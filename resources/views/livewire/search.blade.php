@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div>
    <div class="search-form-wrapper py-3">
        <div class="container">
            <form wire:submit.prevent>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-search"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Cari artikel..." wire:model.debounce.500ms="q">
                </div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <select class="form-select form-select-sm" wire:model="category">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @if($q || $category)
                        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="$set('q','');$set('category',null);">Reset</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="search-results-wrapper pb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="newsten-title mb-0">Hasil Pencarian</h6>
                <span class="badge bg-light text-dark border">{{ $posts->total() }}</span>
            </div>
        </div>

        <div class="container">
            @forelse($posts as $post)
                <div class="single-trending-post d-flex">
                    <div class="post-thumbnail">
                        @if($post->thumbnail)
                            <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}">
                        @else
                            <img src="{{ asset('mobile/img/bg-img/8.jpg') }}" alt="{{ $post->title }}">
                        @endif
                    </div>
                    <div class="post-content">
                        @if($post->category)
                            <a class="post-catagory" href="{{ route('category.archive', ['slug' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                        @endif
                        <a class="post-title d-block" href="{{ route('post.show', ['category' => optional($post->category)->slug ?? 'artikel', 'slug' => $post->slug]) }}">
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
                    <i class="ti ti-info-circle me-2"></i>Tidak ada hasil.
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="container mt-4">
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
