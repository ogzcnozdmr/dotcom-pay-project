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
            "v1" => $request->input('name'),
            "v2" => $request->input('email'),
            "v3" => $request->input('phone')
        ]);
        echo $result ? '1' : '0';
    }
}
