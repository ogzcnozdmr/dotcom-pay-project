<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuthorityPages extends BaseModel
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
    protected $table = 'authority_pages';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'authority_pages_id';
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
    public function __data_authority() : array
    {
        $builder = self::where('authority_pages_id', '!=', 1);
        return builder_return_data($builder);
    }
}
