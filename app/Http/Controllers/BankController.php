<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankController extends Controller
{
    /**
     * Bank start
     * @return View
     */
    public function start(Request $request): View
    {
        $this->startIllegal('bank-settings');
        if (session()->get('users')['authority'] !== "admin"){
            __redirect('danger');
        }
        $bank = new Bank();
        $bank_info = $bank->__data(null);
        $bank_detail = $bank->__data($request->input('select') !== null ? $request->input('select') : 1);
        if (empty($bank_detail)) {
            __redirect('danger');
        }
        $bank_detail_api = __json_decode($bank_detail['bank_json'], true);
        $installment = new Installment();
        $installment_data = $installment->__data_available('installment_number');
        return view('bank', [
            'select' => $request->input('select') ?? -1,
            'bank_info' => $bank_info,
            'bank_detail' => $bank_detail,
            'bank_detail_api' => $bank_detail_api,
            'installment_data' => $installment_data
        ]);
    }

    public function settings(Request $request) {
        $bank = new Bank();
        $update = $bank->__update($request->input('id'), [
            "bank_visible" => $request->input('visible'),
            "bank_json" =>__json_encode([
                "name"          => $request->input('name'),
                "password"      => $request->input('password'),
                "client_id"     => $request->input('client_id'),
                "user_prov_id"  => $request->input('user_prov_id')
            ]),
            "max_installment"        => $request->input('max_installment'),
            "min_installment_amount" => $request->input('min_installment_amount')
        ]);
        echo $update ? '1' : '0';
    }
    /**
     * Bank plus installment
     * @param Request $request
     * @return void
     */
    public function plusInstallment(Request $request) {
        $bank = new Bank();
        $update = $bank->__update($request->input('id'), [
            'plus_installment' => $request->input('installment')
        ]);
        echo $update ? '1' : '0';
    }
    /**
     * Bank plus installment
     * @param Request $request
     * @return void
     */
    public function getInstallment(Request $request) {
        $bank = new Bank();
        $get = current($bank->__data(null, 'max_installment,min_installment_amount', [
            'bank_variable' => $request->input('bank')
        ]));
        $array = [
            "result" => !empty($get) ? '1' : '0',
            "max"    => $get['max_installment'],
            "min"    => $get['min_installment_amount']
        ];
        echo __json_encode($array);
    }
}
