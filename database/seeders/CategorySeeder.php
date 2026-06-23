<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Kategori default untuk UMKM Indonesia.
     * user_id = null berarti kategori sistem (tersedia untuk semua user).
     */
    public function run(): void
    {
        $categories = [
            // ── Pemasukan ────────────────────────────────────────
            ['name' => 'Penjualan Produk',    'type' => 'income',  'icon' => '🛍️', 'color' => '#10b981'],
            ['name' => 'Penjualan Jasa',      'type' => 'income',  'icon' => '💼', 'color' => '#06b6d4'],
            ['name' => 'Pendapatan Lainnya',  'type' => 'income',  'icon' => '💰', 'color' => '#84cc16'],

            // ── Pengeluaran ──────────────────────────────────────
            ['name' => 'Beli Bahan Baku',     'type' => 'expense', 'icon' => '📦', 'color' => '#f59e0b'],
            ['name' => 'Bayar Listrik',       'type' => 'expense', 'icon' => '⚡', 'color' => '#eab308'],
            ['name' => 'Bayar Air',           'type' => 'expense', 'icon' => '💧', 'color' => '#3b82f6'],
            ['name' => 'Bayar Internet',      'type' => 'expense', 'icon' => '📶', 'color' => '#8b5cf6'],
            ['name' => 'Gaji Karyawan',       'type' => 'expense', 'icon' => '👥', 'color' => '#ec4899'],
            ['name' => 'Sewa Tempat',         'type' => 'expense', 'icon' => '🏪', 'color' => '#f97316'],
            ['name' => 'Ongkos Kirim',        'type' => 'expense', 'icon' => '🚚', 'color' => '#14b8a6'],
            ['name' => 'Biaya Marketing',     'type' => 'expense', 'icon' => '📣', 'color' => '#a855f7'],
            ['name' => 'Peralatan & Mesin',   'type' => 'expense', 'icon' => '🔧', 'color' => '#64748b'],
            ['name' => 'Pengeluaran Lainnya', 'type' => 'expense', 'icon' => '📝', 'color' => '#6b7280'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'user_id'    => null, // kategori default sistem
                'is_default' => true,
                ...$cat,
            ]);
        }
    }
}
