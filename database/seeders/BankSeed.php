<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'bank_id' => 1,
                'bank_name' => 'Axess Kart',
                'bank_variable' => 'ak_bank',
                'api_url' => 'https://www.sanalakpos.com/fim/api',
                'bank_json' => json_encode([
                    'name' => '',
                    'password' => '',
                    'client_id' => '',
                    'user_prov_id' => '',
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'virtual_pos_type' => 1,
                'bank_photo' => 'assets/images/banka/ak.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 2,
                'bank_name' => 'Maximum Kart',
                'bank_variable' => 'is_bank',
                'api_url' => 'https://sanalpos.isbank.com.tr/fim/api',
                'bank_json' => json_encode([
                    'name' => '',
                    'password' => '',
                    'client_id' => '',
                    'user_prov_id' => '',
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'virtual_pos_type' => 1,
                'bank_photo' => 'assets/images/banka/is.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 3,
                'bank_name' => 'Paraf Kart',
                'bank_variable' => 'halk_bank',
                'api_url' => 'https://sanalpos.halkbank.com.tr/servlet/cc5ApiServer',
                'bank_json' => json_encode([
                    'name' => '',
                    'password' => '',
                    'client_id' => '',
                    'user_prov_id' => '',
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'virtual_pos_type' => 1,
                'bank_photo' => 'assets/images/banka/halk.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 4,
                'bank_name' => 'Card Finans',
                'bank_variable' => 'finans_bank',
                'api_url' => 'https://www.fbwebpos.com/servlet/cc5ApiServer',
                'bank_json' => json_encode([
                    'name' => '',
                    'password' => '',
                    'client_id' => '',
                    'user_prov_id' => '',
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'virtual_pos_type' => 1,
                'bank_photo' => 'assets/images/banka/finans.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 5,
                'bank_name' => 'World Kart',
                'bank_variable' => 'vakif_bank',
                'api_url' => 'https://onlineodeme.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx',
                'bank_json' => json_encode([
                    'name' => '',
                    'password' => '',
                    'client_id' => '',
                    'user_prov_id' => '',
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'virtual_pos_type' => 1,
                'bank_photo' => 'assets/images/banka/vakif.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 6,
                'bank_name' => 'Bonus Kart',
                'bank_variable' => 'garanti_bank',
                'api_url' => 'https://sanalposprov.garanti.com.tr/VPServlet',
                'bank_json' => json_encode([
                    'name' => '',
                    'password' => '',
                    'client_id' => '',
                    'user_prov_id' => '',
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'virtual_pos_type' => 1,
                'bank_photo' => 'assets/images/banka/garanti.png',
                'bank_visible' => '1',
            ]
        ];
        foreach ($datas as $data) {
            Bank::create($data);
        }
    }
}
