<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Profile start
     * @return View
     */
    public function start() : View
    {
        $this->startIllegal('public');
        $user = new User();
        return view('profile', [
            'data' => $user->__data(session()->get('users')['id'])
        ]);
    }
    /**
     * Profili günceller
     * @param Request $request
     * @return void
     */
    public function update(Request $request) {
        $profile = new User();
        $result = $profile->__update(session()->get('id'), [
            "user_name" => $request->input('name'),
            "user_email" => $request->input('email'),
            "user_phone" => $request->input('phone')
        ]);
        $this->result['result'] = $result;
        $this->result['message'] = $result ? 'Güncelleme Başarılı' : 'Güncelleme Başarısız';
        echo __json_encode($this->result);
    }
}
