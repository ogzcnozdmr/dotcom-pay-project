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
        $authority_area_data = $authority->__data($id);
        if (empty($authority_area_data)) {
            __redirect('home.danger');
        }
        return view('authority', [
            'id' => $id,
            'authority_get' => $authority_get,
            'authority_pages_get' => $authority_pages_get,
            'authority_area_get' => __json_decode($authority_area_data['authority_area'],true)
        ]);
    }

    /**
     * İşlem kısıtı ekleme
     * @param Request $request
     * @return void
     */
    public function set(Request $request) : void
    {
        $option = [];
        if (str_contains($request->input('option'), "&")) {
            $options = str_replace("input=",'', $request->input('option'));
            $option = explode("&", $options);
        } else if($request->input('option') !== '') {
            $option = [str_replace("input=",'', $request->input('option'))];
        }

        /*
         * Public yetki eklendi
         */
        $option[] = 1;

        $authority = new Authority();
        $update = $authority->__update($request->input('id'), ['authority_area' => __json_encode($option)]);

        if ($update) {
            $this->result['result'] = true;
            $this->result['message'] = 'Başarıyla güncellendi';
            $this->result['id'] = $request->input('id');
        }

        echo __json_encode($this->result);
    }
}
