<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Installment;
use App\Models\Pay;
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
     * TODO::YAPILACAK
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
    public function postList() : void {
        $result =[];
        $row = 0;

        $pay = new Pay();
        $lists = $pay->payList(session()->get('users')['authority'], session()->get('users')['id']);
        foreach ($lists as $get) {
            $taksit = $get['order_installment'] !== 0 ? $get['order_installment'] : 'PEŞİN';
            $sonuc = $get['pay_result'] === '1' ? 'BAŞARILI' : 'BAŞARISIZ';
            $link = $get['user_id'] == '0' ? $get['seller_name'] : '<a href="seller/pay/'.$get['user_id'].'">'.$get['seller_name'].'<a/>';

            $result[] = [
                (++$row),
                $link,
                $get['pay_card_owner'],
                $get['order_total'],
                $taksit,
                $get['bank_name'],
                date_translate($get['pay_date'],2),
                $sonuc
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
}
