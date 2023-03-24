<?php

namespace Database\Seeders;

use App\Models\Installment;
use Illuminate\Database\Seeder;

class InstallmentSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'installment_id' => 1,
                'installment_number' => 0,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 2,
                'installment_number' => 1,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 3,
                'installment_number' => 2,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 4,
                'installment_number' => 3,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 5,
                'installment_number' => 4,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 6,
                'installment_number' => 5,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 7,
                'installment_number' => 6,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 8,
                'installment_number' => 7,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 9,
                'installment_number' => 8,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 10,
                'installment_number' => 9,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 11,
                'installment_number' => 10,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 12,
                'installment_number' => 11,
                'installment_visible' => '1'
            ],
            [
                'installment_id' => 13,
                'installment_number' => 12,
                'installment_visible' => '1'
            ]
        ];
        foreach ($datas as $data) {
            Installment::create($data);
        }
    }
}
