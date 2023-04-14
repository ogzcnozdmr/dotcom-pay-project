<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function start() : View
    {
        $this->startIllegal('public');
        return view('privacy');
    }
}
