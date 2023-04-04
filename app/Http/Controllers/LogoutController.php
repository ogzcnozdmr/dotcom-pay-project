<?php

namespace App\Http\Controllers;

class LogoutController extends Controller
{
    /*
     * Çıkış sayfası
     */
    public function start()
    {
        /*
         * sessionu siler
         */
        session()->forget('users');
        /*
         * flash data oluşturur
         */
        session()->flash('message', 'Başarıyla çıkış yaptınız.');
        /*
         * girişe yönlendirir
         */
        return redirect()->route('login.start');
    }
}
