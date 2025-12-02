<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class AllPages extends Component
{
    use WithPagination;

    public function render()
    {
        $pages = Page::query()
            ->where('is_active', true)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('livewire.all-pages', [
            'pages' => $pages,
        ])->layout('components.layouts.mobile', [
            'title' => 'Semua Halaman',
            'description' => 'Daftar halaman statis',
            'headerTitle' => 'Semua Halaman',
            'showBackButton' => true,
            'backUrl' => route('home'),
        ]);
    }
}
