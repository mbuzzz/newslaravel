<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Nama banner (untuk internal admin)
            $table->string('image'); // File gambar
            $table->string('url')->nullable(); // Link jika diklik
            
            // Jenis Banner
            $table->enum('type', ['hero', 'popup', 'sidebar'])->default('hero');
            
            // Status & Jadwal
            $table->boolean('is_active')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};