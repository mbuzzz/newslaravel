<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\Bookmark;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification; // Import Notifikasi Filament
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShowPost extends Component
{
    public Post $post;
    
    // State untuk Interaksi
    public bool $isLiked = false;
    public bool $isSaved = false;
    public int $likesCount = 0;
    
    // State untuk Komentar
    public $commentContent = '';

    public function mount($category, $slug)
    {
        // 1. Cari Post
        $this->post = Post::with(['author', 'category', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // 2. Hitung View (Anti Spam Session)
        $sessionKey = 'viewed_post_' . $this->post->id;
        if (!Session::has($sessionKey)) {
            $this->post->increment('view_count');
            Session::put($sessionKey, true);
        }

        // 3. Cek Status Like & Save (Jika user login)
        if (Auth::check()) {
            $userId = Auth::id();
            $this->isLiked = PostLike::where('user_id', $userId)->where('post_id', $this->post->id)->exists();
            $this->isSaved = Bookmark::where('user_id', $userId)->where('post_id', $this->post->id)->exists();
        }

        // 4. Hitung Total Like
        $this->likesCount = PostLike::where('post_id', $this->post->id)->count();
    }

    // --- AKSI LIKE ---
    public function toggleLike()
    {
        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        $userId = Auth::id();

        if ($this->isLiked) {
            // Unlike
            PostLike::where('user_id', $userId)->where('post_id', $this->post->id)->delete();
            $this->isLiked = false;
            $this->likesCount--;
        } else {
            // Like
            PostLike::create(['user_id' => $userId, 'post_id' => $this->post->id]);
            $this->isLiked = true;
            $this->likesCount++;
        }
    }

    // --- AKSI SAVE (BOOKMARK) ---
    public function toggleSave()
    {
        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        $userId = Auth::id();

        if ($this->isSaved) {
            // Unsave
            Bookmark::where('user_id', $userId)->where('post_id', $this->post->id)->delete();
            $this->isSaved = false;
            
            Notification::make()->title('Artikel dihapus dari simpanan')->warning()->send();
        } else {
            // Save
            Bookmark::create(['user_id' => $userId, 'post_id' => $this->post->id]);
            $this->isSaved = true;

            Notification::make()->title('Artikel berhasil disimpan')->success()->send();
        }
    }

    // --- AKSI KOMENTAR ---
    public function submitComment()
    {
        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        $this->validate([
            'commentContent' => 'required|min:3|max:500',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'content' => $this->commentContent,
            'is_approved' => true, // Ubah jadi false jika ingin moderasi dulu
        ]);

        $this->commentContent = ''; // Reset form

        Notification::make()
            ->title('Komentar terkirim!')
            ->success()
            ->send();
    }

    public function render()
    {
        $metaDescription = $this->post->meta_description ?? Str::limit(strip_tags($this->post->content), 160);
        $metaKeywords = $this->post->meta_keywords ?? null;
        $metaImage = $this->post->thumbnail ? Storage::url($this->post->thumbnail) : null;
        $shareUrl = url()->current();

        $relatedPosts = Post::where('is_published', true)
            ->where('published_at', '<=', now())
            ->where('id', '!=', $this->post->id)
            ->when($this->post->category_id, fn ($query) => $query->where('category_id', $this->post->category_id))
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->take(6)
            ->get();

        return view('livewire.show-post', [
            // Kirim komentar yang sudah diapprove
            'comments' => $this->post->comments()->where('is_approved', true)->latest()->get(),
            'relatedPosts' => $relatedPosts,
            'shareUrl' => $shareUrl,
        ])
        ->layout('components.layouts.mobile', [
            'title' => $this->post->meta_title ?? $this->post->title,
            'description' => $metaDescription,
            'keywords' => $metaKeywords,
            'image' => $metaImage,
            'published_time' => optional($this->post->published_at)?->toIso8601String(),
            'author' => $this->post->custom_author ?? $this->post->author->name,
            'showBackButton' => true,
            'headerTitle' => 'Detail Artikel',
            'backUrl' => url()->previous() !== url()->current() ? url()->previous() : route('home'),
        ]);
    }
}
