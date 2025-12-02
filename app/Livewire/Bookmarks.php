<?php

namespace App\Livewire;

use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Bookmarks extends Component
{
    use WithPagination;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect('/admin/login');
        }
    }

    public function render()
    {
        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        $bookmarks = Bookmark::query()
            ->where('user_id', Auth::id())
            ->with(['post.category', 'post.author'])
            ->whereHas('post', fn ($q) => $q->where('is_published', true))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('livewire.bookmarks', [
            'bookmarks' => $bookmarks,
        ])->layout('components.layouts.mobile', [
            'title' => 'Bookmark',
            'description' => 'Artikel yang kamu simpan',
            'headerTitle' => 'Bookmark',
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
