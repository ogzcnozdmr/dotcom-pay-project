<?php

namespace Database\Seeders;

use App\Models\AuthorityPages;
use Illuminate\Database\Seeder;

class AuthorityPagesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'authority_pages_id' => 1,
                'authority_pages_name' => 'Public',
                'authority_pages_page' => 'public'
            ],
            [
                'authority_pages_id' => 2,
                'authority_pages_name' => 'Ana Sayfa',
                'authority_pages_page' => 'homepage'
            ],
            [
                'authority_pages_id' => 3,
                'authority_pages_name' => 'Bayi Ekle',
                'authority_pages_page' => 'seller-add'
            ],
            [
                'authority_pages_id' => 4,
                'authority_pages_name' => 'Bayi Listale',
                'authority_pages_page' => 'seller-list'
            ],
            [
                'authority_pages_id' => 5,
                'authority_pages_name' => 'Haberler',
                'authority_pages_page' => 'news'
            ]
        ];
        foreach ($datas as $data) {
            AuthorityPages::create($data);
        }
    }
}
