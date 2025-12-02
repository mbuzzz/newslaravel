<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Banner; // <--- Wajib Import Model Banner
use App\Models\Category;
use App\Models\Tag;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Home extends Component
{
    use WithPagination;

    public ?string $category = null;
    public ?string $tag = null;

    protected $queryString = ['category', 'tag'];

    public function mount(): void
    {
        $this->category = request()->query('category');
        $this->tag = request()->query('tag');
    }

    public function render()
    {
        $settings = app(GeneralSettings::class);
        $seo = app(SeoSettings::class);

        // 1. Ambil Berita (Pagination)
        $postsQuery = Post::query()
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->with(['category', 'author'])
            ->latest('published_at');

        $activeCategory = null;
        if ($this->category) {
            $activeCategory = Category::where('slug', $this->category)->first();

            if ($activeCategory) {
                $postsQuery->where('category_id', $activeCategory->id);
            }
        }

        $activeTag = null;
        if ($this->tag) {
            $activeTag = Tag::where('slug', $this->tag)->first();

            if ($activeTag) {
                $postsQuery->whereHas('tags', fn ($q) => $q->where('slug', $activeTag->slug));
            }
        }

        $heroPosts = (clone $postsQuery)->take(5)->get();
        $posts = $postsQuery->paginate(9)->withQueryString();

        // 2. Ambil Banner Hero (Slider)
        // Pastikan tabel banners sudah ada. Mengambil yang aktif.
        $heroBanners = Banner::where('type', 'hero')
            ->where('is_active', true)
            ->latest()
            ->get();

        // 3. Ambil Popup Banner
        $popupBanner = Banner::where('type', 'popup')
            ->where('is_active', true)
            ->latest()
            ->first();

        $categories = Category::where('is_active', true)
            ->withCount(['posts' => fn ($query) => $query->where('is_published', true)])
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->get();

        $trendingPosts = Post::query()
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->with(['category', 'author'])
            ->when($activeCategory, fn ($query) => $query->where('category_id', $activeCategory->id))
            ->when($activeTag, fn ($query) => $query->whereHas('tags', fn ($q) => $q->where('slug', $activeTag->slug)))
            ->orderByDesc('view_count')
            ->take(8)
            ->get();

        $editorialPosts = Post::query()
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->where('is_editor_pick', true)
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(10)
            ->get();

        $topTags = Tag::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(10)
            ->get();

        return view('livewire.home', [
            'posts' => $posts,
            'heroBanners' => $heroBanners, // Mengirim data ke view
            'popupBanner' => $popupBanner, // Mengirim data ke view (Solusi Error Anda)
            'activeCategory' => $activeCategory,
            'activeTag' => $activeTag,
            'categories' => $categories,
            'trendingPosts' => $trendingPosts,
            'topTags' => $topTags,
            'heroPosts' => $heroPosts,
            'editorialPosts' => $editorialPosts,
        ])->layout('components.layouts.mobile', [
            'title' => $seo->meta_title_home ?? 'Beranda',
            'description' => $seo->meta_description_home ?? $settings->site_description,
            'keywords' => $seo->meta_keywords_global,
            'image' => $settings->site_logo ? Storage::url($settings->site_logo) : null,
        ]);
    }
}
