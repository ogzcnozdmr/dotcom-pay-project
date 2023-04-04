<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Redirect route
 * @param string $routeName
 * @param string $extraValue
 * @return void
 */
#[NoReturn] function __redirect(string $routeName, string $extraValue = '') : void
{
    header("Location: ".route($routeName).$extraValue);
    die();
}
/**
 * Project json encode
 * @param object|array|null $json
 * @return string
 */
function __json_encode(object|array|null $json) : string
{
    return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?: '';
}
/**
 * Project json decode
 * @param string|null $json
 * @param bool $type
 * @return object
 */
function __json_decode(string|null $json, bool $type = false) : array | object
{
    return json_decode($json, $type) ?: ($type ? [] : new \stdClass());
}

function file_insert($file,$path,$type){//files , full-half
    if($type == "full"){
        $record_place=get_full_url().$path."/";
    }else if($type == "half"){
        $record_place=$path."/";
    }else{
        return "0";
        exit;
    }

    //$record_place="/assets/images/products/detail/";//"http://".$_SERVER['SERVER_NAME'].
    $record="../..".$path."/";

    $allowed = array('png','jpg','gif','jpeg');
    if(isset($file) && $file['error'] == 0){
        $file_name = file_name($file['name']);
        $extension = pathinfo($file['name'],PATHINFO_EXTENSION);
        if(!in_array(strtolower($extension),$allowed)){
            return "0";
            exit;
        }
        if(move_uploaded_file( $file['tmp_name'],$record.$file_name)){
            return array($file['name'],$file_name,($record_place.$file_name));// echo absolute file_url
            exit;
        }
    }
    return "0";
    exit;
}

function image_insert($title,$file){//base 64 codes
    $kayit_y = "images/haber/resim/";
    $kayit_yeri="../../".$kayit_y;
    list($type, $data) = explode(';', $file);
    list(, $data) = explode(',', $data);
    $file_data = base64_decode($data);
    $finfo = finfo_open();
    $file_mime_type = finfo_buffer($finfo, $file_data, FILEINFO_MIME_TYPE);
    if($file_mime_type == 'image/jpeg' || $file_mime_type == 'image/jpg')
        $file_type = 'jpeg';
    else if($file_mime_type == 'image/png')
        $file_type = 'png';
    else if($file_mime_type == 'image/gif')
        $file_type = 'gif';
    else if($file_mime_type == 'image/svg+xml' || $file_mime_type == 'text/plain')
        $file_type = 'svg';
    else
        $file_type = 'other';

    if(in_array($file_type, ['jpeg', 'png' , 'gif' , 'svg'])) {
        file_put_contents($kayit_yeri.sanitize($title), $file_data);
        return $kayit_y.sanitize($title).".".$file_type;
    }else{
        return "0";
    }
}

function file_name($filename){
    $explode_file=sanitize($filename);
    $explode=explode(".",$explode_file);
    $explode[count($explode)-2]=$explode[count($explode)-2]."_".rand(0,99999);
    $implode=implode(".",$explode);
    return $implode;
}

function get_full_url(){
    if(isset($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] === 'on')
        $link = "https";
    else
        $link = "http";
    $link .= "://";
    $link .= $_SERVER['HTTP_HOST'];
    //$link .= $_SERVER['REQUEST_URI'];//dosya uzantısını da getirir
    return $link;
}
function bildirim_gonder($baslik,$icerik,$sonuc){
    global $db;
    $insert = $db->prepare("INSERT INTO `bildirim` (bildirim_baslik,bildirim_icerik,bildirim_icon,bildirim_sonuc,bildirim_tarih)values(:v1,:v2,:v3,:v4,:v5)");
    $insert->execute(array(
        "v1"=>$baslik,
        "v2"=>$icerik,
        "v3"=>"mdi mdi-message-text-outline",
        "v4"=>$sonuc,
        "v5"=>date("Y-m-d H:i:s")
    ));
}

