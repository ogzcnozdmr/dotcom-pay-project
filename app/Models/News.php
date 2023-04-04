<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends BaseModel
{
    /*
     * Constructor and settings
     */
    public function __construct(bool $autoVisible = true)
    {
        $this->autoVisible = $autoVisible;
    }
    use HasFactory;
    protected $guarded = [];
    /**
     * Tablo adı
     * @var string
     */
    protected $table = 'news';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'news_id';
    /**
     * Birincil anahtarın otomatik artıp artmayacağı
     * @var bool
     */
    public $incrementing = false;
    /**
     * Created_at / Updated_at değerlerinin gelip gelmeyeceği
     * @var bool
     */
    public $timestamps = false;
}
