<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends BaseModel
{
    /*
     * Constructor and settings
     */
    public function __construct(bool $autoVisible = false)
    {
        $this->autoVisible = $autoVisible;
    }
    use HasFactory;
    protected $guarded = [];
    /**
     * Tablo adı
     * @var string
     */
    protected $table = 'notifications';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'notifications_id';
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
