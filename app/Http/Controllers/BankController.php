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
    public function start(Request $request, int $id = 1) : View
    {
        $this->startIllegal('bank-settings');
        if (session()->get('users')['authority'] !== 'admin') {
            __redirect('home.danger');
        }
        $bank = new Bank(false);
        $bank_info = $bank->__data(null);
        $bank_detail = $bank->__data($id);
        if (empty($bank_detail)) {
            __redirect('home.danger');
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

    /**
     * Banka ayarlarını işler
     * @param Request $request
     * @return void
     */
    public function settings(Request $request) : void
    {
        $bank = new Bank();
        $update = $bank->__update($request->input('id'), [
            "bank_visible" => $request->input('option') ? '1' : '0',
            "bank_json" =>__json_encode([
                "name"          => $request->input('name'),
                "password"      => $request->input('password'),
                "client"     => $request->input('client'),
                "storekey"     => $request->input('storekey'),
                "storetype"  => $request->input('storetype'),
                "storetype3d"  => $request->input('storetype3d') ?? ''
            ]),
            "max_installment"        => $request->input('max_installment'),
            "min_installment_amount" => $request->input('min_installment_amount')
        ]);
        if ($update) {
            $this->result['result'] = true;
            $this->result['message'] = 'Başarıyla güncellendi';
            $this->result['id'] = $request->input('id');
        }
        echo __json_encode($this->result);
    }
}
