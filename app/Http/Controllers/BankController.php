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
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function start(Request $request, int $id = 1): View
    {
        $this->startIllegal('bank-settings');
        if (session()->get('users')['authority'] !== "admin"){
            __redirect('danger');
        }
        $bank = new Bank();
        $bank_info = $bank->__data(null);
        $bank_detail = $bank->__data($id);
        if (empty($bank_detail)) {
            __redirect('danger');
        }
        $bank_detail_api = __json_decode($bank_detail['bank_json'], true);
        $installment = new Installment();
        $installment_data = $installment->__data_available('installment_number');
        return view('bank', [
            'select' => $id,
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
}
