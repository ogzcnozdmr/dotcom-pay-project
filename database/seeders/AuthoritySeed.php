<?php

namespace Database\Seeders;

use App\Models\Authority;
use Illuminate\Database\Seeder;

class AuthoritySeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'authority_id' => 1,
                'authority_name' => 'admin',
                'authority_area' => __json_encode([2,3,4,5])
            ],
            [
                'authority_id' => 2,
                'authority_name' => 'seller',
                'authority_area' => __json_encode([1])
            ],
            [
                'authority_id' => 3,
                'authority_name' => 'user',
                'authority_area' => __json_encode([5])
            ]
        ];
        foreach ($datas as $data) {
            Authority::create($data);
        }
    }
}
