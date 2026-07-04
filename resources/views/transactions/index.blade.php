<x-app-layout>
    <x-slot name="title">Laporan Transaksi</x-slot>

    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
        $net = (float) $totalIncome - (float) $totalExpense;
    @endphp

    <div x-data="{ filtersOpen: window.matchMedia('(min-width: 768px)').matches }" class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold text-emerald-700">Laporan Keuangan</p>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Transaksi</h1>
                <p class="mt-1 text-sm text-slate-500">Cari, filter, dan audit semua pemasukan serta pengeluaran.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="button" @click="filtersOpen = ! filtersOpen" class="inline-flex items-center justify-center gap-2 rounded-full border border-emerald-100 bg-white px-5 py-2.5 text-sm font-bold text-emerald-700 shadow-sm shadow-emerald-100/60 transition hover:bg-emerald-50 md:hidden">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M4 6h16M7 12h10M10 18h4" stroke-linecap="round" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">
                    <span aria-hidden="true">+</span>
                    Catat Transaksi
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                <p class="text-xs font-bold uppercase text-emerald-700">Pemasukan</p>
                <p class="mt-2 text-2xl font-bold text-emerald-900">{{ $formatRupiah($totalIncome) }}</p>
            </div>
            <div class="rounded-xl border border-rose-100 bg-rose-50 p-5">
                <p class="text-xs font-bold uppercase text-rose-700">Pengeluaran</p>
                <p class="mt-2 text-2xl font-bold text-rose-900">{{ $formatRupiah($totalExpense) }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm shadow-emerald-100/60">
                <p class="text-xs font-bold uppercase text-slate-500">Hasil Filter</p>
                <p class="mt-2 text-2xl font-bold {{ $net >= 0 ? 'text-slate-900' : 'text-amber-700' }}">{{ $formatRupiah($net) }}</p>
            </div>
        </section>

        <form x-cloak x-show="filtersOpen" x-transition.opacity.duration.150ms method="GET" action="{{ route('transactions.index') }}" class="rounded-[1.5rem] border border-emerald-100 bg-white p-4 shadow-sm shadow-emerald-100/60">
            <div class="grid gap-3 md:grid-cols-6">
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-slate-500">Bulan</label>
                    <select name="month" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($selectedMonth == $m)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-slate-500">Tahun</label>
                    <select name="year" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach (range(now()->year, now()->year - 5) as $y)
                            <option value="{{ $y }}" @selected($selectedYear == $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-slate-500">Jenis</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua</option>
                        <option value="income" @selected($selectedType === 'income')>Pemasukan</option>
                        <option value="expense" @selected($selectedType === 'expense')>Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-slate-500">Kategori</label>
                    <select name="category_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-bold uppercase text-slate-500">Cari</label>
                    <div class="flex gap-2">
                        <input type="search" name="search" value="{{ $search }}" placeholder="Deskripsi, catatan, referensi" class="min-w-0 flex-1 rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <button type="submit" class="rounded-full bg-emerald-600 px-5 py-2 text-sm font-bold text-white transition hover:bg-emerald-700">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <section class="overflow-hidden rounded-[1.5rem] border border-emerald-100 bg-white shadow-sm shadow-emerald-100/60">
            @if ($transactions->isEmpty())
                <div class="px-6 py-16 text-center">
                    <p class="text-sm font-semibold text-slate-700">Belum ada transaksi untuk filter ini.</p>
                    <p class="mt-1 text-sm text-slate-500">Ubah filter atau catat transaksi baru.</p>
                    <a href="{{ route('transactions.create') }}" class="mt-4 inline-flex rounded-full bg-emerald-600 px-5 py-2 text-sm font-bold text-white">Catat Transaksi</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-500">Tanggal</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-500">Transaksi</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-500">Kategori</th>
                                <th class="px-5 py-3 text-right text-xs font-bold uppercase text-slate-500">Nominal</th>
                                <th class="px-5 py-3 text-right text-xs font-bold uppercase text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($transactions as $trx)
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-600">{{ $trx->transaction_date->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-4">
                                        <p class="max-w-md truncate text-sm font-semibold text-slate-900">{{ $trx->description }}</p>
                                        <p class="mt-1 max-w-md truncate text-xs text-slate-500">{{ $trx->reference_number ?: $trx->notes ?: 'Tidak ada catatan tambahan' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $trx->category->name ?? 'Tanpa kategori' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-bold {{ $trx->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">{{ $trx->isIncome() ? '+' : '-' }} {{ $formatRupiah($trx->amount) }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                        <a href="{{ route('transactions.show', $trx) }}" class="font-semibold text-slate-600 hover:text-slate-900">Detail</a>
                                        <span class="mx-2 text-slate-300">/</span>
                                        <a href="{{ route('transactions.edit', $trx) }}" class="font-bold text-emerald-700 hover:text-emerald-900">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </section>
    </div>
</x-app-layout>
