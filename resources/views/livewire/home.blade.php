@php
    use Illuminate\Support\Facades\Storage;

    $categoryImages = [
        'mobile/img/bg-img/8.jpg',
        'mobile/img/bg-img/3.jpg',
        'mobile/img/bg-img/6.jpg',
        'mobile/img/bg-img/15.jpg',
        'mobile/img/bg-img/9.jpg',
        'mobile/img/bg-img/10.jpg',
        'mobile/img/bg-img/14.jpg',
        'mobile/img/bg-img/13.jpg',
        'mobile/img/bg-img/12.jpg',
        'mobile/img/bg-img/7.jpg',
    ];
    $siteName = config('app.name');
@endphp

<div>
    @if($popupBanner)
        <div class="container mt-3">
            <div class="toast show home-page-toast shadow-sm" data-bs-autohide="false">
                <div class="toast-body p-3 d-flex align-items-center gap-3">
                    <img src="{{ Storage::url($popupBanner->image) }}" class="rounded" style="width: 72px; height: 72px; object-fit: cover;" alt="{{ $popupBanner->title }}">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $popupBanner->title }}</h6>
                        @if($popupBanner->url)
                            <p class="mb-0 small text-muted">Klik tombol untuk membuka tautan.</p>
                        @endif
                    </div>
                    @if($popupBanner->url)
                        <a class="btn btn-primary btn-sm fw-bold" href="{{ $popupBanner->url }}" @if($popupBanner->open_in_new_tab) target="_blank" @endif> Buka </a>
                    @endif
                </div>
                <button class="btn btn-close position-absolute top-0 end-0 m-2" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="news-today-wrapper">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-3 ps-1 newsten-title">Berita Hari Ini</h5>
                <p class="mb-3 line-height-1" id="dashboardDate2"></p>
            </div>

            @if($heroBanners->count())
                <div class="hero-slides owl-carousel">
                    @foreach($heroBanners as $banner)
                        <div class="single-hero-slide" style="background-image: url('{{ Storage::url($banner->image) }}')">
                            <div class="background-shape">
                                <div class="circle2"></div>
                                <div class="circle3"></div>
                            </div>
                            <div class="slide-content h-100 d-flex align-items-end">
                                <div class="container-fluid mb-3">
                                    @if($banner->url)
                                        <a class="bookmark-post" href="{{ $banner->url }}" @if($banner->open_in_new_tab) target="_blank" @endif>
                                            <i class="ti ti-bookmark"></i>
                                        </a>
                                    @endif
                                    <a class="post-catagory" href="{{ $banner->url ?? route('home') }}">{{ $banner->type === 'hero' ? 'Sorotan' : 'Banner' }}</a>
                                    <a class="post-title d-block" href="{{ $banner->url ?? route('home') }}" @if($banner->open_in_new_tab) target="_blank" @endif>
                                        {{ $banner->title }}
                                    </a>
                                    <div class="post-meta d-flex align-items-center">
                                        <span><i class="me-1 ti ti-user-circle"></i> {{ $siteName }}</span>
                                        <span class="ms-3"><i class="me-1 ti ti-calendar-month"></i> {{ now()->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($heroPosts->count())
                <div class="hero-slides owl-carousel">
                    @foreach($heroPosts as $post)
                        <div class="single-hero-slide" style="background-image: url('{{ $post->thumbnail ? Storage::url($post->thumbnail) : asset('mobile/img/bg-img/4.jpg') }}')">
                            <div class="background-shape">
                                <div class="circle2"></div>
                                <div class="circle3"></div>
                            </div>
                            <div class="slide-content h-100 d-flex align-items-end">
                                <div class="container-fluid mb-3">
                                    <a class="bookmark-post" href="{{ route('post.show', ['category' => optional($post->category)->slug ?? 'artikel', 'slug' => $post->slug]) }}">
                                        <i class="ti ti-bookmark"></i>
                                    </a>
                                    @if($post->category)
                                        <a class="post-catagory" href="{{ route('category.archive', ['slug' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                                    @endif
                                    <a class="post-title d-block" href="{{ route('post.show', ['category' => optional($post->category)->slug ?? 'artikel', 'slug' => $post->slug]) }}">
                                        {{ $post->title }}
                                    </a>
                                    <div class="post-meta d-flex align-items-center">
                                        <span><i class="me-1 ti ti-user-circle"></i> {{ $post->custom_author ?? optional($post->author)->name ?? $siteName }}</span>
                                        <span class="ms-3"><i class="me-1 ti ti-calendar-month"></i> {{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light border mb-0">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-info-circle me-2"></i>
                        <span>Belum ada berita terbaru.</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="top-catagories-wrapper">
        <div class="bg-shapes">
            <div class="shape1"></div>
            <div class="shape2"></div>
            <div class="shape3"></div>
            <div class="shape4"></div>
            <div class="shape5"></div>
        </div>

        <h6 class="mb-3 catagory-title">Kategori Unggulan</h6>

        @if($categories->count())
            <div class="container">
                <div class="catagory-slides owl-carousel">
                    @foreach($categories as $category)
                        @php
                            $coverUrl = $category->cover ? Storage::url($category->cover) : asset($categoryImages[$loop->index % count($categoryImages)]);
                        @endphp
                        <div class="card catagory-card">
                            <a href="{{ route('category.archive', ['slug' => $category->slug]) }}">
                                <img src="{{ $coverUrl }}" alt="{{ $category->name }}">
                                <h6 class="mb-1">{{ $category->name }}</h6>
                                <span class="small text-muted d-block">{{ $category->posts_count }} artikel</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="container">
                <div class="alert alert-light border mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    <span>Kategori belum tersedia.</span>
                </div>
            </div>
        @endif
    </div>

    <div class="trending-news-wrapper" id="search">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0 ps-1 newsten-title">Trending</h5>
                <div class="d-flex align-items-center gap-2">
                    @if($activeCategory)
                        <a class="badge bg-primary-subtle text-primary text-decoration-none" href="{{ route('home') }}">{{ $activeCategory->name }} &times;</a>
                    @endif
                    @if($activeTag)
                        <a class="badge bg-info-subtle text-info text-decoration-none" href="{{ route('home') }}">#{{ $activeTag->name }} &times;</a>
                    @endif
                    <a class="btn btn-primary btn-sm" href="{{ route('trending') }}">Lihat semua</a>
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('home') }}">Reset</a>
                </div>
            </div>
        </div>

        <div class="container">
            @forelse($trendingPosts as $post)
                <div class="single-trending-post d-flex">
                    <div class="post-thumbnail">
                        @if($post->thumbnail)
                            <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}">
                        @else
                            <img src="{{ asset('mobile/img/bg-img/11.jpg') }}" alt="{{ $post->title }}">
                        @endif
                    </div>
                    <div class="post-content">
                        <a class="post-title" href="{{ route('post.show', ['category' => optional($post->category)->slug ?? 'artikel', 'slug' => $post->slug]) }}">{{ $post->title }}</a>
                        <div class="post-meta d-flex align-items-center">
                            @if($post->category)
                            <a href="{{ route('category.archive', ['slug' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                            @endif
                            <a href="javascript:void(0)">{{ optional($post->published_at)->translatedFormat('d M y') }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border">
                    <i class="ti ti-info-circle me-2"></i>Belum ada artikel trending.
                </div>
            @endforelse
        </div>
    </div>

    <div class="editorial-choice-news-wrapper">
        <div class="bg-shape1"><img src="{{ asset('mobile/img/core-img/edito.png') }}" alt=""></div>
        <div class="bg-shape2" style="background-image: url('{{ asset('mobile/img/core-img/edito2.png') }}')"></div>

        <div class="container">
            <div class="editorial-choice-title text-center mb-4">
                <i class="ti ti-shield"></i>
                <h6 class="newsten-title">Editorial Pick</h6>
            </div>
        </div>

        <div class="container">
            <div class="editorial-choice-news-slide owl-carousel">
                @forelse(($editorialPosts->count() ? $editorialPosts : $posts) as $post)
                    <div class="single-editorial-slide d-flex">
                        <a class="bookmark-post" href="{{ route('post.show', ['category' => optional($post->category)->slug ?? 'artikel', 'slug' => $post->slug]) }}">
                            <i class="ti ti-bookmark"></i>
                        </a>
                        <div class="post-thumbnail editorial-thumb" style="width:160px;height:215px;">
                            @if($post->thumbnail)
                                <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <img src="{{ asset('mobile/img/bg-img/15.jpg') }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                            @endif
                        </div>
                        <div class="post-content">
                            @if($post->category)
                                <a class="post-catagory" href="{{ route('category.archive', ['slug' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                            @endif
                            <a class="post-title d-block" href="{{ route('post.show', ['category' => optional($post->category)->slug ?? 'artikel', 'slug' => $post->slug]) }}">{{ $post->title }}</a>
                            <div class="post-meta d-flex align-items-center">
                                <span><i class="me-1 ti ti-user-circle"></i>{{ $post->custom_author ?? optional($post->author)->name }}</span>
                                <span class="ms-3"><i class="me-1 ti ti-clock"></i>{{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light border w-100">
                        <i class="ti ti-alert-circle me-2"></i>Tidak ada artikel untuk ditampilkan.
                    </div>
                @endforelse
            </div>

            @if($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <div class="popular-tags-wrapper pb-5">
        <div class="container">
            <h6 class="mb-3 ps-2 newsten-title">Tag Populer</h6>
        </div>
        <div class="container">
            <div class="popular-tags-list">
                @forelse($topTags as $tag)
                    <a class="btn btn-primary btn-sm m-1" href="{{ route('home', ['tag' => $tag->slug]) }}">#{{ $tag->name }}</a>
                @empty
                    <div class="alert alert-light border">Belum ada tag populer.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