function mail_gonder($tarih,$tutar,$sonuc,$eposta){
    include '../library/phpmailer/class.phpmailer.php';

    //if($sonuc==1) $konu = "başarı ile sonuçlandırıldı, bizi seçtiğiniz için teşekkürler...";
    //else $konu = "başarılı olamadı lütfen tekrar deneyiniz..";
    if($sonuc=="basarili") $konu = "İşlem başarılı.";
    else $konu = "Başarısız işlem!";
    //VGCOahgAbc
    //info@eraslan.com.tr
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'ni-maria.guzelhosting.com';//rd-sansa.guzelhosting.com
    $mail->Port = "25";
    $mail->SMTPSecure = 'tls';
    $mail->Username = 'odeme@eraslan.com.tr';//info@eraslan.com.tr
    //$mail->From = $mail->Username;
    $mail->Password = 'Noktacom*123';//VGCOahgAbc
    $mail->SetFrom($mail->Username, 'Ödeme | Eraslanlar');
    //$mail->AddAddress($eposta);

    $mail->AddBCC($eposta);
    $mail->AddBCC("muhasebe@eraslan.com.tr");
    $mail->AddBCC("bilgi@noktacommedya.com");

    $mail->CharSet = 'UTF-8';

    $header = "Eraslanlar ödeme işlemi";

    //$content		 =	"<b>Eraslanlar ödeme işlemi : </b> $tarih tarihinde yapılan $tutar tutarındaki ödeme işlemi $konu <hr />";
    //$content		.=	"<span style='font-size:10px;color:#bbbbbb;'>Bu mesaj ". date('H:i:s d.m.Y') ." tarihinde gönderildi.</span>";

    $content = "<b>Eraslanlar ödeme işlemi </b><hr>";
    $content .="<b>Tutar : </b>".$tutar." TL<br>";
    $content .="<b>Tarih : </b>".$tarih."<br>";
    $content .="<b>Sonuç : </b>".$konu."<br>";
    $content .=	"<span style='font-size:10px;color:#bbbbbb;'>Bu mesaj ". date('Y-m-d H:i:s') ." tarihinde gönderildi.</span>";

    $mail->FromName = "Ödeme | Eraslanlar";
    $mail->Subject  = "Eraslanlar ödeme işlemi";
    $mail->Body = $content;
    $mail->AltBody = $content;

    //$content = '<div style="background: #eee; padding: 10px; font-size: 14px">Bu bir test e-posta\'dır..</div>';
    //$mail->MsgHTML($content);
    /*if($mail->Send()){
        //e-posta başarılı ile gönderildi
        echo "basarili";
    }else{
        //bir sorun var, sorunu ekrana bastıralım
        echo $mail->ErrorInfo;
    }*/
    @$mail->Send();
}

function date_translate($date, $type = 0){
    $date_explode = explode(" ",$date);
    $year_explode = explode("-",$date_explode[0]);
    $hour_explode = explode(":",$date_explode[1]);
    switch($year_explode[1]){
        case '01':
            $month = "Ocak";
            break;
        case '02':
            $month = "Şubat";
            break;
        case '03':
            $month = "Mart";
            break;
        case '04':
            $month = "Nisan";
            break;
        case '05':
            $month = "Mayıs";
            break;
        case '06':
            $month = "Haziran";
            break;
        case '07':
            $month = "Temmuz";
            break;
        case '08':
            $month = "Ağustos";
            break;
        case '09':
            $month = "Eylül";
            break;
        case '10':
            $month = "Ekim";
            break;
        case '11':
            $month = "Kasım";
            break;
        case '12':
            $month = "Aralık";
            break;
        default:
            $month = "";
    }
    $year_explode[2] = ltrim($year_explode[2],"0");
    if($type==0){
        return $year_explode[2]." ".$month; //12 aralık
    }else if($type==1){
        return $year_explode[2]." ".$month." ".$hour_explode[0].":".$hour_explode[1]; //12 aralık 13:20
    }else if($type==2){
        return $year_explode[2]." ".$month." ".$year_explode[0]." ".$hour_explode[0].":".$hour_explode[1];//12 aralık 2019 13:20
    }else if($type==3){
        return $year_explode[2]." ".$month." ".$year_explode[0]; //13 aralık 2019
    }
}

function sanitize($s){
    $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
    $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
    $s = strtolower(str_replace($find, $replace, $s));
    $s = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $s);
    $s = trim(preg_replace('/\s+/', ' ', $s));
    $s = str_replace(' ', '-', $s);

    $tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',':',',');
    $eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','');
    $s = str_replace($tr,$eng,$s);
    $s = strtolower($s);
    $s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
    $s = preg_replace('/\s+/', '-', $s);
    $s = preg_replace('|-+|', '-', $s);
    $s = preg_replace('/#/', '', $s);
    //$s = str_replace('.', '', $s);
    $s = trim($s, '-');
    $s = trim($s, ' ');
    return $s;
}

/**
 * Ekrana yazdırır ve durdurur
 * @param mixed $pire
 * @param bool $die
 * @return void
 */
function preprint(mixed $pire, bool $die = true) : void
{
    echo "<pre>";
    print_r($pire);
    echo "<pre>";
    if ($die) {
        die();
    }
}
?>