<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Kategori bisa milik user tertentu (custom) atau null (default sistem)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');                    // Nama kategori
            $table->enum('type', ['income', 'expense']); // Jenis: pemasukan atau pengeluaran
            $table->string('icon')->nullable();        // Ikon opsional (emoji atau class icon)
            $table->string('color', 7)->default('#6366f1'); // Warna hex untuk UI
            $table->boolean('is_default')->default(false);  // Kategori bawaan sistem
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
