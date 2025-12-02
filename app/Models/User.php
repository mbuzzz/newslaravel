<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar; // <--- 1. Import Interface Avatar
use Illuminate\Support\Facades\Storage; // <--- 2. Import Storage

class User extends Authenticatable implements FilamentUser, HasAvatar // <--- 3. Tambahkan implements
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url', // Pastikan kolom ini fillable
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super_admin');
    }

    // <--- 4. Tambahkan fungsi ini agar Filament tahu lokasi gambar avatarnya
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }
}
