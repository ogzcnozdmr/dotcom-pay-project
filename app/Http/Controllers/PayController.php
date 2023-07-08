<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Installment;
use App\Models\Pay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayController extends Controller
{
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
    public function screen($orderCode = '') : View
    {
        $this->startIllegal('public');

        //TODO::YAPILACAK sipariş kodu boş değilse kontroller yapılıp o sipariş üzerinden gidilecekif ($orderCode !== '') {}

        $installment = new Installment(false);
        $bank = new Bank();
        $user = new User();

        return view('pay-screen', [
            'installment' => $installment->__data_available('installment_number'),
            'bank' => $bank->__data(null, 'bank_name,bank_variable,plus_installment,bank_photo,max_installment,min_installment_amount'),
            'user' => session()->get('users')['authority'] !== 'admin' ? $user->__data(session()->get('users')['id']) : []
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
            $link = $get['is_user'] == '0' ? $get['seller_name'] : '<a href="'.route('seller.pay', ['id' => $get['user_id']]).'">'.$get['seller_name'].'<a/>';

            $result[] = [
                (++$row),
                $link,
                $get['pay_card_owner'],
                $get['order_total'],
                $taksit,
                $get['bank_name'],
                date_translate($get['pay_date'],2),
                __pay_result_titles($get['pay_result']),
                '<button data-id="'.$get['pay_id'].'"class="btn btn-info btn-xs pay-detail" >Ödeme Detay</button>'
            ];
        }
        echo __json_encode($result);
    }
    /**
     * Ödeme Detayını getirir
     * @return void
     */
    public function postDetail(Request $request) : void
    {
        $data = [];
        $pay = new Pay();
        $detail = $pay->payDetail($request->input('id'));
        if (!empty($detail)) {
            $data = array_merge($data, [
                'order_number' => $detail['order_number'],
                'pay_date' => date_translate($detail['pay_date'], 2),
                'bank_name' => $detail['bank_name'],
                'pay_card_owner' => $detail['pay_card_owner'],
                'seller_name' => $detail['seller_name'],
                'user_email' => $detail['user_email'],
                'user_phone' => $detail['user_phone'],
                'pay_result' => __pay_result_titles($detail['pay_result']),
                'pay_message' => empty($detail['pay_message']) && $detail['pay_result'] === 'success' ? 'Ödeme başarıyla tamamlandı' : $detail['pay_message']
            ]);
        }
        echo __json_encode($data);
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
}
