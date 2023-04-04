<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends BaseModel
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
    protected $table = 'installment';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'installment_id';
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
     * Kullanılabilir taksitleri getirir
     * @return array
     */
    public function __data_available(string $areas = '*') {
        $builder = self::where(['installment_visible' => '1'])->where('installment_number' , '>', '1');
        return builder_return_data($builder, null, $areas);
    }
}
