<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Authority extends BaseModel
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
    protected $table = 'authority';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'authority_id';
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
     * Yönetici hariç yetkileri getirir
     * @return array
     */
    public function __data_seller() : array
    {
        $builder = self::where('authority_name', '!=', 'admin');
        return builder_return_data($builder);
    }
}
