<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Trending extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = Post::query()
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->with(['category', 'author'])
            ->orderByDesc('view_count')
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('livewire.trending', [
            'posts' => $posts,
        ])->layout('components.layouts.mobile', [
            'title' => 'Trending',
            'description' => 'Artikel trending berdasarkan jumlah pembaca',
            'headerTitle' => 'Trending',
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
