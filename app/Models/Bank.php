<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends BaseModel
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
    protected $table = 'bank';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'bank_id';
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
