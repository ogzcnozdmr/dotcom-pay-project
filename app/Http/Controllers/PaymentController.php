<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Pay;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $payClass = null;

    public string $selectedBank = '';
    public string $orderCode = '';
    public array $selectedVirtualCard = [];
    public string $ip = '';
    public float|string $orderTotal;
    public null|array $order = null;
    public null|array $bankDetail = null;
    public array $cardInformation = [
        'number'      => null,
        'expire'      => null,
        'cvv'         => null,
        'name'        => null,
        'type'        => null,
        'installment' => null
    ];

    public function __construct() {
        parent::__construct();
        /*
         * Veri tabanına kaydeder
         */
        $this->payClass = new Pay();
        /*
         * Ip adresini ekler
         */
        $this->ip = request()->ip();
    }

    /**
     * Ödeme sayfası
     * @param Request $request
     * @return void
     */
    public function start(Request $request) : void
    {
        $this->selectedBank = $request->input('order')['bank'];
        /*
         * Sipariş kodunu oluşturur
         */
        $this->orderCode = date("dmYHi") . "OZI" . rand(10000, 99999);

        $this->order = [
            'user_id' => session()->get('users')['authority'] === 'admin' ? 0 : session()->get('users')['id'],
            'seller_name' => $request->input('customer')['name_surname'],
            'order_number' => $this->orderCode,
            'order_ip' => $this->ip,
            'order_total' => $request->input('order')['total'],
            'order_installment' => $request->input('order')['installment'],
            'pay_bank' => $this->selectedBank,
            'pay_json' => '{}',//
            'pay_result' => 'process',//
            'pay_message' => 'Ödeme bekleniyor',//
            'pay_card_owner' => $request->input('card')['name_surname'],
            'user_phone' => $request->input('customer')['tel'],
            'user_email' => $request->input('customer')['email'],
            'pay_ip' => ''//
        ];

        $this->orderTotal = $this->order['order_total'];

        /*
         * Siparişi oluşturur
         */
        $this->insertDatabase();

        /*
         * Bankayı başlatır
         */
        $this->bankDetail();

        $this->cardInformation['number']      = str_replace(" ","", trim($request->input('card')['number']));
        $this->cardInformation['expire']      = str_replace(" ","", trim($request->input('card')['expiration']));
        $this->cardInformation['cvv']         = str_replace(" ","", trim($request->input('card')['cvv']));
        $this->cardInformation['name']        = $request->input('card')['name_surname'];
        $this->cardInformation['type']        = $request->input('card')['type'];
        $this->cardInformation['installment'] = $request->input('order')['installment'];

        /*
         * Bütün ödeme işlemlerini başlatır
         */
        $this->paymentStart();
    }

    /**
     * ödeme sonucu
     * @param Request $request
     * @param string $bank
     * @param string $installment
     * @return void
     */
    public function result(Request $request, string $bank, string $installment) : void
    {
        $this->selectedBank = $bank;
        /*
         * bankayı başlatır
         */
        $this->bankDetail();

        switch ($this->selectedVirtualCard['virtual_pos_type']) {
            case '1';
                $this->orderCode  = $request->input('oid');
                break;
            case '2':
            case '3';
                $this->orderCode  = $request->input('OrderId');
                break;
            case '4';
                $this->orderCode  = $request->input('VerifyEnrollmentRequestId');
                break;
        }

        /*
         * Sipariş kodunu aldıktan sonra siparişi başlatır
         */
        $this->getOrder();

        switch ($this->selectedVirtualCard['virtual_pos_type']) {
            case '1':
            case '2':
                $hashparams = $request->input("HASHPARAMS");
                $hashparamsval = $request->input("HASHPARAMSVAL");
                $hashparam = $request->input("HASH");
                $paramsval = '';
                $index1 = 0;
                $index2 = 0;

                while ($index1 < strlen($hashparams)) {
                    $index2 = strpos($hashparams, ":", $index1);
                    $vl = $request->input(substr($hashparams, $index1, $index2 - $index1));
                    if ($vl == null)
                        $vl = '';
                    $paramsval = $paramsval . $vl;
                    $index1 = $index2 + 1;
                }

                /*if ($this->selectedVirtualCard['banka_degisken'] === "teb") {
                    $paramsval = $request->input('clientId').$paramsval;//clientId != clientid
                }*/

                $hashval = $paramsval.$this->bankDetail['storekey'];
                $hash = base64_encode(pack('H*',sha1($hashval)));

                /*echo "paramsval     = $paramsval    <br>";
                echo "hashparamsval = $hashparamsval<br>";
                echo "hashparam     = $hashparam    <br>";
                echo "hash          = $hash         <br>";*/

                if ($paramsval != $hashparamsval || $hashparam != $hash) {
                    $this->paymentFinish([
                        "result"  => false,
                        "message" => 'Güvenlik Uyarisi. Sayisal Imza Geçerli Degil.'
                    ]);
                }
                $mdStatus = $request->input("mdStatus");
                //3d Secure iþleminin sonucu mdStatus 1,2,3,4 ise baþarýlý 5,6,7,8,9,0 baþarýsýzdýr
                if ($mdStatus == "1" || $mdStatus == "2" || $mdStatus == "3" || $mdStatus == "4") {
                    $this->paymentContinue([
                        'clientid' => $request->input('clientId'),
                        'expires'  => $request->input('Ecom_Payment_Card_ExpDate_Month').'/'.$request->input('Ecom_Payment_Card_ExpDate_Year'),
                        'cv2'      => $request->input('cv2'),
                        'tutar'    => $request->input('amount'),
                        'taksit'   => $this->installmentControl($installment),
                        'oid'      => $this->orderCode,//Siparis numarası her islem icin farkli olmalidir
                        'email'    => "",
                        'xid'      => $request->input('xid'),// 3d Secure özel alani PayerTxnId
                        'eci'      => $request->input('eci'),// 3d Secure özel alani PayerSecurityLevel
                        'cavv'     => $request->input('cavv'),// 3d Secure özel alani PayerAuthenticationCode
                        'md'       => $request->input('md'),// Eðer 3D iþlembaþarýlýsya provizyona kart numarasý yerine md deðeri gönderilir.
                        'mode'     => 'P',//P olursa gerçek islem, T olursa test islemi yapar
                        'type'     => 'Auth'//Auth: Satýþ PreAuth: Ön Otorizasyon
                    ]);
                } else {
                    $err = $request->input("ErrMsg") ?: '';
                    if ($err !== '') {
                        $err = "($err)";
                    }
                    $this->paymentFinish([
                        "result" => false,
                        "message" => '3D islemi onay almadi'.$err
                    ]);
                }
                break;
            case '3':
                $status = $request->input("3DStatus");
                if ($status == "1") {
                    $this->paymentContinue([
                        'RequestGuid' => $request->input('RequestGuid'),
                        'OrderId' => $request->input('OrderId'),
                        'UserCode' => $this->bankDetail['name'],
                        'UserPass' => $this->bankDetail['password'],
                        'SecureType' => $this->bankDetail['storetype3d'],
                        'tutar'    => $request->input('PurchAmount'),
                        'taksit'   => $this->installmentControl($installment),
                    ]);
                } else {
                    $this->paymentFinish([
                        "result" => false,
                        "message" => '3D islemi onay almadi'
                    ]);
                }
                break;
            case '4':
                $status = $request->input("Status");
                /*
                    Y:Doğrulama başarılı
                    A:Doğrulama tamamlanamadı ancak doğrulama denemesini kanıtlayan
                    CAVV üretildi
                    U:Doğrulama tamamlanamadı
                    E:Doğrulama başarısız.
                    N:Doğrulama başarısız, işlem reddedildi
                */
                if ($status === "Y") {
                    $taksit = $this->installmentControl($installment);
                    $tutar = (substr($request->input('PurchAmount'), 0, -2) ?: 0).'.'.substr($request->input('PurchAmount'), -2);
                    $this->paymentContinue([
                        'pan' => $request->input('Pan'),
                        'expiry' => '20'.$request->input('Expiry'),
                        'cvv' => $request->input('SessionInfo'),
                        'currencyamount' => $request->input('PurchAmount'),
                        'transactionid' => $request->input('VerifyEnrollmentRequestId'),
                        'transactiontype' => 'Sale',
                        'currencycode' => '949',
                        'installments' => $taksit,
                        'cavv' => $request->input('Cavv'),
                        'eci' => $request->input('Eci'),
                        'tutar' => $tutar,
                        'taksit' => $taksit
                    ]);
                } else {
                    $this->paymentFinish([
                        "result" => false,
                        "message" => '3D islemi onay almadi'
                    ]);
                }
                break;
        }
    }

    /**
     * Ödemeyi başlatır
     * @return void
     */
    private function paymentStart() : void
    {
        /*
         * Tarih veya her seferinde degisen bir deger güvenlik amaçli
         */
        $microtime = microtime();

        $okUrl = $failUrl = url()->previous()."/pay/result/{$this->selectedBank}/{$this->cardInformation['installment']}";
        $currency = "949";

        // 3D modelinde hash hesaplamasinda islem tipi ve taksit kullanilmiyor
        switch ($this->selectedVirtualCard['virtual_pos_type']) {
            case '1':
                $hashstr = $this->bankDetail['client'] . $this->orderCode . $this->orderTotal . $okUrl . $failUrl . $microtime . $this->bankDetail['storekey'];
                $hash = base64_encode(pack('H*', sha1($hashstr)));
                break;
            case '2':
                $MbrId= $this->selectedVirtualCard['virtual_pos_type'] === '2' ? '12' : '5';
                $TxnType="Auth";
                $Lang="TR";
                $hashstr = $MbrId . $this->orderCode . $this->orderTotal . $okUrl . $failUrl . $TxnType. $this->cardInformation['installment'] . $microtime  . $this->bankDetail['storekey'];
                $hash = base64_encode(pack('H*', sha1($hashstr)));
                break;
            case '4':
            default :
                //gerekli işlem yok
                break;
        }

        /*ISTEGE BAGLI ALANLAR
        Islem takip numarasi 3D için XID i magaza üretirse o kullanir, yoksa sistem üretiyor. (3D secure islemleri için islem takip numarasi 20 bytelik bilgi 28 karaktere base64 olarak kodlanmali, geçersiz yada bos ise sistem tarafindan üretilir.)
        $lang="";//gösterim dili bos ise Türkçe (tr), Ingilizce için (en)
        $description = $this->order['user_name'].' '.$this->order['user_name_sur'];
        $xid    = $this->order['order_code'];
        $email  = $this->order['user_email'];//email adresi
        $userid = $this->order['user_id'];//Kullanici takibi için id*/

        $expire = explode('/', $this->cardInformation['expire']);
        $expireYear = strlen($expire[1]) === 2 ? '20' . $expire[1] : $expire[1];
        $expireMonth = $expire[0];

        switch ($this->selectedVirtualCard['virtual_pos_type']) {
            case '1':
                $postRequest_url = $this->selectedVirtualCard['api_security_url'];
                $data = [
                    'clientId'  => $this->bankDetail['client'],
                    'storekey'  => $this->bankDetail['storekey'],
                    'storetype' => $this->bankDetail['storetype'],
                    'amount'    => $this->orderTotal,
                    'oid'       => $this->orderCode,
                    'okUrl'     => $okUrl,
                    'failUrl'   => $failUrl,
                    'rnd'       => $microtime,
                    'hash'      => $hash,
                    'currency'  => $currency,
                    //kart bilgileri
                    'pan'       => $this->cardInformation['number'],
                    'cv2'       => $this->cardInformation['cvv'],
                    'Ecom_Payment_Card_ExpDate_Year' => $expireYear,
                    'Ecom_Payment_Card_ExpDate_Month' => $expireMonth,
                    'cardType'  => $this->cardInformation['type']//1 visa 2 mastercard
                ];
                break;
            case '2':
                $postRequest_url = $this->selectedVirtualCard['api_security_url'];
                $data = [
                    'MbrId' => $MbrId,
                    'MerchantID' => $this->bankDetail['client'],
                    'UserCode' => $this->bankDetail['name'],
                    'UserPass' => $this->bankDetail['password'],
                    'SecureType' => $this->bankDetail['storetype'],
                    'TxnType' => $TxnType,
                    'InstallmentCount' => $this->cardInformation['installment'],
                    'Currency' => $currency,
                    'OkUrl' => $okUrl,
                    'FailUrl' => $failUrl,
                    'OrderId' => $this->orderCode,
                    'OrgOrderId' => $this->orderCode,
                    'PurchAmount' => $this->orderTotal,
                    'Lang' => $Lang,
                    'Rnd' => $microtime,
                    'Hash' => $hash,
                    'Pan'=> $this->cardInformation['number'],
                    'Cvv2' => $this->cardInformation['cvv'],
                    'Expiry' => $this->cardInformation['expire'],
                ];
                break;
            case '3':
                $postRequest_url = $this->selectedVirtualCard['api_security_url'];
                $data = [
                    'MbrId' => $MbrId,
                    'MerchantID' => $this->bankDetail['client'],
                    'UserCode' => $this->bankDetail['name'],
                    'SecureType' => $this->bankDetail['storetype'],
                    'TxnType' => $TxnType,
                    'InstallmentCount' => $this->cardInformation['installment'],
                    'Currency' => $currency,
                    'OkUrl' => $okUrl,
                    'FailUrl' => $failUrl,
                    'OrderId' => $this->orderCode,
                    'OrgOrderId' => "",
                    'PurchAmount' => $this->orderTotal,
                    'Lang' => $Lang,
                    'Rnd' => $microtime,
                    'Hash' => $hash,
                    'CardHolderName'=> $this->cardInformation['name'],
                    'Pan'=> $this->cardInformation['number'],
                    'Cvv2' => $this->cardInformation['cvv'],
                    'Expiry' => $expireMonth.substr($expireYear, 2),
                ];
                break;
            case '4':
                /*
                 * TODO::DİKKAT ET
                 */
                if (str_contains($this->orderTotal, '.')) {
                    $exp = explode('.', $this->orderTotal);
                    $strlen = strlen($exp[1]);
                    if ($strlen > 2) {
                        $exp[1] = substr($exp[1], 0, 2);
                    } else {
                        if ($strlen === 1) {
                            $exp[1] .= '0';
                        } else {
                            $exp[1] = '00';
                        }
                    }
                    $this->orderTotal = implode('.', $exp);
                } else {
                    $this->orderTotal .= '.00';
                }
                $curldata = [
                    'MerchantID' => $this->bankDetail['name'],
                    'MerchantPassword' => $this->bankDetail['password'],
                    'VerifyEnrollmentRequestId' => $this->orderCode,
                    'Pan'=> $this->cardInformation['number'],
                    'ExpiryDate' => substr($expireYear, 2, 2).$expireMonth,
                    'PurchaseAmount' => $this->orderTotal,
                    'Currency' => $currency,
                    'BrandName' => $this->cardInformation['type'] === '1' ? '100' : '200', //1 visa 2 mastercard
                    'SuccessUrl' => $okUrl,
                    'FailureUrl' => $failUrl,
                    'SessionInfo' => $this->cardInformation['cvv']
                ];
                if (intval($this->cardInformation['installment']) > 1) {
                    $curldata['InstallmentCount'] = $this->cardInformation['installment'];
                }
                //TODO:CURL YERİNE REQUEST KULLAN
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$this->selectedVirtualCard['api_security_url']);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curldata));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 90);
                $curlresult = simplexml_load_string(curl_exec($ch));
                curl_close($ch);
                if (isset($curlresult->Message->VERes->Status)) {
                    /*
                     * sonuç başarılı
                     */
                    if ($curlresult->Message->VERes->Status == 'Y') {
                        $postRequest_url = (string) $curlresult->Message->VERes->ACSUrl;
                        $data = [
                            'PaReq' => (string) $curlresult->Message->VERes->PaReq,
                            'TermUrl' => (string) $curlresult->Message->VERes->TermUrl,
                            'MD' => (string) $curlresult->Message->VERes->MD
                        ];
                    } else {
                        $this->paymentFinish([
                            "result" => false,
                            "message" => 'cur'.((string) $curlresult->ErrorMessage)
                        ]);
                    }
                } else {
                    $this->paymentFinish([
                        "result" => false,
                        "message" => '3D islemi onay almadi'
                    ]);
                }
                break;
        }
        echo $this->postRequest($postRequest_url, $data);
    }

    /**
     * Ödemeyi devam ettirir
     * @param array $request
     * @return void
     */
    private function paymentContinue(array $request) : void
    {
        /*
         * Ödemeyi apiye yollayacak değerler ayarlanır
         */
        $sanalPos = $this->setPaymentValue($request);

        /*
         * Ödeme apiye gönderiliyor
         */
        switch($this->selectedVirtualCard['virtual_pos_type']) {
            case '1':
            case '2':
            case '4':
                $odemeSonuc = $this->paymentSend($sanalPos);
                break;
            case '3':
            default:
                $odemeSonuc = [];
                foreach(explode(";;", $this->paymentSend($sanalPos)) as $sonuc) {
                    list($key, $value)= explode("=", $sonuc);
                    $odemeSonuc[$key] = $value;
                }
                break;
        }

        /*
         * Dönen xml sonuçlarının değerlendirilmesi
         */
        $result = $this->resultXML($odemeSonuc);

        /*
         * Sonuçların ekrana yazdırılması
         */
        $this->paymentFinish($result);
    }

    /**
     * Ödeme değerlerini ayarlar
     * @param array $value
     * @return string
     */
    private function setPaymentValue(array $value) : string
    {
        switch($this->selectedVirtualCard['virtual_pos_type']) {
            case '1';
            case '2':
                $request =
                    "<?xml version=\"1.0\" encoding=\"ISO-8859-9\"?>".
                    "<CC5Request>".
                    "<Name>{NAME}</Name>".
                    "<Password>{PASSWORD}</Password>".
                    "<ClientId>{CLIENTID}</ClientId>".
                    "<IPAddress>{IP}</IPAddress>".
                    "<Email>{EMAIL}</Email>".
                    "<Mode>{MODE}</Mode>".
                    "<OrderId>{OID}</OrderId>".
                    "<GroupId></GroupId>".
                    "<TransId></TransId>".
                    "<UserId></UserId>".
                    "<Type>{TYPE}</Type>".
                    "<Number>{MD}</Number>".
                    "<Expires></Expires>".
                    "<Cvv2Val></Cvv2Val>".
                    "<Total>{TUTAR}</Total>".
                    "<Currency>949</Currency>".
                    "<Taksit>{TAKSIT}</Taksit>".
                    "<PayerTxnId>{XID}</PayerTxnId>".
                    "<PayerSecurityLevel>{ECI}</PayerSecurityLevel>".
                    "<PayerAuthenticationCode>{CAVV}</PayerAuthenticationCode>".
                    "<CardholderPresentCode>13</CardholderPresentCode>".
                    "<BillTo>".
                    "<Name></Name>".
                    "<Street1></Street1>".
                    "<Street2></Street2>".
                    "<Street3></Street3>".
                    "<City></City>".
                    "<StateProv></StateProv>".
                    "<PostalCode></PostalCode>".
                    "<Country></Country>".
                    "<Company></Company>".
                    "<TelVoice></TelVoice>".
                    "</BillTo>".
                    "<ShipTo>".
                    "<Name></Name>".
                    "<Street1></Street1>".
                    "<Street2></Street2>".
                    "<Street3></Street3>".
                    "<City></City>".
                    "<StateProv></StateProv>".
                    "<PostalCode></PostalCode>".
                    "<Country></Country>".
                    "</ShipTo>".
                    "<Extra></Extra>".
                    "</CC5Request>";
                $request=str_replace("{NAME}",$this->bankDetail['name'],$request);
                $request=str_replace("{PASSWORD}",$this->bankDetail['password'],$request);
                $request=str_replace("{CLIENTID}",$value['clientid'],$request);
                $request=str_replace("{IP}",$this->ip,$request);
                $request=str_replace("{OID}",$this->orderCode,$request);//$value['oid']
                $request=str_replace("{MODE}",$value['mode'],$request);
                $request=str_replace("{TYPE}",$value['type'],$request);
                $request=str_replace("{XID}",$value['xid'],$request);
                $request=str_replace("{ECI}",$value['eci'],$request);
                $request=str_replace("{CAVV}",$value['cavv'],$request);
                $request=str_replace("{MD}",$value['md'],$request);
                $request=str_replace("{TUTAR}",$value['tutar'],$request);
                $request=str_replace("{TAKSIT}",$value['taksit'],$request);
                break;
            case '3':
                $request = http_build_query(array(
                    'RequestGuid' => $value['RequestGuid'],
                    'OrderId' => $value['OrderId'],
                    'UserCode' => $value['UserCode'],
                    'UserPass' => $value['UserPass'],
                    'SecureType' => $value['SecureType']
                ));
                break;
            case '4':
                $request =
                    "<?xml version=\"1.0\" encoding=\"ISO-8859-9\"?>".
                    "<VposRequest>".
                    "<MerchantId>{NAME}</MerchantId>".
                    "<Password>{PASSWORD}</Password>".
                    "<TerminalNo>{CLIENTID}</TerminalNo>".
                    "<TransactionType>{TRANSACTIONTYPE}</TransactionType>";
                //"<TransactionId>{TRANSACTIONID}</TransactionId>";
                if($value['installments'] !== ""){
                    $request .= "<NumberOfInstallments>{INSTALLMENTS}</NumberOfInstallments>";
                }
                $request .= "<CurrencyAmount>{CURRENCYAMOUNT}</CurrencyAmount>".
                    "<CurrencyCode>{CURRENCYCODE}</CurrencyCode>".
                    "<Pan>{PAN}</Pan>".
                    "<Cvv>{CVV}</Cvv>".
                    "<ECI>{ECI}</ECI>".
                    "<CAVV>{CAVV}</CAVV>".
                    "<MpiTransactionId>{MPITRANSACTIONID}</MpiTransactionId>".
                    "<Expiry>{EXPIRY}</Expiry>".
                    "<TransactionDeviceSource>0</TransactionDeviceSource>".
                    "<ClientIp>{IP}</ClientIp>".
                    "</VposRequest>";
                $request=str_replace("{NAME}",$this->bankDetail['name'],$request);
                $request=str_replace("{PASSWORD}",$this->bankDetail['password'],$request);
                $request=str_replace("{CLIENTID}",$this->bankDetail['client'],$request);
                $request=str_replace("{TRANSACTIONTYPE}",$value['transactiontype'],$request);//$value['oid']
                //$request=str_replace("{TRANSACTIONID}",$value['transactionid'],$request);
                $request=str_replace("{CURRENCYAMOUNT}",$value['tutar'],$request);
                $request=str_replace("{CURRENCYCODE}",$value['currencycode'],$request);
                $request=str_replace("{PAN}",$value['pan'],$request);
                $request=str_replace("{CVV}",$value['cvv'],$request);
                $request=str_replace("{ECI}",$value['eci'],$request);
                $request=str_replace("{CAVV}",$value['cavv'],$request);
                $request=str_replace("{MPITRANSACTIONID}",$value['transactionid'],$request);
                $request=str_replace("{EXPIRY}",$value['expiry'],$request);
                $request=str_replace("{IP}",$this->ip,$request);
                if ($value['installments'] !== '') {
                    $request=str_replace("{INSTALLMENTS}",$value['installments'],$request);
                }
                break;
            default:
                $this->paymentFinish([
                    "result"  => 0,
                    "message" => "Hatalı sanal kart tipi."
                ]);
        }
        return $request;
    }

    /**
     * Banka bilgisini getirir
     * @return void
     */
    private function bankDetail() : void
    {
        $bankModel = new Bank();
        $bank = current($bankModel->__data(null, '*', ['bank_variable' => $this->selectedBank]));
        /*
         * Banka bilgisi bulunduysa
         */
        if (!empty($bank)) {
            $this->bankDetail = __json_decode($bank['bank_json'], true);
            $this->selectedVirtualCard = $bank;
        } else {
            $this->paymentFinish([
                "result" => false,
                "message" => "Banka bilgisi bulunamadı."
            ]);
        }
    }

    /**
     * Siparişi getirir
     * @return void
     */
    private function getOrder() : void
    {
        $order = current(Orders::__data_pay(null, 'user_id,user_email,order_code,order_total,user_name,user_name_sur,user_phone', ['order_code' => $this->orderCode]));
        /*
         * Sipariş varsa
         */
        if (!empty($order)) {
            $this->order = $order;
            /*
             * SATIS TARAFI
             */
            $this->orderTotal = str_replace(",", ".", (string) $order['order_total']);
        } else {
            $this->paymentFinish([
                "result" => false,
                "message" => "Sipariş bilgisi bulunamadı."
            ]);
        }
    }

    /**
     * Ödemeyi bitirir
     * @param null|array $json
     * @return void
     */
    private function paymentFinish(null|array $json = null) : void
    {
        $json['result'] = $json['result'] ? '1' : '0';
        $siparisUrl = url()->previous()."/pay/{$this->orderCode}";
        /*
         * Sipariş bilgisi varsa email ve bildirim yollar
         */
        if (!empty($this->order) && false) {//TODO::TAPILACAK
            $alici   = $this->order['user_email'];
            $konu    = 'ONLİNE ÖDEME';

            if ($json['result'] == '1') {
                $sonucText = 'Teşekkür ederiz. Online ödeme yapıldı.';
            } else {
                $sonucText = 'Online ödeme işlemi başarısız.';
            }
            $icerik = "<b> {$sonucText} </b> <br/> Sipariş kodunuz: <a href='{$siparisUrl}' target='_blank'>{$this->orderCode}</a> ile sipariş detaylarınızı öğrenebilirsiniz.";
            //__mail_send(date('Y-m-d H:i:s'), $this->order['order_total'], $sonucText, $alici);
            __notification_send($konu, "{$icerik}<br/>Message: {$json['message']}", $json['result'] === '1' ? 'success' : 'danger');
        }
        /*
         * Yönlendirmeyi yapar
         */
        $redirectLink = "{$siparisUrl}?result={$json['result']}&message={$json['message']}";
        if ($json['result'] === '1') {
            $redirectLink .= "&tutar={$this->order['order_total']}";
        }
        header("Location: $redirectLink");
        die();
    }

    /**
     * Taksit kontrolü yapar
     * @param mixed $taksit
     * @return int|string
     */
    private function installmentControl(mixed $taksit) : int|string
    {
        if (!is_numeric($taksit) || (int) $taksit < 2) {
            return '';
        } else {
            return (int) $taksit;
        }
    }

    /**
     * Ödemeyi gönderir
     * @param string|array $request
     * @return string|array|null
     */
    private function paymentSend(string|array $request) : null|string|array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->selectedVirtualCard['api_url']);
        switch ($this->selectedVirtualCard['virtual_pos_type']) {
            case '1':
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "DATA=" . $request);
                break;
            case '2':
                curl_setopt($ch, CURLOPT_POST, TRUE);
                break;
            case '3':
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                break;
            case '4':
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "prmstr=" . $request);
                break;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Xml sonucunu parçalayıp diziye çevirir
     * @param null|string $odemeSonucXML
     * @return array
     */
    private function resultXML(null|string $odemeSonucXML) : array
    {
        switch ($this->selectedVirtualCard['virtual_pos_type']) {
            case '1':
            case '2':
                $xml = simplexml_load_string($odemeSonucXML);
                $response = $xml->Response == "Approved";
                $error = isset($xml->ErrMsg) ? (string) $xml->ErrMsg : '';
                break;
            case '3':
                $xml = $odemeSonucXML;
                $response = $xml['ProcReturnCode'] === "00";
                $error = isset($xml['ErrMsg']) ? (string) $xml['ErrMsg'] : '';
                break;
            case '4':
            default:
                $xml = simplexml_load_string($odemeSonucXML);
                $response = (string) $xml->ResultCode === "0000";
                $error = isset($xml->ResultDetail) ? (string) $xml->ResultDetail : '';
                break;
        }

        /*
         * Sonuçların veritabanından güncellenmesi
         */
        $this->updateDatabase([
            "pay_json"    => json_encode($xml, true),
            "pay_date"    => date('Y-m-d H:i:s'),
            "pay_result"  => $response ? 'success' : 'error',
            "pay_message" => $error,
        ]);

        return [
            "result"  => $response,
            "message" => $response ? 'Ödeme işlemi başarıyla gerçekleştirildi' : $error
        ];
    }

    /**
     * Veri tabanına kaydeder
     * @return void
     */
    private function insertDatabase() : void
    {
        $this->payClass->__create($this->order);
    }

    /**
     * Veri tabanından günceller
     * @param array $array
     * @return void
     */
    private function updateDatabase(array $array) : void
    {
        $this->payClass->__update(null, $array, ['order_number' => $this->orderCode]);
    }

    /**
     * 3d için yönlendirme
     * @param string $url
     * @param array $params
     * @return false|string
     */
    private function postRequest(string $url, array $params) {
        $query_content = http_build_query($params);
        $fp = fopen($url, 'r', FALSE, // do not use_include_path
            stream_context_create([
                'http' => [
                    'header'  => [ // header array does not need '\r\n'
                        'Content-type: application/x-www-form-urlencoded',
                        'Content-Length: ' . strlen($query_content)
                    ],
                    'method'  => 'POST',
                    'content' => $query_content
                ]
            ])
        );
        if ($fp === FALSE) {
            return __json_encode(['error' => 'Failed to get contents...']);
        }
        $result = stream_get_contents($fp); // no maxlength/offset
        fclose($fp);
        return $result;
    }
}
