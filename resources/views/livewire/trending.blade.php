@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="trending-news-wrapper">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 newsten-title">Trending</h5>
            <span class="badge bg-light text-dark border">{{ $posts->total() }} artikel</span>
        </div>
    </div>

    <div class="container">
        @forelse($posts as $post)
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

    @if($posts->hasPages())
        <div class="container mt-4">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
