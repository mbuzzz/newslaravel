@php
    // 1. Ambil Pengaturan Global
    $settings = app(\App\Settings\GeneralSettings::class);
    $seo = app(\App\Settings\SeoSettings::class);

    // 2. Ambil Kategori untuk Menu (Hanya yang aktif, ambil 5-6 utama)
    $navCategories = \App\Models\Category::where('is_active', true)->take(6)->get();

    // 3. Logika SEO (Fallback System)
    $pageTitle = $title ?? $seo->meta_title_home ?? $settings->site_name;
    $pageDesc = $description ?? $seo->meta_description_home ?? $settings->site_description;
    $pageKeywords = $keywords ?? $seo->meta_keywords_global;
    
    // Gambar Share (OG Image)
    $defaultOg = $seo->og_image_default ? \Illuminate\Support\Facades\Storage::url($seo->og_image_default) : asset('images/default-og.jpg');
    $pageImage = $image ?? $defaultOg;
    
    $pageUrl = url()->current();
    $pageType = isset($published_time) ? 'article' : 'website';

    // 4. Aset Visual
    $favicon = $settings->site_favicon ? \Illuminate\Support\Facades\Storage::url($settings->site_favicon) : asset('favicon.ico');
    $logo = $settings->site_logo ? \Illuminate\Support\Facades\Storage::url($settings->site_logo) : null;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- --- SEO META TAGS --- -->
    <title>{{ $pageTitle }} | {{ $settings->site_name }}</title>
    <meta name="description" content="{{Str::limit($pageDesc, 160)}}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="author" content="{{ $author ?? $settings->site_name }}">
    <link rel="canonical" href="{{ $pageUrl }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph & Twitter Card -->
    <meta property="og:type" content="{{ $pageType }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{Str::limit($pageDesc, 200)}}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:site_name" content="{{ $settings->site_name }}">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{Str::limit($pageDesc, 200)}}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    @if(isset($published_time))
        <meta property="article:published_time" content="{{ $published_time }}">
        <meta property="article:author" content="{{ $author ?? $settings->site_name }}">
    @endif

    <link rel="icon" href="{{ $favicon }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- --- DYNAMIC THEME CSS --- -->
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        :root {
            --color-primary: {{ $settings->site_theme_primary ?? '#2563EB' }}; 
            --color-secondary: {{ $settings->site_theme_secondary ?? '#EAB308' }};
            --color-footer: {{ $settings->site_theme_footer ?? '#111827' }};
        }

        /* Classes Override */
        .text-theme-primary { color: var(--color-primary) !important; }
        .bg-theme-primary { background-color: var(--color-primary) !important; }
        .border-theme-primary { border-color: var(--color-primary) !important; }
        .ring-theme-primary { --tw-ring-color: var(--color-primary) !important; }
        
        .text-theme-secondary { color: var(--color-secondary) !important; }
        .bg-theme-secondary { background-color: var(--color-secondary) !important; }
        
        .bg-theme-footer { background-color: var(--color-footer) !important; }
        
        .hover\:text-theme-primary:hover { color: var(--color-primary) !important; }
        .hover\:text-theme-secondary:hover { color: var(--color-secondary) !important; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-theme-primary selection:text-white">

    <!-- NAVBAR -->
    <header x-data="{ mobileMenuOpen: false, scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="{ 'bg-white/90 backdrop-blur-md shadow-sm': scrolled, 'bg-white border-b border-gray-100': !scrolled }"
            class="sticky top-0 z-50 transition-all duration-300 w-full">
            
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo Brand -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group outline-none">
                        @if($logo)
                            <img src="{{ $logo }}" alt="{{ $settings->site_name }}" class="h-10 w-auto transform group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg transform group-hover:rotate-6 transition duration-300 bg-theme-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                        @endif
                        
                        <div class="flex flex-col justify-center">
                            <span class="text-xl md:text-2xl font-extrabold tracking-tight text-gray-900 group-hover:text-theme-primary transition duration-300 leading-none">
                                {{ $settings->site_name }}
                            </span>
                            
                            <!-- Responsive Tagline -->
                            @if($settings->site_tagline)
                                <span class="text-[10px] sm:text-xs font-medium tracking-wide text-gray-500 uppercase mt-1 line-clamp-1 max-w-[150px] sm:max-w-xs md:max-w-sm">
                                    {{ $settings->site_tagline }}
                                </span>
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu (Dinamis dari Kategori) -->
                <nav class="hidden md:flex space-x-6 lg:space-x-8">
                    <a href="{{ route('home') }}" class="text-sm font-bold text-gray-600 hover:text-theme-primary transition duration-300 py-2 border-b-2 border-transparent hover:border-theme-primary {{ request()->routeIs('home') ? 'text-theme-primary border-theme-primary' : '' }}">
                        Beranda
                    </a>
                    
                    @foreach($navCategories as $category)
                        <a href="#" class="text-sm font-bold text-gray-600 hover:text-theme-primary transition duration-300 py-2 border-b-2 border-transparent hover:border-theme-primary">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </nav>

                <!-- Right Side Buttons -->
                <div class="flex items-center gap-3">
                    <!-- Search (Desktop) -->
                    <button class="hidden sm:flex items-center justify-center p-2 text-gray-400 hover:text-theme-primary transition rounded-full hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    @auth
                        <a href="/admin" class="hidden md:inline-flex items-center px-5 py-2 border border-gray-200 text-sm font-bold rounded-full text-gray-700 hover:text-theme-primary hover:border-theme-primary transition duration-300 bg-white shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <div class="hidden md:flex items-center gap-2">
                            <a href="/admin/login" class="text-sm font-bold text-gray-600 hover:text-theme-primary transition px-3 py-2">
                                Masuk
                            </a>
                            <a href="/admin/register" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-full text-white bg-theme-primary shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-300">
                                Daftar
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-theme-primary">
                        <span class="sr-only">Open menu</span>
                        <svg x-show="!mobileMenuOpen" class="block h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="block h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Dinamis Kategori) -->
        <div x-show="mobileMenuOpen" 
             x-collapse 
             x-cloak
             @click.away="mobileMenuOpen = false"
             class="md:hidden border-t border-gray-100 bg-white shadow-lg">
            <div class="space-y-1 px-4 py-4">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50 {{ request()->routeIs('home') ? 'text-theme-primary bg-blue-50' : '' }}">
                    Beranda
                </a>
                
                @foreach($navCategories as $category)
                    <a href="#" class="block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50 hover:text-theme-primary">
                        {{ $category->name }}
                    </a>
                @endforeach

                <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                    @auth
                        <a href="/admin" class="block w-full text-center rounded-lg bg-gray-50 px-3 py-3 text-base font-bold text-gray-900 hover:bg-gray-100">Dashboard Admin</a>
                    @else
                        <a href="/admin/login" class="flex w-full items-center justify-center rounded-lg border border-gray-200 px-3 py-2.5 text-base font-bold text-gray-900 hover:bg-gray-50">
                            Masuk
                        </a>
                        <a href="/admin/register" class="flex w-full items-center justify-center rounded-lg bg-theme-primary px-3 py-2.5 text-base font-bold text-white shadow-sm hover:opacity-90">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- FOOTER PROFESIONAL -->
    <footer class="text-white pt-16 pb-8 border-t-4 border-theme-secondary bg-theme-footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                
                <!-- Brand Info -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        @if($logo)
                            <img src="{{ $logo }}" class="h-9 w-auto brightness-0 invert opacity-90"> 
                        @endif
                        <span class="text-2xl font-bold tracking-tight">{{ $settings->site_name }}</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        {{ Str::limit($settings->site_description, 150) }}
                    </p>
                    <div class="space-y-3 pt-2">
                        @if($settings->organization_address)
                        <div class="flex items-start space-x-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-theme-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="leading-snug">{{ $settings->organization_address }}</span>
                        </div>
                        @endif
                        @if($settings->organization_phone)
                        <div class="flex items-center space-x-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 flex-shrink-0 text-theme-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>{{ $settings->organization_phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Navigasi Kategori (Dinamis) -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6 border-b-2 border-theme-secondary inline-block pb-1">Jelajahi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-theme-secondary transition-colors duration-200 flex items-center group"><span class="mr-2 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition">›</span> Beranda</a></li>
                        
                        @foreach($navCategories as $category)
                            <li><a href="#" class="hover:text-theme-secondary transition-colors duration-200 flex items-center group"><span class="mr-2 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition">›</span> {{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Halaman Legal -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6 border-b-2 border-theme-secondary inline-block pb-1">Informasi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        @foreach(\App\Models\Page::active()->get() as $page)
                            <li><a href="{{ route('page.show', $page->slug) }}" class="hover:text-theme-secondary transition-colors duration-200 flex items-center group"><span class="mr-2 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition">›</span> {{ $page->title }}</a></li>
                        @endforeach
                        <li><a href="#" class="hover:text-theme-secondary transition-colors duration-200 flex items-center group"><span class="mr-2 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition">›</span> Kontak Kami</a></li>
                    </ul>
                </div>

                <!-- Sosmed -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6 border-b-2 border-theme-secondary inline-block pb-1">Ikuti Kami</h4>
                    <p class="text-sm text-gray-400 mb-6 leading-relaxed">Dapatkan informasi terbaru melalui media sosial kami.</p>
                    <div class="flex flex-wrap gap-3">
                        @if($settings->social_instagram)
                            <a href="{{ $settings->social_instagram }}" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-gradient-to-tr hover:from-yellow-500 hover:via-red-500 hover:to-purple-600 transition duration-300 border border-white/10" aria-label="Instagram">IG</a>
                        @endif
                        @if($settings->social_youtube)
                            <a href="{{ $settings->social_youtube }}" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-red-600 transition duration-300 border border-white/10" aria-label="YouTube">YT</a>
                        @endif
                        @if($settings->social_tiktok)
                            <a href="{{ $settings->social_tiktok }}" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-black transition duration-300 border border-white/10" aria-label="TikTok">TT</a>
                        @endif
                        @if($settings->social_facebook)
                            <a href="{{ $settings->social_facebook }}" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-blue-600 transition duration-300 border border-white/10" aria-label="Facebook">FB</a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-700/50 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 gap-4">
                <p>&copy; {{ date('Y') }} <strong>{{ $settings->site_name }}</strong>. All rights reserved.</p>
                <p>Designed with <span class="text-red-500">❤</span> for Education.</p>
            </div>
        </div>
    </footer>

</body>
</html>