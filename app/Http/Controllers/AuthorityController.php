<?php

namespace App\Http\Controllers;

use App\Models\Authority;
use Illuminate\Http\Request;

class AuthorityController extends Controller
{

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
