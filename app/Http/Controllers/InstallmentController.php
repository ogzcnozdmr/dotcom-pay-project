<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstallmentController extends Controller
{
    /**
     * İşlem kısıtı sayfası
     * @param int $id
     * @return View
     */
    public function start(int $id = 1) : View
    {
        $this->startIllegal('bank-settings');
        if (session()->get('users')['authority'] !== 'admin') {
            __redirect('home.danger');
        }
        $bank = new Bank(false);
        $installment = new Installment();
        $bank_all_get = $bank->__data(null);
        $selected_bank = $bank->__data($id);
        if (empty($selected_bank)) {
            __redirect('home.danger');
        }
        $installment_get = $installment->__data(null);
        return view('installment', [
            'id' => $id,
            'bank_json' => __json_decode($selected_bank['bank_json'],true),
            'selected_bank' => $selected_bank,
            'installment_get' => $installment_get,
            'bank_all_get' => $bank_all_get
        ]);
    }
    /**
     * Installment set
     * @param Request $request
     * @return void
     */
    public function set(Request $request) : void
    {
        $bank = new Bank();
        $update = $bank->__update($request->input('id'), [
            'plus_installment' => $request->input('installment')
        ]);
        if ($update) {
            $this->result['result'] = true;
            $this->result['message'] = 'Başarıyla güncellendi';
            $this->result['id'] = $request->input('id');
        }
        echo __json_encode($this->result);
    }
    /**
     * Bank plus installment
     * @param Request $request
     * @return void
     */
    public function get(Request $request) : void
    {
        $bank = new Bank();
        $get = current($bank->__data(null, 'max_installment,min_installment_amount', [
            'bank_variable' => $request->input('bank')
        ]));
        $array = [
            "result" => !empty($get),
            "max"    => $get['max_installment'],
            "min"    => $get['min_installment_amount']
        ];
        echo __json_encode($array);
    }
}
