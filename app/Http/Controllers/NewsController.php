<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Haber listesi
     * @return void
     */
    public function postList() {
        $result = [];
        $row = 0;

        $news = new News();
        $datas = $news->__data(null, 'news_id,news_title,news_seo,news_date', ['news_visible' => '1'], ['news_id' => 'desc']);

        foreach ($datas as $data) {
            $result[] = [
                (++$row),
                '<a href=/news/'.$data['news_seo'].' target="_blank">'.$data['news_title'].'</a>',
                date_translate($data['news_date'],2),
                '<button st="'.$data["news_id"].'" class="btn btn-danger btn-xs sil"> Sil</button>',
                '<a href="news/update/'.$data["news_id"].'"><button class="btn btn-warning btn-xs"> Güncelle</button></a>'
            ];
        }
        echo __json_encode($result);
    }

    /**
     * Haber ekler
     * @return void
     */
    public function postAdd() {
        $photo = file_insert($_FILES['file'],"/assets/images/haber/main","half");
        if ($photo != "0") {
            $news = new News();
            $seo = sanitize($_POST['title']);
            $insert = $news->__create([
                "news_title"   => $_POST['title'],
                "news_seo"     => $seo,
                "news_content" => $_POST['content'],
                "news_photo"   => $photo[2],
            ]);

            if ($insert) {
                $this->result['result'] = true;
                $this->result['message'] = "Haber Başarıyla Eklendi";
                $this->result['id'] = current($news->__data(null, 'news_id',['news_seo' => $seo]))['news_id'] ?? 0;
            }
        } else {
            $this->result['message'] = "Resimde Hata / Yanlış Resim";
        }
        echo __json_encode($this->result);
    }

    /**
     * Haber günceller
     * @param Request $request
     * @return void
     */
    public function postUpdate(Request $request) {
        $this->result['id'] = $request->input('id');
        $seo = sanitize($request->input('title'));
        $news = new News();
        if (isset($_FILES['file'])) {
            $photo = file_insert($_FILES['file'],"/assets/images/haber/main","half");
            if ($photo != "0") {
                $update = $news->__update($this->result['id'], [
                    "news_title"   => $request->input('title'),
                    "news_seo"     => $seo,
                    "news_content" => $request->input('content'),
                    "news_photo"   => $photo[2],
                ]);
            }else{
                $this->result['message'] = "Resimde Hata / Yanlış Resim";
            }
        } else {
            $update = $news->__update($this->result['id'], [
                "news_title"   => $request->input('title'),
                "news_seo"     => $seo,
                "news_content" => $request->input('content')
            ]);
        }

        if ($update) {
            $this->result['result'] = true;
            $this->result['message'] = "Haber Başarıyla Güncellendi";
            $this->result['image'] = $photo[2] ?? '';
        }
        echo __json_encode($this->result);
    }

    /**
     * Haber siler
     * @param Request $request
     * @return void
     */
    public function postRemove(Request $request) {
        $this->result['id'] = $request->input('id');

        $news = new News();
        $update = $news->__update($this->result['id'], ['news_visible' => '0']);

        if ($update) {
            $this->result['result'] = true;
            $this->result['message'] = 'Haber başarıyla silindi';
        }

        echo __json_encode($this->result);
    }

    /**
     * Resim ekler
     * @param Request $request
     * @return void
     */
    public function addImages(Request $request) {
        $resim =  file_insert($_FILES['file'],"/assets/images/news/detail","full");
        echo $resim[2];
    }
}
