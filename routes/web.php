<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\ShowPost;
use App\Livewire\ShowPage;
use App\Livewire\Archive;
use App\Livewire\Trending;
use App\Livewire\Bookmarks;
use App\Livewire\Search;
use App\Livewire\AllPages;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\ReaderProfile;

Route::get('/', Home::class)->name('home');

Route::get('/kategori/{slug}', Archive::class)->name('category.archive');
Route::get('/trending', Trending::class)->name('trending');
Route::get('/bookmark', Bookmarks::class)->name('bookmarks');
Route::get('/search', Search::class)->name('search');
Route::get('/pages', AllPages::class)->name('pages.index');
Route::get('/masuk', Login::class)->name('mobile.login');
Route::get('/daftar', Register::class)->name('mobile.register');
Route::get('/profil', ReaderProfile::class)->name('mobile.profile');
Route::get('/page/{slug}', ShowPage::class)->name('page.show');

// --- UPDATE ROUTE POST ---
// Struktur: /{kategori}/{slug}
// Contoh: /teknologi/cara-belajar-coding
Route::get('/{category}/{slug}', ShowPost::class)->name('post.show');
