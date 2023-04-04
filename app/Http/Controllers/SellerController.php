<?php

namespace App\Http\Controllers;

use App\Models\Authority;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerController extends Controller
{
    /**
     * Bayi listeleme sayfası
     * @return View
     */
    public function start() : View
    {
        $this->startIllegal('seller-list');
        $authority = new Authority();
        return view('seller-list', [
            'authority_all' => $authority->__data_seller()
        ]);
    }
    /**
     * Bayi listeleme sayfası
     * @return View
     */
    public function add() : View
    {
        $this->startIllegal('seller-add');
        $authority = new Authority();
        return view('seller-add', [
            'authority_all' => $authority->__data_seller()
        ]);
    }
    /**
     * Bayi listeleme sayfası
     * @return View
     */
    public function update() : View
    {
        return view('seller-update');
    }
    /**
     * Bayi oluşturur
     * @param $request
     * @return void
     */
    public function postAdd($request) : void
    {
        $user = new User(['autoVisible' => false]);
        $getUser = current($user->__data(null, 'user_username', ['user_username' => $request->input('username')]));
        if (!empty($getUser)) {
            $result = $user->__create([
                "user_name" => $request->input('name'),
                "user_username" => $request->input('username'),
                "user_email" => $request->input('email'),
                "user_phone" => $request->input('phone'),
                "user_password" => password_hash(md5($request->input('password')), PASSWORD_BCRYPT, ["cost" => 12]),
                "user_authority" => $request->input('authority'),
                "official_distributor" => $request->input('official_distributor')
            ]);
            $this->result['result'] = $result;
            $this->result['message'] = $result ? 'Kayıt Başarılı' : 'Kayıt Başarısız';
        } else {
            $this->result['message'] = 'Kullanıcı Adı Önceden Alımış';
        }
        echo __json_encode($this->result);
    }
    /**
     * Bayi günceller
     * @param $request
     * @return void
     */
    public function postUpdate($request) : void {
        $user = new User();
        $array = [
            "user_name" => $request->input('name'),
            "user_email" => $request->input('email'),
            "user_phone" => $request->input('phone'),
            "user_authority" => $request->input('authority'),
            "official_distributor" => $request->input('official_distributor')
        ];
        if ($request->input('password') !== null) {
            $array['user_password'] = password_hash(md5($request->input('password')), PASSWORD_BCRYPT, ["cost" => 12]);
        }
        $update = $user->__update($request->input('id'), $array);
        $this->result['result'] = $update ? 1 : 0;
        $this->result['message'] = $update ? 'Güncelleme Başarılı' : 'Güncelleme Başarısız';
        $this->result['id'] = $update ? $request->input('id') : 0;
        echo __json_encode($this->result);
    }
    /**
     * Bayileri listeler
     * @return void
     */
    public function postList() {
        $result = [];
        $row = 0;

        $user = new User();
        $datas = $user->__data(null, '*', [], ['user_id' => 'desc']);

        foreach ($datas as $data) {
            if ($data['user_authority'] === 'admin') {
                continue;
            }
            $result[] = [
                (++$row),
                $data['user_name'],
                $data['user_email'],
                strtoupper($data['user_authority']),
                '<button bayi_id="'.$data['user_id'].'" class="btn btn-danger btn-xs bayi-sil-button"> Sil</button>',
                '<a href="/seller/update/'.$data['user_id'].'"><button class="btn btn-warning btn-xs" >Güncelle</button></a>',
                '<a href="/seller/pay/'.$data['user_id'].'"><button class="btn btn-info btn-xs" >Ödeme Bilgi</button></a>'
            ];
        }
        echo __json_encode($result);
    }

    /**
     * Bayiyi siler
     * @param $request
     * @return void
     */
    public function postRemove ($request) {
        $user = new User();
        $remove = $user->__update($request->input('id'), ['user_visible' => '0']);
        if ($remove) {
            $this->result['result'] = '1';
            $this->result['message'] = 'Başarıyla silindi';
        }
        echo __json_encode($this->result);
    }
}
