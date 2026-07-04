<x-app-layout>
    <x-slot name="title">Laporan Transaksi</x-slot>
    <x-slot name="mobileFullScreen">true</x-slot>

    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
        $net = (float) $totalIncome - (float) $totalExpense;
        $selectedDate = \Carbon\Carbon::create((int) $selectedYear, (int) $selectedMonth, 1);
        $monthTabs = collect(range(3, 0))->map(fn ($offset) => $selectedDate->copy()->subMonths($offset));
        $mobileGroups = $transactions->getCollection()->groupBy(fn ($trx) => $trx->transaction_date->toDateString());
        $queryBase = request()->except(['month', 'year', 'page']);
    @endphp

    <div class="min-h-screen bg-emerald-500 md:hidden">
        <section class="relative overflow-hidden bg-gradient-to-b from-emerald-400 via-emerald-500 to-green-600 px-6 pb-20 pt-7 text-white">
            <div class="pointer-events-none absolute inset-0 opacity-30">
                <div class="absolute left-[-18%] top-24 h-32 w-[140%] rotate-[-9deg] rounded-[100%] border-t-4 border-white/40"></div>
                <div class="absolute left-[-24%] top-44 h-40 w-[150%] rotate-[-11deg] rounded-[100%] border-t-4 border-white/25"></div>
            </div>

            <div class="relative flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex h-11 w-11 items-center justify-center rounded-full text-white/95 active:bg-white/10" aria-label="Kembali">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                        <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="{{ route('categories.index') }}" class="flex h-11 w-11 items-center justify-center rounded-full text-white/95 active:bg-white/10" aria-label="Pengaturan kategori">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" />
                        <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-.4-1.1 1.7 1.7 0 0 0-1-.6 1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.1-.4 1.7 1.7 0 0 0 .6-1 1.7 1.7 0 0 0-.34-1.88l-.06-.06A2 2 0 1 1 7.22 3.4l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 .4 1.1 1.7 1.7 0 0 0 1 .6 1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.26.36.6.7 1 .9.34.16.72.25 1.1.25H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.1.4 1.7 1.7 0 0 0-.41.45Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>

            <div class="relative mt-2 text-center">
                <div class="mx-auto flex h-16 w-24 items-center justify-center rounded-lg bg-white/20 shadow-lg ring-1 ring-white/30 backdrop-blur">
                    <span class="text-xs font-extrabold uppercase tracking-wide">Akunting<br>Pro</span>
                </div>
                <h1 class="mt-4 text-2xl font-extrabold tracking-tight">Transaksi Usaha</h1>
                <p class="mt-1 text-lg font-medium text-white/90">{{ $selectedDate->translatedFormat('F Y') }}</p>
                <p class="mt-5 text-4xl font-extrabold tracking-tight">{{ $formatRupiah($net) }}<sup class="text-base">00</sup></p>
            </div>

            <div class="relative mt-9 grid grid-cols-4 gap-4 text-center">
                <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="space-y-2">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/90 text-emerald-600 shadow-lg">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 19V5M6 11l6-6 6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="block text-xs font-semibold leading-tight">Tambah<br>Pemasukan</span>
                </a>
                <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="space-y-2">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/90 text-emerald-600 shadow-lg">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 5v14M18 13l-6 6-6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="block text-xs font-semibold leading-tight">Tambah<br>Pengeluaran</span>
                </a>
                <a href="{{ route('categories.index') }}" class="space-y-2">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/90 text-emerald-600 shadow-lg">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M4 7h16M4 12h16M4 17h10" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="block text-xs font-semibold leading-tight">Kelola<br>Kategori</span>
                </a>
                <a href="{{ route('dashboard') }}" class="space-y-2">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/90 text-emerald-600 shadow-lg">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M4 19V9M10 19V5M16 19v-7M22 19H2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="block text-xs font-semibold leading-tight">Lihat<br>Dashboard</span>
                </a>
            </div>
        </section>

        <section x-data="{ filters: false }" class="-mt-6 rounded-t-[2rem] bg-white pb-8 shadow-2xl">
            <div class="mx-auto pt-3">
                <div class="mx-auto h-1.5 w-20 rounded-full bg-slate-500/80"></div>
            </div>

            <div class="flex items-center justify-between px-5 pt-7">
                <h2 class="text-2xl font-extrabold text-slate-700">Transaksi</h2>
                <button type="button" @click="filters = ! filters" class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 active:bg-emerald-100" aria-label="Buka filter">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M4 6h16M7 12h10M10 18h4" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            @if (session('success'))
                <div class="mx-5 mt-4 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-6 flex items-center gap-1 overflow-x-auto border-b border-slate-100 px-2">
                @foreach ($monthTabs as $tab)
                    <a href="{{ route('transactions.index', array_merge($queryBase, ['month' => $tab->month, 'year' => $tab->year])) }}"
                        class="relative min-w-20 px-4 pb-3 text-center text-xl font-medium {{ $tab->month === (int) $selectedMonth && $tab->year === (int) $selectedYear ? 'text-slate-950' : 'text-slate-500' }}">
                        {{ $tab->translatedFormat('M') }}
                        @if ($tab->month === (int) $selectedMonth && $tab->year === (int) $selectedYear)
                            <span class="absolute inset-x-3 bottom-0 h-1.5 rounded-t-full bg-emerald-500"></span>
                        @endif
                    </a>
                @endforeach

                <button type="button" @click="filters = ! filters" class="ml-auto flex h-11 w-11 shrink-0 items-center justify-center border-l border-slate-100 text-emerald-600" aria-label="Buka pencarian transaksi">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                        <path d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <form x-cloak x-show="filters" x-transition.opacity.duration.150ms method="GET" action="{{ route('transactions.index') }}" class="mx-5 mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <div class="grid gap-3">
                    <div class="grid grid-cols-2 gap-3">
                        <select name="month" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" @selected($selectedMonth == $m)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach (range(now()->year, now()->year - 5) as $y)
                                <option value="{{ $y }}" @selected($selectedYear == $y)>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="type" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua jenis</option>
                        <option value="income" @selected($selectedType === 'income')>Pemasukan</option>
                        <option value="expense" @selected($selectedType === 'expense')>Pengeluaran</option>
                    </select>
                    <select name="category_id" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari deskripsi, catatan, referensi" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <button type="submit" class="rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-extrabold text-white">Terapkan</button>
                </div>
            </form>

            <div class="divide-y divide-slate-100">
                @forelse ($mobileGroups as $date => $items)
                    <div>
                        <p class="px-5 pb-2 pt-6 text-lg font-medium text-slate-400">{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</p>

                        @foreach ($items as $trx)
                            <a href="{{ route('transactions.show', $trx) }}" class="grid grid-cols-[2.5rem_minmax(0,1fr)_auto] gap-2 px-4 py-4 active:bg-slate-50">
                                <span class="mt-1 flex h-9 w-9 items-center justify-center rounded-xl text-emerald-500">
                                    @if ($trx->isIncome())
                                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <path d="M12 19V5M6 11l6-6 6 6" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5 21h14" stroke-linecap="round" />
                                        </svg>
                                    @else
                                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <path d="M12 5v14M18 13l-6 6-6-6" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5 21h14" stroke-linecap="round" />
                                        </svg>
                                    @endif
                                </span>
                                <span class="min-w-0">
                                    <span class="block truncate text-lg font-extrabold text-slate-950">{{ $trx->category->name ?? $trx->description }}</span>
                                    <span class="mt-1 line-clamp-2 block text-sm leading-snug text-slate-500">
                                        {{ $trx->description }}
                                        @if ($trx->reference_number)
                                            <br>{{ $trx->reference_number }}
                                        @elseif ($trx->notes)
                                            <br>{{ $trx->notes }}
                                        @endif
                                    </span>
                                </span>
                                <span class="max-w-[8.75rem] whitespace-nowrap pt-1 text-right text-base font-extrabold {{ $trx->isIncome() ? 'text-emerald-600' : 'text-slate-950' }}">
                                    {{ $trx->isIncome() ? '+' : '-' }} {{ $formatRupiah($trx->amount) }}<sup class="text-xs">00</sup>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-base font-bold text-slate-700">Belum ada transaksi bulan ini.</p>
                        <p class="mt-1 text-sm text-slate-500">Catat transaksi pertama atau ubah filter pencarian.</p>
                        <a href="{{ route('transactions.create') }}" class="mt-5 inline-flex rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-extrabold text-white">Catat Transaksi</a>
                    </div>
                @endforelse
            </div>

            @if ($transactions->hasPages())
                <div class="px-5 py-5">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        </section>
    </div>

    <div class="hidden space-y-6 md:block">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold text-emerald-700">Laporan Keuangan</p>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Transaksi</h1>
                <p class="mt-1 text-sm text-slate-500">Cari, filter, dan audit semua pemasukan serta pengeluaran.</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">
                <span aria-hidden="true">+</span>
                Catat Transaksi
            </a>
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

        <form method="GET" action="{{ route('transactions.index') }}" class="rounded-[1.5rem] border border-emerald-100 bg-white p-4 shadow-sm shadow-emerald-100/60">
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
