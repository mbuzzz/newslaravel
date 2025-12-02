@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="bookmark-post-wrapper">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 newsten-title">Bookmark</h5>
            <span class="badge bg-light text-dark border">{{ $bookmarks->total() }}</span>
        </div>
    </div>

    <div class="container">
        @forelse($bookmarks as $bookmark)
            @if($bookmark->post)
                <div class="single-trending-post d-flex">
                    <div class="post-thumbnail">
                        @if($bookmark->post->thumbnail)
                            <img src="{{ Storage::url($bookmark->post->thumbnail) }}" alt="{{ $bookmark->post->title }}">
                        @else
                            <img src="{{ asset('mobile/img/bg-img/7.jpg') }}" alt="{{ $bookmark->post->title }}">
                        @endif
                    </div>
                    <div class="post-content">
                        @if($bookmark->post->category)
                            <a class="post-catagory" href="{{ route('category.archive', ['slug' => $bookmark->post->category->slug]) }}">{{ $bookmark->post->category->name }}</a>
                        @endif
                        <a class="post-title d-block" href="{{ route('post.show', ['category' => optional($bookmark->post->category)->slug ?? 'artikel', 'slug' => $bookmark->post->slug]) }}">
                            {{ $bookmark->post->title }}
                        </a>
                        <div class="post-meta d-flex align-items-center">
                            <a href="javascript:void(0)">{{ optional($bookmark->post->published_at)->translatedFormat('d M y') }}</a>
                            <a href="javascript:void(0)">{{ number_format($bookmark->post->view_count) }} views</a>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="alert alert-light border">
                <i class="ti ti-info-circle me-2"></i>Belum ada bookmark.
            </div>
        @endforelse
    </div>

    @if($bookmarks->hasPages())
        <div class="container mt-4">
            {{ $bookmarks->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
