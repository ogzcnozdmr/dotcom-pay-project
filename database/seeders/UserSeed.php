<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'user_id' => 1,
                'user_name' => 'Oğuzcan ÖZDEMİR',
                'user_username' => 'ozi',
                'user_email' => 'o.ozdmr.40@gmail.com',
                'user_phone' => '5466458003',
                'user_password' => '$2y$12$suDnWXGSYLZeIYKs9uaLyeSQPKVKR6VFzu6q4p2HxiWEyLa887.Ky',
                'user_authority' => 'admin',
                'official_distributor' => '0'
            ]
        ];
        foreach ($datas as $data) {
            User::create($data);
        }
    }
}
