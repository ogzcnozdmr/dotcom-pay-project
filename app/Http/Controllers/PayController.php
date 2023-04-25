<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Installment;
use App\Models\Pay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayController extends Controller
{
    private array $pv = [
        
        'bank_selected' => '',
        'bank_selected_arr' => [],//$this->pv['bank_selected_arr']['api_url'],$this->pv['bank_selected_arr']['virtual_pos_type']
        
        'bank_json' => [],//$this->pv['bank_json']['name'],$this->pv['bank_json']['password'],$this->pv['bank_json']['client_id'],$this->pv['bank_json']['user_prov_id']
        
        /*
         * GENEL TARAF
         */
        'general' => [
            'address_ip' => '',
            'address_email' => '',
            'numara_siparis' => '',
            'numara_user' => ''
        ],

        /*
         * Kart tarafı
         */
        'card' => [
            'kart_numara' => '',
            'kart_son_kullanma_tarih' => '',
            'kart_cvc' => ''
        ],

        /*
         * Ödeme tarafı
         */
        'pay' => [
            'total' => '',
            'installment' => ''
        ],

        /*
         * Gönderen tarafı
         */
        'sender' => [
            'gonderen_isimi' => '',
            'gonderen_firma' => '',
            'gonderen_adres' => '',
            'gonderen_sehir' => '',
            'gonderen_posta_kod' => '',
            'gonderen_telefon' => '',
        ],

        /*
         * Alıcı tarafı
         */
        'buyer' => [
            'alici_isim' => '',
            'alici_adres' => '',
            'alici_sehir' => '',
            'alici_posta_kod' => ''
        ]
    ];
    public function list() : View
    {
        $this->startIllegal('public');
        $this->starOfficialDistributor();
        return view('pay-list');
    }
    /**
     * Ödeme Ekranı
     * @return View
     */
    public function screen() : View
    {
        $this->startIllegal('public');
        $installment = new Installment(false);
        $bank = new Bank(false);

        return view('pay-screen', [
            'installment' => $installment->__data_available('installment_number'),
            'bank' => $bank->__data(null, 'bank_name,bank_variable,plus_installment,bank_photo,max_installment,min_installment_amount')
        ]);
    }
    /**
     * Ödeme listesini getirir
     * @return void
     */
    public function postList() : void
    {
        $result =[];
        $row = 0;

        $pay = new Pay();
        $lists = $pay->payList(session()->get('users')['authority'], session()->get('users')['id']);
        foreach ($lists as $get) {
            $taksit = $get['order_installment'] !== 0 ? $get['order_installment'] : 'PEŞİN';
            $link = $get['user_id'] == '0' ? $get['seller_name'] : '<a href="seller/pay/'.$get['user_id'].'">'.$get['seller_name'].'<a/>';

            $result[] = [
                (++$row),
                $link,
                $get['pay_card_owner'],
                $get['order_total'],
                $taksit,
                $get['bank_name'],
                date_translate($get['pay_date'],2),
                __pay_result_titles($get['pay_result'])
            ];
        }
        echo __json_encode($result);
    }
    /**
     * İstatistikleri getirir
     * @return void
     */
    public function dashboard() : void
    {
        $value = date("Y-m-d H:i:s");
        $pay = new Pay();

        $date = explode(" ", $value);
        $year = explode("-", $date[0]);

        $yil = $year[0];
        $ay = $year[1];

        $toplam_ay = 12;//toplam ay sayısı
        $gosterilecek_ay = 6;//gösterilecek ay sayısı

        $array = [];
        $goster_yil = $yil;
        for ($i = 0;$i < $gosterilecek_ay;$i++) {

            $goster_ay = ($ay-$i)%($toplam_ay+1);
            if ($goster_ay < 1) {
                $goster_ay = $toplam_ay+$goster_ay;
                if($goster_yil == $yil) $goster_yil--;
            }

            $ilkay = "{$goster_yil}-{$goster_ay}-1 0:0:0";
            $ikinciay = month_next($goster_yil, $goster_ay);

            $basarili_satis_get = current($pay->__pay_success($ilkay, $ikinciay));

            $toplam_satis = round($basarili_satis_get['total'] ?? 0,2);

            $odeme_istegi_get = $pay->__pay_total_count($ilkay, $ikinciay);
            $odeme_istegi = round($odeme_istegi_get,2);

            //'Toplam Satış', 'Ödeme İsteği', 'Başarılı Ödeme'
            $array[] = [
                "y" => "{$goster_yil}-{$goster_ay}",
                "a" => (int) ceil($toplam_satis) ?: 0,
                "b" => (int) $odeme_istegi ?: 0,
                "c" => (int) ($basarili_satis_get['success'] ?? 0)
            ];
        }
        echo __json_encode($array);
    }
    /**
     * Ödeme requesti
     * @param Request $request
     * @return void
     */
    public function payRequest(Request $request) {
        $bank = new Bank();
        $this->pv['bank_selected'] = $request->input('satis')['banka'];
        $this->pv['bank_selected_arr'] = current($bank->__data(null, '*', ['bank_variable' => $this->pv['bank_selected']]));
        /*
         * Banka bulunamadıysa
         */
        if (empty($this->pv['bank_selected_arr'])) {
            $this->result['result'] = false;
            $this->result['message'] = 'Banka bilgisi bulunamadı';
            echo __json_encode($this->result);
            die();
        }
        $this->pv['bank_json'] = __json_decode($this->pv['bank_selected_arr']['bank_json'], true);

        //GENEL TARAF
        $this->pv['general']['address_ip'] = __ip();
        $this->pv['general']['address_email'] = $request->input('musteri')['email'];
        $this->pv['general']['numara_siparis'] = date("dmYHi") . "00" . rand(10000, 99999);
        $this->pv['general']['numara_user'] = '';

        //KART TARAFI
        $this->pv['card']['kart_numara'] = str_replace(" ", "", trim($request->input('kart')['numara']));
        $this->pv['card']['kart_son_kullanma_tarih'] = str_replace(" ", "", trim($request->input('kart')['son_kullanma']));
        $this->pv['card']['kart_cvc'] = str_replace(" ", "", trim($request->input('kart')['cvc']));

        //SATIS TARAFI
        $this->pv['pay']['amount'] = str_replace(",", ".", (string) $request->input('satis')['tutar']);
        $this->pv['pay']['installment'] = !is_numeric($request->input('satis')['taksit']) || (int) $request->input('satis')['taksit'] < 2 ? '' : ((int) $request->input('satis')['taksit']);

        //GONDEREN TARAFI
        $this->pv['sender']['gonderen_isim'] = $request->input('kart')['ad_soyad'];
        $this->pv['sender']['gonderen_firma'] = $request->input('musteri')['ad_soyad'];
        $this->pv['sender']['gonderen_adres'] = '';
        $this->pv['sender']['gonderen_sehir'] = '';
        $this->pv['sender']['gonderen_posta_kod'] = '';
        $this->pv['sender']['gonderen_telefon'] = $request->input('musteri')['tel'];

        //ALICI TARAFI
        $this->pv['buyer']['alici_isim'] = '';
        $this->pv['buyer']['alici_adres'] = '';
        $this->pv['buyer']['alici_sehir'] = '';
        $this->pv['buyer']['alici_posta_kod'] = '';



        $sanalpos_xml = '';
        //ÖDEMEYİ APİYE YOLLAYACAK DEĞERLER AYARLANIYOR
        $sanalpos_xml = $this->pay_variable_set($sanalpos_xml);
        //ÖDEME APİYE GÖNDERİLİYOR VE XML SONUCU DÖNÜYOR
        $odeme_sonuc_xml = $this->pay_send($sanalpos_xml);
        //DÖNEN XML SONUCUNUN DEĞERLENDİRİLİP EKRANA YAZILMASI
        $result_json = $this->xml_result($odeme_sonuc_xml);
        echo __json_encode($result_json);
    }
    //$this->pv['bank_selected_arr']['virtual_pos_type'] == 1 ise Secilen_banka=="ak_bank" || "is_bank" || "halk_bank" || "finans_bank"
    //$this->pv['bank_selected_arr']['virtual_pos_type'] == 2 ise Secilen_banka=="vakifbank"
    /**
     * Ödeme değerlerini ayarlar
     * @param string $sanalpos_xml
     * @return string
     */
    private function pay_variable_set(string $sanalpos_xml) : string
    {
        $eski_satis_tutar = $this->pv['pay']['amount'];

        if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 1) {
            $sanalpos_xml =
                '<?xml version="1.0" encoding="ISO-8859-9"?>
			<CC5Request>
				<Name>{SANAL_NAME}</Name>
				<Password>{SANAL_PASSWORD}</Password>
				<ClientId>{SANAL_CLIENT_ID}</ClientId>
				<IPAddress>{ADDRESS_IP}</IPAddress>
				<Email>{ADDRESS_EMAIL}</Email>
				<Mode>P</Mode>
				<OrderId>{NUMARA_SIPARIS}</OrderId>
				<GroupId></GroupId>
				<TransId></TransId>
				<UserId>{NUMARA_USER}</UserId>
				<Type>Auth</Type>
				<Number>{KART_NUMARA}</Number>
				<Expires>{KART_SON_KULLANMA_TARIH}</Expires>
				<Cvv2Val>{KART_CVC}</Cvv2Val>
				<Total>{SATIS_TUTAR}</Total>
				<Currency>949</Currency>
				<Taksit>{SATIS_TAKSIT}</Taksit>
				<BillTo>
					<Name>{GONDEREN_ISIM}</Name>
					<Company>{GONDEREN_FIRMA}</Company>
					<Street1>{GONDEREN_ADRES}</Street1>
					<Street2></Street2>
					<Street3></Street3>
					<City>{GONDEREN_SEHIR}</City>
					<StateProv></StateProv>
					<PostalCode>{GONDEREN_POSTA_KOD}</PostalCode>
					<Country></Country>
					<TelVoice>{GONDEREN_TELEFON}</TelVoice>
				</BillTo>
				<ShipTo>
					<Name>{ALICI_ISIM}</Name>
					<Street1>{ALICI_ADRES}</Street1>
					<Street2></Street2>
					<Street3></Street3>
					<City>{ALICI_SEHIR}</City>
					<StateProv></StateProv>
					<PostalCode>{ALICI_POSTA_KOD}</PostalCode>
					<Country></Country>
				</ShipTo>
				<Extra></Extra>
			</CC5Request>';
        } else if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 2) {
            //MerchantId -> sanal_name
            //Password -> sanal_password
            //TerminalNo -> sanal_client_id

            $exp = explode("/", $this->pv['card']['kart_son_kullanma_tarih']);
            $this->pv['card']['kart_son_kullanma_tarih'] = "20" . $exp[1] . $exp[0];

            $sanalpos_xml =
                '<VposRequest>
				<MerchantId>{SANAL_NAME}</MerchantId>
				<Password>{SANAL_PASSWORD}</Password>
				<TerminalNo>{SANAL_CLIENT_ID}</TerminalNo>
				<TransactionType>Sale</TransactionType>
				<TransactionId>{NUMARA_SIPARIS}</TransactionId>
				<CurrencyAmount>{SATIS_TUTAR}</CurrencyAmount>
				<CurrencyCode>949</CurrencyCode>
				{VAKIF_TAKSIT}
				<Pan>{KART_NUMARA}</Pan>
				<Cvv>{KART_CVC}</Cvv>
				<Expiry>{KART_SON_KULLANMA_TARIH}</Expiry>
				<TransactionDeviceSource>0</TransactionDeviceSource>
				<ClientIp>{ADDRESS_IP}</ClientIp>
			</VposRequest>';

            if ($this->pv['pay']['installment'] !== "") {//satis taksit varsa vakıfa ekliyoruz
                $sanalpos_xml = str_replace("{VAKIF_TAKSIT}", "<NumberOfInstallments>" . $this->pv['pay']['installment'] . "</NumberOfInstallments>", $sanalpos_xml);
            } else $sanalpos_xml = str_replace("{VAKIF_TAKSIT}", "", $sanalpos_xml);

            //VAKIFBANK AYARLARIN AYARLANMASI
            $sanalpos_vakif_xml_degiskenleri = ["{VAKIF_TAKSIT}"];
            $sanalpos_vakif_xml_degerleri = [[1]];//TODO::$vakif_taksit İDİ ama değer yok
            $sanalpos_xml = str_replace($sanalpos_vakif_xml_degiskenleri, $sanalpos_vakif_xml_degerleri, $sanalpos_xml);
        } else if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 3) {

            $data_security = strtoupper(sha1($this->pv['bank_json']['password'] . ("0" . (string)$this->pv['bank_json']['name'])));//sayıyı tamamlamak için 0 ekliyoruz başına

            if (strstr($this->pv['pay']['amount'], ".")) {
                $exp = explode(".", $this->pv['pay']['amount']);
                if ((int)$exp[0] < 1) {
                    $exp[0] = "";
                }

                if ((int)$exp[1] < 10) {
                    $this->pv['pay']['amount'] = (string)$exp[0] . substr((string)$exp[1], 0, 1) . "0";
                } else {
                    $this->pv['pay']['amount'] = (string)$exp[0] . substr((string)$exp[1], 0, 2);
                }
            } else {
                $this->pv['pay']['amount'] = (string)$this->pv['pay']['amount'] . "00";
            }

            $this->pv['card']['kart_son_kullanma_tarih'] = str_replace("/", "", $this->pv['card']['kart_son_kullanma_tarih']);
            $data_hash = strtoupper(sha1($this->pv['general']['numara_siparis'] . $this->pv['bank_json']['name'] . $this->pv['card']['kart_numara'] . $this->pv['pay']['amount'] . $data_security));

            $sanalpos_xml = '<?xml version="1.0" encoding="UTF-8"?>
			<GVPSRequest>
			  <Mode>PROD</Mode>
			  <Version>v0.01</Version>
			  <Terminal>
			    <ProvUserID>{USER_PROV_ID}</ProvUserID>
			    <HashData>{DATA_HASH}</HashData>
			    <UserID>{USER_PROV_ID}</UserID>
			    <ID>{SANAL_NAME}</ID>
			    <MerchantID>{SANAL_CLIENT_ID}</MerchantID>
			  </Terminal>
			  <Customer>
			    <IPAddress>{ADDRESS_IP}</IPAddress>
			    <EmailAddress>{ADDRESS_EMAIL}</EmailAddress>
			  </Customer>
			  <Card>
			    <Number>{KART_NUMARA}</Number>
			    <ExpireDate>{KART_SON_KULLANMA_TARIH}</ExpireDate>
			    <CVV2>{KART_CVC}</CVV2>
			  </Card>
			  <Order>
			    <OrderID>{NUMARA_SIPARIS}</OrderID>
			    <GroupID></GroupID>
			    <AddressList>
			      <Address>
			        <Type>S</Type>
			        <Name>{GONDEREN_ISIM}</Name>
			        <LastName></LastName>
			        <Company>{GONDEREN_SEHIR}</Company>
			        <Text></Text>
			        <District></District>
			        <City></City>
			        <PostalCode></PostalCode>
			        <Country></Country>
			        <PhoneNumber></PhoneNumber>
			      </Address>
			    </AddressList>
			  </Order>
			  <Transaction>
			    <Type>sales</Type>
			    <InstallmentCnt>{SATIS_TAKSIT}</InstallmentCnt>
			    <Amount>{SATIS_TUTAR}</Amount>
			    <CurrencyCode>949</CurrencyCode>
			    <CardholderPresentCode>0</CardholderPresentCode>
			    <MotoInd>N</MotoInd>
			    <Description></Description>
			    <OriginalRetrefNum></OriginalRetrefNum>
			  </Transaction>
			</GVPSRequest>';

            //GARANTİ AYARLARIN AYARLANMASI
            $sanalpos_garanti_xml_degiskenleri = ["{DATA_HASH}", "{USER_PROV_ID}"];
            $sanalpos_garanti_xml_degerleri = [$data_hash, $this->pv['bank_json']['user_prov_id']];
            $sanalpos_xml = str_replace($sanalpos_garanti_xml_degiskenleri, $sanalpos_garanti_xml_degerleri, $sanalpos_xml);
        } else {
            exit();
        }

        //ŞİFRELERİN AYARLANMASI
        $sanalpos_sifre_xml_degiskenleri = ["{SANAL_NAME}", "{SANAL_PASSWORD}", "{SANAL_CLIENT_ID}"];
        $sanalpos_sifre_xml_degerleri = [$this->pv['bank_json']['name'], $this->pv['bank_json']['password'], $this->pv['bank_json']['client_id']];
        $sanalpos_xml = str_replace($sanalpos_sifre_xml_degiskenleri, $sanalpos_sifre_xml_degerleri, $sanalpos_xml);

        //DEĞİŞKENLERİN AYARLANMASI
        $sanalpos_degisken_xml_degiskenleri = [
            "{ADDRESS_IP}",//GENEL TARAF
            "{ADDRESS_EMAIL}",
            "{NUMARA_SIPARIS}",
            "{NUMARA_USER}",

            "{KART_NUMARA}",//KART TARAFI
            "{KART_SON_KULLANMA_TARIH}",
            "{KART_CVC}",

            "{SATIS_TUTAR}",//SATIS TARAFI
            "{SATIS_TAKSIT}",

            "{GONDEREN_ISIM}",//GONDEREN TARAFI
            "{GONDEREN_FIRMA}",
            "{GONDEREN_ADRES}",
            "{GONDEREN_SEHIR}",
            "{GONDEREN_POSTA_KOD}",
            "{GONDEREN_TELEFON}",

            "{ALICI_ISIM}",//ALICI TARAFI
            "{ALICI_ADRES}",
            "{ALICI_SEHIR}",
            "{ALICI_POSTA_KOD}"
        ];

        $sanalpos_degisken_xml_degerleri = [
            $this->pv['general']['address_ip'],//GENEL TARAF
            $this->pv['general']['address_email'],
            $this->pv['general']['numara_siparis'],
            $this->pv['general']['numara_user'],

            $this->pv['card']['kart_numara'],//KART TARAFI
            $this->pv['card']['kart_son_kullanma_tarih'],
            $this->pv['card']['kart_cvc'],

            $this->pv['pay']['amount'],//SATIS TARAFI
            $this->pv['pay']['installment'],

            $this->pv['sender']['gonderen_isim'],//GONDEREN TARAFI
            $this->pv['sender']['gonderen_firma'],
            $this->pv['sender']['gonderen_adres'],
            $this->pv['sender']['gonderen_sehir'],
            $this->pv['sender']['gonderen_posta_kod'],
            $this->pv['sender']['gonderen_telefon'],

            $this->pv['buyer']['alici_isim'],////ALICI TARAFI
            $this->pv['buyer']['alici_adres'],
            $this->pv['buyer']['alici_sehir'],
            $this->pv['buyer']['alici_posta_kod']
        ];
        $this->pv['pay']['amount'] = $eski_satis_tutar;
        return str_replace($sanalpos_degisken_xml_degiskenleri, $sanalpos_degisken_xml_degerleri, $sanalpos_xml);
    }
    /**
     * Ödeme gönderir
     * @param $sanalpos_xml
     * @return bool|string
     */
    private function pay_send($sanalpos_xml)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->pv['bank_selected_arr']['api_url']);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 59);
        if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 1) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, "DATA=" . $sanalpos_xml);
        } else if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 2) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, "prmstr=" . $sanalpos_xml);
            curl_setopt($ch, CURLOPT_SSLVERSION, "CURL_SSLVERSION_TLSv1_1");
        } else if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 3) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . $sanalpos_xml);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    /**
     * XML result
     * @param $odeme_sonuc_xml
     * @return array
     */
    private function xml_result($odeme_sonuc_xml)
    {
        $response = 0;
        $error = '';
        $xml = simplexml_load_string($odeme_sonuc_xml);
        if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 1) {
            $response = $xml->Response == "Approved" ? 1 : 0;
            $error = isset($xml->ErrMsg) ? (string) $xml->ErrMsg : '';
        } else if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 2) {
            $response = $xml->ResultCode == "0000" ? 1 : 0;
            $error = isset($xml->ResultDetail) ? (string) $xml->ResultDetail : '';
        } else if ($this->pv['bank_selected_arr']['virtual_pos_type'] == 3) {
            $response = $xml->Transaction->Response->Message == "Approved" ? 1 : 0;
            $error = isset($xml->Transaction->Response->ErrorMsg) ? (string) $xml->Transaction->Response->ErrorMsg : '';
        }
        $pay_date = date('Y-m-d H:i:s');
        /*
         * Veri tabanına kaydeder
         */
        $pay = new Pay();
        $pay->__create([
            'user_id' => session()->get('users')['authority'] === 'admin' ? 0 : session()->get('users')['id'],
            'seller_name' => $this->pv['sender']['gonderen_firma'],
            'order_number' => $this->pv['general']['numara_siparis'],
            'order_ip' => $this->pv['general']['address_ip'],
            'order_total' => $this->pv['pay']['amount'],
            'order_installment' => $this->pv['pay']['installment'],
            'pay_bank' => $this->pv['bank_selected'],
            'pay_json' => __json_encode($xml, true),
            'pay_date' => $pay_date,
            'pay_result' => $response == 1 ? 'success' : 'error',
            'pay_message' => $error,
            'pay_card_owner' => $this->pv['sender']['gonderen_isim'],
            'user_phone' => $this->pv['sender']['gonderen_telefon'],
            'user_email' => $this->pv['general']['address_email'],
        ]);
        /*
         * Mail gönderir
         */
        __mail_send(date_translate($pay_date, 2), $this->pv['pay']['amount'], ($response == 1 ? 'basarili' : 'basarisiz'), $this->pv['general']['address_email']);
        /*
         * Bildiirm gönderir
         */
        __notification_send(
            $response == 1 ? 'Başarılı' : 'Başarısız',
            $this->pv['pay']['amount'] . " TL",
            $response == 1 ? 1 : 0
        );
        return ["result" => $response, "message" => $error];
    }
}
