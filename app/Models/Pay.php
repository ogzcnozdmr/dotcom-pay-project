<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Pay extends BaseModel
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Tablo adı
     * @var string
     */
    protected $table = 'pay';
    /**
     * Ayarlar tablosu birincil anahtarı
     * @var string
     */
    protected $primaryKey = 'pay_id';
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

    public static function payList($authority, $id) {
        $where = [
            'pay_visible' => '1'
        ];
        if ($authority !== 'admin') {
            $where['user_id'] = $id;
        }
        $builder = DB::table('pay')
            ->join('bank', 'bank.bank_variable', '=', 'pay.pay_bank')
            ->where($where)
            ->orderBy('pay_id', 'desc');
        return builder_return_data($builder, null, 'user_id,seller_name,pay_card_owner,order_total,pay_date,order_installment,pay_result,bank_name');
    }

    public static function __pay_success ($date1, $date2) {
        $builder = DB::table('pay')
            ->where([
                'pay_result' => '1',
                'pay_visible' => '1'
            ])
            ->where('pay_date', '>=', $date1)
            ->where('pay_date', '<=', $date2);
        return builder_return_data($builder, 1, 'sum(order_total) as total,count(*) as success');
    }

    public static function __pay_total_count ($date1, $date2) : int {
        return self::where([
                'pay_visible' => '1'
            ])
            ->where('pay_date', '>=', $date1)
            ->where('pay_date', '<=', $date2)
            ->count();
    }

    /**
     * Başarılı ödeme toplamını ve sayısını verir
     * @return mixed
     */
    public static function __successful_payment_total_and_count() : mixed
    {
        $builder = DB::table('pay')
            ->where([
                'pay_visible' => '1'
            ]);
        return builder_return_data($builder, 1, 'sum(order_total) as total,count(*) as success');
    }

    /**
     * Ödeme isteği sayısını verir
     * @return int
     */
    public static function __payment_request_count() : int
    {
        return self::where([
                'pay_visible' => '1'
            ])
            ->count();
    }
}
