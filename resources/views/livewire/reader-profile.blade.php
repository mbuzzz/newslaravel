@php
    use Illuminate\Support\Facades\Storage;
    $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp

<div class="profile-viewer-wrapper">
    <div class="container">
        <div class="profile-card text-center mt-3 mb-4 p-4 bg-white rounded-4 shadow-sm position-relative overflow-hidden">
            <div class="bg-shapes">
                <div class="shape1"></div>
                <div class="shape2"></div>
                <div class="shape3"></div>
                <div class="shape4"></div>
                <div class="shape5"></div>
            </div>
            <div class="user-thumbnail mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width:96px;height:96px;background:#eef2f7;">
                <span class="fw-bold fs-3 text-primary">{{ $initial }}</span>
            </div>
            <h5 class="mb-1">{{ $user->name }}</h5>
            <p class="text-muted mb-3">{{ $user->email }}</p>
            <div class="d-flex justify-content-center gap-3">
                <div class="text-center">
                    <h6 class="mb-0">{{ $bookmarkCount }}</h6>
                    <small class="text-muted">Bookmark</small>
                </div>
                <div class="text-center">
                    <h6 class="mb-0">{{ $likesCount }}</h6>
                    <small class="text-muted">Likes</small>
                </div>
                <div class="text-center">
                    <h6 class="mb-0">{{ $postsCount }}</h6>
                    <small class="text-muted">Artikel</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="newsten-title mb-0">Bookmark Terakhir</h6>
        </div>
        @forelse($bookmarks as $bookmark)
            <div class="single-trending-post d-flex">
                <div class="post-thumbnail">
                    @if($bookmark->post?->thumbnail)
                        <img src="{{ Storage::url($bookmark->post->thumbnail) }}" alt="{{ $bookmark->post->title }}">
                    @else
                        <img src="{{ asset('mobile/img/bg-img/7.jpg') }}" alt="{{ $bookmark->post->title }}">
                    @endif
                </div>
                <div class="post-content">
                    @if($bookmark->post?->category)
                        <a class="post-catagory" href="{{ route('category.archive', ['slug' => $bookmark->post->category->slug]) }}">{{ $bookmark->post->category->name }}</a>
                    @endif
                    <a class="post-title d-block" href="{{ route('post.show', ['category' => optional($bookmark->post?->category)->slug ?? 'artikel', 'slug' => $bookmark->post?->slug]) }}">
                        {{ $bookmark->post?->title }}
                    </a>
                    <div class="post-meta d-flex align-items-center">
                        <a href="javascript:void(0)">{{ optional($bookmark->post?->published_at)->translatedFormat('d M y') }}</a>
                        <a href="javascript:void(0)">{{ number_format($bookmark->post?->view_count ?? 0) }} views</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-light border">
                <i class="ti ti-info-circle me-2"></i>Belum ada bookmark.
            </div>
        @endforelse
    </div>
</div>
