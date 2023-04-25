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
                'bank_name' => 'Teb Kart',
                'bank_variable' => 'teb',
                'virtual_pos_type' => '1',
                'api_url' => 'https://sanalpos.teb.com.tr/fim/api',
                'api_security_url' => 'https://sanalpos.teb.com.tr/fim/est3Dgate',
                'bank_json' => json_encode([
                    'name' => '',
                    'password'  => '',
                    'client' => '',
                    'storekey' => '',
                    'storetype' => '3d'
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'bank_photo' => 'assets/images/banka/teb.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 2,
                'bank_name' => 'Ak Kart',
                'bank_variable' => 'ak',
                'virtual_pos_type' => '1',
                'api_url' => 'https://www.sanalakpos.com/fim/api',
                'api_security_url' => 'https://www.sanalakpos.com/fim/est3Dgate',
                'bank_json' => json_encode([
                    'name' => '',
                    'password'  => '',
                    'client' => '',
                    'storekey' => '',
                    'storetype' => '3d'
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'bank_photo' => 'assets/images/banka/ak.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 3,
                'bank_name' => 'Ziraat Kart',
                'bank_variable' => 'ziraat',
                'virtual_pos_type' => '1',
                'api_url' => 'https://sanalpos2.ziraatbank.com.tr/fim/api',
                'api_security_url' => 'https://sanalpos2.ziraatbank.com.tr/fim/est3Dgate',
                'bank_json' => json_encode([
                    'name' => '',
                    'password'  => '',
                    'client' => '',
                    'storekey' => '',
                    'storetype' => '3d'
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'bank_photo' => 'assets/images/banka/ziraat.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 4,
                'bank_name' => 'İş Kart',
                'bank_variable' => 'is',
                'virtual_pos_type' => '1',
                'api_url' => 'https://sanalpos.isbank.com.tr/fim/api',
                'api_security_url' => 'https://sanalpos.isbank.com.tr/fim/est3Dgate',
                'bank_json' => json_encode([
                    'name' => '',
                    'password'  => '',
                    'client' => '',
                    'storekey' => '',
                    'storetype' => '3d'
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'bank_photo' => 'assets/images/banka/is.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 5,
                'bank_name' => 'Ziraat Katılım',
                'bank_variable' => 'ziraatkatilim',
                'virtual_pos_type' => '2',
                'api_url' => 'https://vpos.ziraatkatilim.com.tr/MPI/Default.aspx',
                'api_security_url' => 'https://vpos.ziraatkatilim.com.tr/MPI/3DHost.aspx',
                'bank_json' => json_encode([
                    'name' => '',
                    'password'  => '',
                    'client' => '',
                    'storekey' => '',
                    'storetype' => '3d'
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'bank_photo' => 'assets/images/banka/ziraatkatilim.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 6,
                'bank_name' => 'Finans Kart',
                'bank_variable' => 'finans',
                'virtual_pos_type' => '3',
                'api_url' => 'https://vpos.qnbfinansbank.com/Gateway/Default.aspx',
                'api_security_url' => 'https://vpos.qnbfinansbank.com/Gateway/Default.aspx',
                'bank_json' => json_encode([
                    'name' => '',
                    'password'  => '',
                    'client' => '',
                    'storekey' => '',
                    'storetype' => '3DModel',
                    'storetype3d' => '3DModelPayment'
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'bank_photo' => 'assets/images/banka/finans.png',
                'bank_visible' => '1',
            ],
            [
                'bank_id' => 7,
                'bank_name' => 'Vakıf Kart',
                'bank_variable' => 'vakif',
                'virtual_pos_type' => '4',
                'api_url' => 'https://onlineodeme.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx',
                'api_security_url' => 'https://3dsecure.vakifbank.com.tr:4443/MPIAPI/MPI_Enrollment.aspx',
                'bank_json' => json_encode([
                    'name' => '',
                    'password'  => '',
                    'client' => '',
                    'storekey' => '',
                    'storetype' => '3d',
                    'storetype3d' => ''
                ]),
                'max_installment' => 12,
                'min_installment_amount' => 6,
                'plus_installment' => 2,
                'bank_photo' => 'assets/images/banka/vakif.png',
                'bank_visible' => '1',
            ]
        ];
        foreach ($datas as $data) {
            Bank::create($data);
        }
    }
}
