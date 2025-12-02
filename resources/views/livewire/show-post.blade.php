@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div>
    <div id="scrollIndicator"></div>

    <div class="single-blog-thumbnail">
        @if($post->thumbnail)
            <img class="w-100 blog-hero-img" src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}">
        @else
            <img class="w-100 blog-hero-img" src="{{ asset('mobile/img/bg-img/29.jpg') }}" alt="{{ $post->title }}">
        @endif
        <button type="button" class="post-bookmark" wire:click="toggleSave">
            <i class="ti {{ $isSaved ? 'ti-bookmark-filled text-primary' : 'ti-bookmark' }}"></i>
        </button>
    </div>

    <div class="single-blog-info">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="post-like-wrap text-center">
                    <button type="button" class="post-love d-block {{ $isLiked ? 'text-danger' : '' }}" wire:click="toggleLike">
                        <i class="ti ti-heart"></i>
                    </button>
                    <span class="d-block">{{ number_format($likesCount) }} Likes</span>
                    <div class="line"></div>
                    <a class="post-share" href="#comments-section"><i class="ti ti-message"></i></a>
                    <span class="d-block">{{ $comments->count() }}</span>
                </div>
                <div class="post-content-wrap ms-3">
                    @if($post->category)
                        <a class="post-catagory d-inline-block mb-2" href="{{ route('category.archive', ['slug' => $post->category->slug]) }}">{{ $post->category->name }}</a>
                    @endif
                    <h5 class="mb-2">{{ $post->title }}</h5>
                    <div class="post-meta d-flex flex-wrap align-items-center gap-2">
                        <span class="post-date"><i class="ti ti-calendar"></i> {{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
                        <span class="post-views"><i class="ti ti-eye"></i> {{ number_format($post->view_count) }} views</span>
                        <span><i class="ti ti-user-circle"></i> {{ $post->custom_author ?? optional($post->author)->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-content-wrapper">
        <div class="container">
            <div class="user-meta-data d-flex align-items-center">
                <div class="user-thumbnail">
                    <img src="{{ asset('mobile/img/bg-img/3.jpg') }}" alt="">
                </div>
                <div class="user-content">
                    <h6>{{ $post->custom_author ?? optional($post->author)->name ?? 'Penulis' }}</h6>
                    <p>Penulis</p>
                    <div class="user-meta-data d-flex align-items-center justify-content-between">
                        <p class="mx-1"><span>{{ $post->category?->name }}</span></p>
                        <p class="mx-1"><span>{{ optional($post->published_at)->translatedFormat('d M Y') }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-description">
        <div class="container">
            {!! $post->content !!}
        </div>
    </div>

    @if($post->tags->count())
        <div class="related-post-wrapper">
            <div class="container">
                <h6 class="mb-3 newsten-title">Tags</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach($post->tags as $tag)
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('home', ['tag' => $tag->slug]) }}">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($relatedPosts->count())
        <div class="related-post-wrapper">
            <div class="container">
                <h6 class="mb-3 newsten-title">Artikel Terkait</h6>
            </div>
            <div class="container">
                @foreach($relatedPosts as $related)
                    <div class="single-trending-post d-flex">
                    <div class="post-thumbnail">
                        @if($related->thumbnail)
                            <img src="{{ Storage::url($related->thumbnail) }}" alt="{{ $related->title }}">
                        @else
                            <img src="{{ asset('mobile/img/bg-img/17.jpg') }}" alt="{{ $related->title }}">
                        @endif
                        </div>
                        <div class="post-content">
                            <a class="post-title" href="{{ route('post.show', ['category' => optional($related->category)->slug ?? 'artikel', 'slug' => $related->slug]) }}">{{ $related->title }}</a>
                            <div class="post-meta d-flex align-items-center">
                                @if($related->category)
                                    <a href="{{ route('category.archive', ['slug' => $related->category->slug]) }}">{{ $related->category->name }}</a>
                                @endif
                                <a href="javascript:void(0)">{{ optional($related->published_at)->translatedFormat('d M y') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="live-video-comments" id="comments-section">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="newsten-title video-comments-title mb-0">{{ $comments->count() }} Komentar</h6>
            </div>

            <div class="d-flex align-items-center gap-2 mb-3">
                <a class="btn btn-outline-secondary btn-sm" href="https://wa.me/?text={{ urlencode($post->title . ' ' . $shareUrl) }}" target="_blank">
                    <i class="ti ti-brand-whatsapp"></i> WhatsApp
                </a>
                <a class="btn btn-outline-secondary btn-sm" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank">
                    <i class="ti ti-brand-facebook"></i> Facebook
                </a>
                <button class="btn btn-primary btn-sm" onclick="navigator.share && navigator.share({title:'{{ addslashes($post->title) }}', url:'{{ $shareUrl }}'})">
                    <i class="ti ti-share"></i> Bagikan
                </button>
            </div>

            @auth
                <div class="comment-form">
                    <form wire:submit.prevent="submitComment">
                        <textarea wire:model="commentContent" id="comment-text" rows="4" placeholder="Tulis komentar yang sopan..." class="form-control"></textarea>
                        @error('commentContent') <small class="text-danger">{{ $message }}</small> @enderror
                        <button class="mt-2 btn btn-primary btn-sm fw-bold" type="submit">
                            <i class="ti ti-send me-1"></i> Kirim
                        </button>
                    </form>
                </div>
            @else
                <div class="alert alert-light border">
                    <p class="mb-2">Masuk untuk ikut berdiskusi.</p>
                    <a class="btn btn-primary btn-sm me-2" href="/admin/login">Masuk</a>
                    <a class="btn btn-outline-secondary btn-sm" href="/admin/register">Daftar</a>
                </div>
            @endauth

            @if($comments->isNotEmpty())
                <ul class="comments-list mt-3">
                    @foreach($comments as $comment)
                        @php
                            $commentName = optional($comment->user)->name ?? 'Pengguna';
                        @endphp
                        <li class="single-comment-wrap">
                            <div class="d-flex mb-4">
                                <div class="comment-author">
                                    <span class="avatar bg-primary text-white fw-bold rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                        {{ strtoupper(substr($commentName, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="comment-meta">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="comment-author-info-wrap">
                                            <h6 class="comments-author-name mb-0">{{ $commentName }}</h6>
                                            <span class="post-date">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="comment-text mb-1">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-secondary mb-0 mt-3">
                    Belum ada komentar.
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('mobile/js/scrollindicator.js') }}"></script>
    @endpush
</div>
