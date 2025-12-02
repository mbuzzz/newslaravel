<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    // Identitas Website
    public string $site_name;
    public ?string $site_tagline;
    public ?string $site_description;
    
    // Visual / Branding
    public ?string $site_logo;
    public ?string $site_favicon;
    public ?string $sidenav_background = null;
    public ?string $adsense_client_id = null; // contoh: ca-pub-XXXXXXXXXXXXXXXX

    // --- FITUR BARU: PENGATURAN WARNA ---
    public string $site_theme_primary;   // Warna Utama (Header, Tombol, Link)
    public string $site_theme_secondary; // Warna Sekunder (Aksen, Badge)
    public string $site_theme_footer;    // Warna Background Footer
    // ------------------------------------

    // Kontak Organisasi
    public ?string $organization_email;
    public ?string $organization_phone;
    public ?string $organization_address;
    public ?string $google_maps_embed_link;

    // Media Sosial
    public ?string $social_instagram;
    public ?string $social_facebook;
    public ?string $social_twitter;
    public ?string $social_youtube;
    public ?string $social_tiktok;

    public static function group(): string
    {
        return 'general';
    }

    public static function defaults(): array
    {
        return [
            'sidenav_background' => null,
            'adsense_client_id' => null,
        ];
    }
}
