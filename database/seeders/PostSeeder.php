<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User Pertama sebagai Penulis Default
        $user = User::first();
        
        // Jika belum ada user, buat satu (jaga-jaga)
        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@pelajarmu.com',
                'password' => bcrypt('password'),
            ]);
        }

        // 2. Buat Kategori (Data Realistis)
        $categories = [
            'Berita Utama',
            'Opini Pelajar',
            'Kabar Daerah',
            'Teknologi & Sains',
            'Kajian Islam',
            'Sastra & Budaya',
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($cat)],
                [
                    'name' => $cat,
                    'is_active' => true
                ]
            );
            $categoryIds[] = $category->id;
        }

        // 3. Buat Tags
        $tags = [
            'IPM', 'Literasi', 'Digital', 'Sekolah', 'Prestasi', 
            'Muhammadiyah', 'Beasiswa', 'Event', 'Tips Belajar'
        ];

        $tagIds = [];
        foreach ($tags as $tag) {
            $t = Tag::firstOrCreate(
                ['slug' => Str::slug($tag)],
                ['name' => $tag]
            );
            $tagIds[] = $t->id;
        }

        // 4. Buat Artikel Dummt (Looping)
        // Kita buat 15 artikel
        for ($i = 1; $i <= 15; $i++) {
            $title = fake()->sentence(6); // Judul random 6 kata
            
            // Konten HTML Dummy untuk Rich Editor
            $content = '
                <p>' . fake()->paragraph(5) . '</p>
                <h3>Subjudul Penting</h3>
                <p>' . fake()->paragraph(4) . '</p>
                <ul>
                    <li>Poin penting pertama tentang pelajar.</li>
                    <li>Poin kedua tentang kemajuan teknologi.</li>
                    <li>Poin ketiga tentang semangat berkarya.</li>
                </ul>
                <p>' . fake()->paragraph(3) . '</p>
                <blockquote>"Ini adalah kutipan motivasi yang dibuat secara otomatis untuk mempercantik tampilan artikel."</blockquote>
            ';

            $post = Post::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(4),
                'user_id' => $user->id,
                'category_id' => $categoryIds[array_rand($categoryIds)], // Pilih kategori acak
                
                // Redaksi
                'custom_author' => $i % 3 == 0 ? fake()->name() : null, // Tiap kelipatan 3 pakai penulis tamu
                'editor' => fake()->firstName() . ' (Redaksi)',
                'narasumber' => $i % 2 == 0 ? 'Dr. ' . fake()->lastName() : null,
                
                // SEO
                'meta_title' => $title,
                'meta_description' => Str::limit(strip_tags($content), 150),
                'meta_keywords' => 'pelajar, berita, ipm, sekolah',
                
                // Status
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 30)), // Tanggal mundur 0-30 hari ke belakang
                
                // Gambar (Pakai Placeholder Online)
                // Nanti di frontend akan tetap tampil meski belum upload file asli
                'thumbnail' => null, 
                'content' => $content,
            ]);

            // Attach 2-3 Tag secara acak ke setiap post
            $randomTags = collect($tagIds)->random(rand(2, 4))->toArray();
            $post->tags()->attach($randomTags);
        }
    }
}