<?php

namespace App\Http\Controllers;

use App\Models\Authority;
use App\Models\AuthorityPages;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorityController extends Controller
{
    /**
     * İşlem kısıtı sayfası
     * @param int $id
     * @return View
     */
    public function start(int $id = 2) : View
    {
        $this->startIllegal('transaction-constraint');
        if (session()->get('users')['authority'] !== 'admin') {
            __redirect('home.danger');
        }
        $authority = new Authority();
        $authority_pages = new AuthorityPages();
        $authority_get = $authority->__data_seller();
        $authority_pages_get = $authority_pages->__data_authority();
        $authority_area_get = $authority->__data($id);
        if (empty($authority_area_get)) {
            __redirect('home.danger');
        }
        $yetki_alan_islem_json = __json_decode($authority_area_get['authority_area'],true);
        return view('authority', [
            'id' => $id,
            'authority_get' => $authority_get,
            'authority_pages_get' => $authority_pages_get,
            'authority_area_get' => $authority_area_get
        ]);
    }

    /**
     * İşlem kısıtı ekleme
     * @param $request
     * @return void
     */
    public function transactionConstraint($request) {
        $option = [];
        if (strstr($request->input('option'),"&")) {
            $options = str_replace("input=",'', $request->input('option'));
            $option = explode("&",$options);
        }else if($request->input('option') !== ''){
            $option = (array) str_replace("input=",'', $request->input('option'));
        }

        /*
         * Public yetki eklendi
         */
        $option[] = 1;

        $authority = new Authority();
        $authority->__update($request->input('id'), ['authority_area' => __json_encode($option)]);

        echo __json_encode($option);
    }
}
