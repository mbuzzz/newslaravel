<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('seo.meta_title_home', 'PelajarMu - Portal Berita Pelajar');
        $this->migrator->add('seo.meta_description_home', 'Media informasi terkini seputar dunia pelajar, pendidikan, dan teknologi.');
        $this->migrator->add('seo.meta_keywords_global', 'berita pelajar, ipm, pendidikan, sekolah');
        $this->migrator->add('seo.og_image_default', null);
    }
};