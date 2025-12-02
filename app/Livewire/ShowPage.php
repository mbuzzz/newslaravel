<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Str;

class ShowPage extends Component
{
    public Page $page;

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.show-page')
            ->layout('components.layouts.mobile', [
                'title' => $this->page->title,
                'description' => Str::limit(strip_tags($this->page->content), 160),
                'showBackButton' => true,
                'headerTitle' => $this->page->title,
                'backUrl' => route('home'),
            ]);
    }
}
