@php
    $user = auth()->user();
    $name = $user?->name ?? 'Admin';
@endphp

<div class="rounded-2 p-4 mb-4" style="background: radial-gradient(circle at 20% 20%, #0ea5e9, #0f172a 40%), radial-gradient(circle at 80% 10%, #8b5cf6, #0f172a 35%), #0f172a; color:#e2e8f0; box-shadow: 0 15px 40px rgba(0,0,0,0.35);">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="text-uppercase fw-bold small mb-2" style="letter-spacing: 2px; color:#38bdf8;">Control Room</div>
            <h3 class="fw-bold mb-1" style="color:#f8fafc;">Welcome, {{ $name }}!</h3>
            <p class="mb-0" style="color:#cbd5e1;">Pantau performa artikel, interaksi pembaca, dan konfigurasi situs dari satu layar.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('filament.admin.resources.posts.index') }}" class="btn btn-primary btn-sm fw-bold">Kelola Artikel</a>
            <a href="{{ route('filament.admin.pages.manage-site-settings') }}" class="btn btn-outline-light btn-sm fw-bold">Pengaturan Situs</a>
        </div>
    </div>
</div>
