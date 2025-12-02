<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\ManageSeoSettings;
use App\Filament\Pages\ManageSiteSettings;
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Vite;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Pages\Dashboard as FilamentDashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        try {
            // Ambil pengaturan dari database
            $settings = app(GeneralSettings::class);
            $brandName = $settings->site_name ?: 'CMS Admin';
            $faviconUrl = $settings->site_favicon ? Storage::url($settings->site_favicon) : null;
            $logoUrl = $settings->site_logo ? Storage::url($settings->site_logo) : null;
        } catch (\Throwable $e) {
            // Fallback jika database/settings belum siap
            $brandName = 'CMS Admin';
            $faviconUrl = null;
            $logoUrl = null;
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration(Register::class) // Fitur Daftar Pembaca
            ->profile(EditProfile::class) // Edit Profil Custom
            
            // Branding Dinamis
            ->brandName($brandName)
            ->favicon($faviconUrl)
            ->brandLogo($logoUrl)
            ->brandLogoHeight('3rem')
            
            // UX dan Tema
            ->font('Plus Jakarta Sans')
            ->defaultThemeMode(ThemeMode::Dark)
            ->colors([
                'primary' => Color::Cyan, // Warna Dominan (Keren)
                'gray' => Color::Slate,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->spa()
            ->unsavedChangesAlerts() // Mengaktifkan notifikasi unsaved changes (akan diganti JS)
            ->databaseNotifications()

            // Urutan dan Pengelompokan Menu
            ->navigationGroups([
                'Manajemen Berita',
                'Manajemen Situs',
                'Manajemen Pengguna',
                'Pengaturan Sistem',
            ])
            ->collapsibleNavigationGroups(true)
            
            // Aset JS Custom (PERBAIKAN: Mengganti scripts() yang error dengan renderHook)
            // Hook di akhir <head> agar Vite dapat memuat berkas JS/CSS-nya
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => app(Vite::class)(['resources/js/custom-admin.js'])->toHtml(),
            )
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                ManageSiteSettings::class, // Pengaturan Website
                ManageSeoSettings::class, // Pengaturan SEO
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([]) // widget diatur di halaman Dashboard kustom
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->bootUsing(function () {
                $namespace = 'filament-shield';
                // Override Terjemahan Shield agar menu rapi
                $lines = [
                    'filament-shield.nav.group' => 'Manajemen Pengguna',
                    'filament-shield.nav.role.label' => 'Roles',
                    'filament-shield.nav.role.icon' => 'heroicon-o-shield-check',
                    'filament-shield.resource.label.role' => 'Role',
                    'filament-shield.resource.label.roles' => 'Roles',
                    'filament-shield.column.name' => 'Nama',
                    'filament-shield.column.guard_name' => 'Guard',
                    'filament-shield.column.permissions' => 'Hak Akses',
                    'filament-shield.column.updated_at' => 'Diperbarui',
                    'filament-shield.section.permissions' => 'Hak Akses',
                    'filament-shield.field.name' => 'Nama',
                    'filament-shield.button.save' => 'Simpan',
                ];
                app('translator')->addLines($lines, 'en', $namespace);
                app('translator')->addLines($lines, 'id', $namespace);
            });
    }
}
