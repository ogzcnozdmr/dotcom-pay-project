<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class User extends BaseModel
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
    protected $table = 'user';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'user_id';
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

    /**
     * Kayıtlı firma sayısıfı verir
     * @return int
     */
    public static function __registered_company_count() : int
    {
        return self::where([
            'user_visible' => '1'
        ])
        ->where('user_authority', '!=', 'admin')
        ->count();
    }
}
