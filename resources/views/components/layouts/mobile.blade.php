@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $seo = app(\App\Settings\SeoSettings::class);
    $navCategories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
    $aboutPage = \App\Models\Page::where('slug', 'about')->where('is_active', true)->first();

    $pageTitle = ($title ?? $seo->meta_title_home ?? $settings->site_name) . ' | ' . $settings->site_name;
    $pageDesc = $description ?? $seo->meta_description_home ?? $settings->site_description;
    $pageKeywords = $keywords ?? $seo->meta_keywords_global;
    $pageType = isset($published_time) ? 'article' : 'website';

    $favicon = $settings->site_favicon ? \Illuminate\Support\Facades\Storage::url($settings->site_favicon) : asset('mobile/img/core-img/favicon.ico');
    $logo = $settings->site_logo ? \Illuminate\Support\Facades\Storage::url($settings->site_logo) : asset('mobile/img/core-img/logo.png');
    $sidenavBg = $settings->sidenav_background ? \Illuminate\Support\Facades\Storage::url($settings->sidenav_background) : asset('mobile/img/bg-img/1.jpg');
    $pageImage = $image ?? ($settings->site_logo ? \Illuminate\Support\Facades\Storage::url($settings->site_logo) : asset('mobile/img/bg-img/4.jpg'));
    $pageUrl = url()->current();
    $showBackButton = $showBackButton ?? false;
    $headerTitle = $headerTitle ?? null;
    $backUrl = $backUrl ?? route('home');
    $homeActive = request()->routeIs('home') || request()->routeIs('post.show') || request()->routeIs('category.archive');
    $searchActive = request()->routeIs('search');
    $trendingActive = request()->routeIs('trending');
    $pageActive = request()->routeIs('page.show') || request()->routeIs('pages.index');
    $bookmarkActive = request()->routeIs('bookmarks');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($pageDesc, 160) }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="theme-color" content="#e42f08">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="canonical" href="{{ $pageUrl }}">

    <meta property="og:type" content="{{ $pageType }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($pageDesc, 180) }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:site_name" content="{{ $settings->site_name }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($pageDesc, 180) }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <title>{{ $pageTitle }}</title>

    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ asset('mobile/img/icons/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('mobile/img/icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('mobile/img/icons/icon-167x167.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('mobile/img/icons/icon-180x180.png') }}">

    <link rel="manifest" href="{{ asset('mobile/manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('mobile/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/livewire-thumbs.css') }}">
    <style>
        :root {
            color-scheme: light;
        }
        .theme-toggle {
            position: fixed;
            bottom: 90px;
            right: 16px;
            z-index: 1050;
        }
    </style>

    @livewireStyles
    @stack('styles')
</head>
<body class="bg-light">
    <div class="preloader" id="preloader">
        <div class="spinner-grow text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="header-area" id="headerArea">
        <div class="container h-100 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                @if($showBackButton)
                    <div class="back-button">
                        <a href="{{ $backUrl }}">
                            <i class="ti ti-chevron-left"></i>
                        </a>
                    </div>
                @else
                    <div class="navbar--toggler" id="newstenNavbarToggler">
                        <span></span><span></span><span></span><span></span>
                    </div>
                @endif
            </div>

            <div class="logo-wrapper text-center">
                @if($showBackButton && $headerTitle)
                    <div class="page-heading">
                        <h6 class="mb-0">{{ $headerTitle }}</h6>
                    </div>
                @else
                    <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none justify-content-center">
                        <img src="{{ $logo }}" alt="{{ $settings->site_name }}" style="height: 36px; width: auto;">
                        <span class="fw-bold text-dark">{{ $settings->site_name }}</span>
                    </a>
                @endif
            </div>

            <div class="search-form">
                <a href="{{ route('search') }}">
                    <i class="ti ti-search"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="sidenav-black-overlay"></div>

    <div class="sidenav-wrapper" id="sidenavWrapper">
        <div class="time-date-weather-wrapper text-center py-5" style="background-image: url('{{ $sidenavBg }}')">
            <div class="weather-update mb-4">
                <i class="ti ti-temperature-sun"></i>
                <h4 class="mb-1">{{ now()->format('d M Y') }}</h4>
                <h6 class="mb-0">{{ $settings->site_name }}</h6>
                <p class="mb-0">{{ $settings->site_tagline }}</p>
            </div>
            <div class="time-date">
                <div id="dashboardDate"></div>
                <div class="running-time d-flex justify-content-center">
                    <div id="hours"></div><span>:</span>
                    <div id="min"></div><span>:</span>
                    <div id="sec"></div>
                </div>
            </div>
        </div>

        <ul class="sidenav-nav">
            <li>
                <a href="{{ route('home') }}"><i class="ti ti-home"></i>Beranda</a>
            </li>
            @foreach($navCategories as $category)
                <li>
                    <a href="{{ route('category.archive', ['slug' => $category->slug]) }}">
                        <i class="ti ti-layout"></i>{{ $category->name }}
                    </a>
                </li>
            @endforeach
            @auth
                <li>
                    <a href="{{ route('mobile.profile') }}"><i class="ti ti-user-circle"></i>Profil Saya</a>
                </li>
            @endauth
            <li>
                <a href="{{ route('pages.index') }}"><i class="ti ti-files"></i>Semua Halaman</a>
            </li>
            @if($aboutPage)
                <li>
                    <a href="{{ route('page.show', ['slug' => $aboutPage->slug]) }}"><i class="ti ti-info-circle"></i>{{ $aboutPage->title }}</a>
                </li>
            @endif
            @auth
                <li>
                    <a href="/admin"><i class="ti ti-dashboard"></i>Dashboard</a>
                </li>
            @else
                <li>
                    <a href="/admin/login"><i class="ti ti-login"></i>Masuk</a>
                </li>
            @endauth
        </ul>

        <div class="go-home-btn" id="goHomeBtn">
            <i class="ti ti-arrow-left"></i>
        </div>
    </div>

    <div class="page-content-wrapper">
        {{ $slot }}
    </div>

    <div class="footer-nav-area" id="footerNav">
        <div class="newsten-footer-nav h-100">
            <ul class="h-100 d-flex align-items-center justify-content-between">
                <li class="{{ $homeActive ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="ti ti-home-2"></i>
                    </a>
                </li>
                <li class="{{ $searchActive ? 'active' : '' }}">
                    <a href="{{ route('search') }}">
                        <i class="ti ti-search"></i>
                    </a>
                </li>
                <li class="{{ $trendingActive ? 'active' : '' }}">
                    <a href="{{ route('trending') }}">
                        <i class="ti ti-heart"></i>
                    </a>
                </li>
                <li class="{{ $pageActive ? 'active' : '' }}">
                    <a href="{{ route('pages.index') }}">
                        <i class="ti ti-files"></i>
                    </a>
                </li>
                <li class="{{ $bookmarkActive ? 'active' : '' }}">
                    @if(auth()->check())
                        <a href="{{ route('bookmarks') }}">
                            <i class="ti ti-bookmark"></i>
                        </a>
                    @else
                        <a href="{{ route('mobile.login') }}">
                            <i class="ti ti-lock"></i>
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>

    <div class="theme-toggle">
        <div class="form-check form-switch bg-white shadow-sm px-3 py-2 rounded-pill">
            <input class="form-check-input" type="checkbox" id="themeSwitch">
            <label class="form-check-label small ms-1" for="themeSwitch">Dark Mode</label>
        </div>
    </div>

    <script src="{{ asset('mobile/js/jquery.min.js') }}"></script>
    <script src="{{ asset('mobile/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('mobile/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('mobile/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('mobile/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('mobile/js/date-clock.js') }}"></script>
    <script src="{{ asset('mobile/js/active.js') }}"></script>
    <script>
        (function() {
            const themeKey = 'theme';
            const stored = localStorage.getItem(themeKey) || 'light';
            document.documentElement.dataset.theme = stored;
            document.documentElement.classList.toggle('dark-mode', stored === 'dark');
            document.querySelectorAll('#themeSwitch').forEach(el => el.checked = stored === 'dark');

            document.addEventListener('change', function(e) {
                if (e.target && e.target.id === 'themeSwitch') {
                    const mode = e.target.checked ? 'dark' : 'light';
                    document.documentElement.dataset.theme = mode;
                    document.documentElement.classList.toggle('dark-mode', mode === 'dark');
                    localStorage.setItem(themeKey, mode);
                    document.cookie = themeKey + '=' + mode + ';path=/;max-age=' + (60*60*24*365);
                }
            });
        })();
    </script>

    @livewireScripts
    @stack('modals')
    @stack('scripts')

    @if($settings->adsense_client_id)
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $settings->adsense_client_id }}" crossorigin="anonymous"></script>
    @endif
</body>
</html>
