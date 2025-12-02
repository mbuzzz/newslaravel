<?php

namespace App\Livewire;

use App\Models\Bookmark;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReaderProfile extends Component
{
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('mobile.login');
        }
    }

    public function render()
    {
        $user = Auth::user();

        $bookmarks = Bookmark::query()
            ->where('user_id', $user->id)
            ->with(['post.category'])
            ->whereHas('post', fn ($q) => $q->where('is_published', true))
            ->latest()
            ->take(10)
            ->get();

        $bookmarkCount = $bookmarks->count();
        $likesCount = PostLike::where('user_id', $user->id)->count();
        $postsCount = \App\Models\Post::where('user_id', $user->id)->count();

        return view('livewire.reader-profile', [
            'user' => $user,
            'bookmarks' => $bookmarks,
            'bookmarkCount' => $bookmarkCount,
            'likesCount' => $likesCount,
            'postsCount' => $postsCount,
        ])->layout('components.layouts.mobile', [
            'title' => 'Profil',
            'description' => 'Profil pembaca',
            'headerTitle' => 'Profil',
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
