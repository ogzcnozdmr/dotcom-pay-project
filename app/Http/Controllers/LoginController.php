<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginLoginRequest;
use App\Models\User;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Login sayfası
     * @return View
     */
    public function start() : View
    {
        return view('login');
    }

    /**
     * Kullanıcı giriş
     * @param LoginLoginRequest $request
     * @return void
     */
    public function login(LoginLoginRequest $request) : void
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $user = new User();
        $getUser = current($user->__data(null, 'user_id,user_password,user_authority,user_username', ['user_username' => $username]));
        /*
         * Kullanıcı yoksa
         */
        if (empty($getUser)) {
            $this->result['message'] = 'Kullanıcı bulunamadı';
        } else {
            if (password_verify(md5($password), $getUser['user_password'])) {
                $this->result['result'] = true;
                /*
                 * Sessionları ayarlar
                 */
                session()->put('users', [
                    'isLogged'  => TRUE,
                    'id'        => $getUser['user_id'],
                    'username'  => $username,
                    'authority' => $getUser['user_authority']
                ]);
                session()->save();
                if ($getUser['user_authority'] === 'seller') {
                    $this->result['location'] = route('news.start');
                } else {
                    $this->result['location'] = route('home.start');
                }
                $this->result['message'] = 'Giriş Başarılı';
            } else {
                $this->result['message'] = 'Kullanıcı adı/Şifre yanlış';
            }
        }
        echo __json_encode($this->result);
    }
}
