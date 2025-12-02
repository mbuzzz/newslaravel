<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public string $q = '';
    public ?string $category = null;

    protected $queryString = ['q', 'category'];

    public function updated($field)
    {
        if (in_array($field, ['q', 'category'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $posts = Post::query()
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->when($this->q, fn ($query) => $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->q . '%')
                    ->orWhere('content', 'like', '%' . $this->q . '%');
            }))
            ->when($this->category, function ($query) {
                $category = Category::where('slug', $this->category)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            })
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('livewire.search', [
            'posts' => $posts,
            'categories' => $categories,
        ])->layout('components.layouts.mobile', [
            'title' => 'Cari Artikel',
            'description' => 'Pencarian artikel',
            'headerTitle' => 'Pencarian',
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
