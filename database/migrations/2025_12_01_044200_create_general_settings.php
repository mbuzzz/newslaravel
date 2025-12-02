<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Identitas Default
        $this->migrator->add('general.site_name', 'PelajarMu');
        $this->migrator->add('general.site_tagline', 'Media Literasi Pelajar Berkemajuan');
        $this->migrator->add('general.site_description', 'Portal berita resmi Ikatan Pelajar Muhammadiyah.');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.site_favicon', null);
        
        // --- WARNA DEFAULT (Biru & Kuning khas Organisasi) ---
        $this->migrator->add('general.site_theme_primary', '#2563EB'); // Biru (Blue-600)
        $this->migrator->add('general.site_theme_secondary', '#EAB308'); // Kuning (Yellow-500)
        $this->migrator->add('general.site_theme_footer', '#111827'); // Hitam Abu (Gray-900)
        // -----------------------------------------------------
        
        // Kontak Default
        $this->migrator->add('general.organization_email', 'admin@pelajarmu.com');
        $this->migrator->add('general.organization_phone', '+62 812 3456 7890');
        $this->migrator->add('general.organization_address', 'Jl. Menteng Raya No.62, Jakarta Pusat');
        $this->migrator->add('general.google_maps_embed_link', null);

        // Sosmed Default
        $this->migrator->add('general.social_instagram', 'https://instagram.com/ppipm');
        $this->migrator->add('general.social_facebook', null);
        $this->migrator->add('general.social_twitter', null);
        $this->migrator->add('general.social_youtube', null);
        $this->migrator->add('general.social_tiktok', null);
    }
};