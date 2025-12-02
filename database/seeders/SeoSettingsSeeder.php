<?php

namespace Database\Seeders;

use App\Settings\SeoSettings;
use Illuminate\Database\Seeder;

class SeoSettingsSeeder extends Seeder
{
    public function run(SeoSettings $settings): void
    {
        // Paksa isi nilai default
        $settings->meta_title_home = 'PelajarMu - Portal Berita Pelajar';
        $settings->meta_description_home = 'Media informasi terkini seputar dunia pelajar, pendidikan, dan teknologi.';
        $settings->meta_keywords_global = 'berita pelajar, ipm, pendidikan, sekolah';
        $settings->og_image_default = null;
        
        $settings->save();
    }
}