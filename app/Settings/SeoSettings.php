<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public string $meta_title_home;
    public string $meta_description_home;
    public string $meta_keywords_global;
    public ?string $og_image_default; // Gambar share default jika artikel tidak ada gambar
    
    public static function group(): string
    {
        return 'seo';
    }
}