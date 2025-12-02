@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="single-catagory-wrapper">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 newsten-title">Kategori: {{ $category->name }}</h5>
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
                        <img src="{{ asset('mobile/img/bg-img/6.jpg') }}" alt="{{ $post->title }}">
                    @endif
                </div>
                <div class="post-content">
                    <a class="post-title" href="{{ route('post.show', ['category' => optional($post->category)->slug ?? 'artikel', 'slug' => $post->slug]) }}">
                        {{ $post->title }}
                    </a>
                    <div class="post-meta d-flex align-items-center">
                        <a href="javascript:void(0)">{{ optional($post->published_at)->translatedFormat('d M y') }}</a>
                        <a href="javascript:void(0)">{{ number_format($post->view_count) }} views</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-light border">
                <i class="ti ti-info-circle me-2"></i>Belum ada artikel pada kategori ini.
            </div>
        @endforelse
    </div>

    @if($posts->hasPages())
        <div class="container mt-4">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
