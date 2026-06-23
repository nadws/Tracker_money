<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->enum('type', ['income', 'expense']);  // Pemasukan atau pengeluaran
            $table->decimal('amount', 15, 2);             // Nominal (max 999 triliun)
            $table->date('transaction_date');             // Tanggal transaksi
            $table->string('description');                // Deskripsi singkat
            $table->text('notes')->nullable();            // Catatan tambahan opsional
            $table->string('reference_number')->nullable(); // Nomor referensi (struk, invoice)
            $table->timestamps();

            // Index untuk performa query laporan per bulan
            $table->index(['user_id', 'transaction_date']);
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
