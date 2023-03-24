<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginLoginRequest;
use App\Http\Requests\LoginRegisterRequest;
use Illuminate\View\View;
use Illuminate\Http\Request;

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
        $json = [
            "result"  => FALSE,
            "message" => "",
            "id"      => 0
        ];

        $email = $request->input('email');
        $password = $request->input('password');

        $getUser = User::__data($email, 'user_id,user_password,user_email');
        /*
         * Kullanıcı varsa
         */
        if (count($getUser) > 0) {
            /*
             * Şifre doğruysa
             */
            if (password_verify(md5($password), $getUser['user_password'])) {
                $json['id']       = $getUser['user_id'];
                $json['message']  = 'Giriş Başarılı';
                $json['result']   = TRUE;
                $json['history']  = $this->getHistory();

                /*
                 * sessionları ayarlar
                 */
                session()->put('users', [
                    'isLogged' => TRUE,
                    'id'   => $getUser['user_id'],
                    'mail' => $getUser['user_email']
                ]);
                session()->save();
                User::__update(null, ['user_last_login' => date('Y-m-d H:i:s')], ['user_id' => $getUser['user_id']]);
            } else {
                $json['message'] = "Kullanıcı adı/şifre yanlış";
            }
        } else {
            $json['message'] = "Kullanıcı bulunamadı";
        }
        echo __json_encode($json);
    }

    /**
     * Kullanıcı kayıt
     * @param LoginRegisterRequest $request
     * @return void
     */
    public function register(LoginRegisterRequest $request) : void
    {
        $json = [
            "result"  => FALSE,
            "message" => "",
            "id"      => 0
        ];

        $email = $request->input('email');
        $password = $request->input('password');
        $platform = $request->input('platform') ?: 'web';

        if ($platform === "web") {
            /*
             * email adresi yoksa
             */
            if (User::__count(['user_email' => $email]) === 0) {
                $insertId = User::__register($email, $password);
                if ($insertId) {
                    $json['id']       = $insertId;
                    $json['message']  = 'Kayıt başarılı';
                    $json['result']   = TRUE;
                    $json['history']  = $this->getHistory();

                    /*
                     * sessionları ayarlar
                     */
                    session()->put('users', [
                        'isLogged' => TRUE,
                        'id'   => $insertId,
                        'mail' => $email,
                        'type' => 'user',
                        'platform' => $platform
                    ]);
                    session()->save();
                } else {
                    $json['message'] = 'Kayıt başarısız';
                }
            } else {
                $json['message'] = 'Kayıtlı email adresi';
            }
        } else if($platform === "google" || $platform === "facebook") {
            /*
             * Kullanıcı bulunmadıysa
             */
            if (User::__count([
                    'user_email' => $email,
                    'user_platform' => $platform,
                    'user_platform_id' => $request->input('platformId')
                ]) === 0) {
                $result = User::__register(
                    $email,
                    $password,
                    $platform,
                    [
                        'names' => $request->input('name'),
                        'image' => $request->input('image'),
                        'platform_id' => $request->input('platformId')
                    ]
                );
                /*
                 * Kayıt başarılıysa
                 */
                if ($result) {
                    //TODO::lastid kontrol edilecek
                    $insertId = DB::getPdo()->lastInsertId();

                    $json['id']       = $insertId;
                    $json['message']  = 'Kayıt başarılı';
                    $json['result']   = TRUE;
                    $json['history']  = $this->getHistory();

                    /*
                     * sessionları ayarlar
                     */
                    session()->put('users', [
                        'isLogged' => TRUE,
                        'id'   => $insertId,
                        'mail' => $email,
                        'type' => 'user',
                        'platform' => $platform
                    ]);
                    session()->save();
                } else {
                    $json['message'] = 'Kayıt başarısız';
                }
            } else {
                /*
                 * giriş başarılı
                 */
                $userData = User::__data($email, 'user_id');
                /*
                 * kullanıcı verisi var
                 */
                if (count($userData) > 0) {
                    $json['id']       = $userData['user_id'];
                    $json['message']  = 'Giriş başarılı';
                    $json['result']   = TRUE;
                    $json['history']  = $this->getHistory();

                    $isSeller = Seller::__control($userData['user_id']) > 0;
                    /*
                     * sessionları ayarlar
                     */
                    session()->put('users', [
                        'isLogged' => TRUE,
                        'id'   => $userData['user_id'],
                        'mail' => $email,
                        'type' => $isSeller ? 'seller' : 'user',
                        'platform' => $platform
                    ]);
                    session()->save();
                } else {
                    /*
                     * giriş başarısız
                     */
                    $json['message'] = "Giriş başarısız";
                }
            }
        }
        echo __json_encode($json);
    }
}
