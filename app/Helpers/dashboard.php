<?php

function month_next($yil, $ay){
    $toplam_ay = 12;

    $ay += 1;
    if($ay > 12) {
        $ay = $ay%$toplam_ay;
        $yil++;
    }
    return "$yil-$ay-1 0:0:0";
}