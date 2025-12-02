<div>
    <div class="page-content-wrapper py-3">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0 newsten-title">Semua Halaman</h5>
                <span class="badge bg-light text-dark border">{{ $pages->total() }}</span>
            </div>
            <ul class="page-nav">
                @forelse($pages as $page)
                    <li><a href="{{ route('page.show', ['slug' => $page->slug]) }}">{{ $page->title }}<i class="ti ti-arrow-narrow-right"></i></a></li>
                @empty
                    <li class="text-muted">Belum ada halaman.</li>
                @endforelse
            </ul>
        </div>
    </div>

    @if($pages->hasPages())
        <div class="container mb-3">
            {{ $pages->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
