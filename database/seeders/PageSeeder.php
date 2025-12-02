<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'about',
                'content' => '<p>PelajarMu adalah portal berita dan literasi untuk pelajar Indonesia. Kami menghadirkan berita, opini, dan inspirasi belajar.</p>',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'privacy-policy',
                'content' => '<p>Kami menjaga data dan privasi pembaca. Detail kebijakan privasi akan diperbarui secara berkala.</p>',
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'terms',
                'content' => '<p>Penggunaan situs PelajarMu tunduk pada syarat dan ketentuan yang berlaku. Harap membaca dengan seksama.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'is_active' => true,
                ]
            );
        }
    }
}
