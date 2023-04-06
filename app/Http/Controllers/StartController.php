<?php

namespace App\Http\Controllers;

use App\Models\Pay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StartController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->startIllegal('homepage');
    }

    /**
     * Start function
     * @return View
     */
    public function start () : View
    {
        $user = new User();
        $pay = new Pay();
        $registered_company_count = $user->__registered_company_count();
        $successful_payment_total_and_count = $pay->__successful_payment_total_and_count();
        $payment_request_count = $pay->__payment_request_count();
        return view('start', [
            'registered_company' => $registered_company_count,
            'total_sales' => round($successful_payment_total_and_count['total'] ?: 0, 2),
            'payment_request' => $payment_request_count,
            'successful_payment' => $successful_payment_total_and_count['success'] === 0 ? 0 : ceil($successful_payment_total_and_count['success'] * 100 / $payment_request_count)
        ]);
    }
    /**
     * Danger function
     * @return View
     */
    public function danger () : View
    {
        return view('danger');
    }
}
