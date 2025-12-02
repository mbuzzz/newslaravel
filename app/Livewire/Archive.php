<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Archive extends Component
{
    use WithPagination;

    public Category $category;

    public function mount(string $slug): void
    {
        $this->category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function render()
    {
        $posts = Post::query()
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->where('category_id', $this->category->id)
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('livewire.archive', [
            'posts' => $posts,
        ])->layout('components.layouts.mobile', [
            'title' => 'Kategori: ' . $this->category->name,
            'description' => 'Arsip artikel ' . $this->category->name,
            'headerTitle' => $this->category->name,
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
