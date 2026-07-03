<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        Dashboard Keuangan
    </x-slot>

    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
        $savingsRate = ($monthlyIncome ?? 0) > 0 ? round((($monthlyNet ?? 0) / $monthlyIncome) * 100) : 0;
        $expenseRatio = ($monthlyIncome ?? 0) > 0 ? min(100, round((($monthlyExpense ?? 0) / $monthlyIncome) * 100)) : 0;
        $chartMax = max(1, collect($chartData ?? [])->flatMap(fn ($item) => [$item->income ?? 0, $item->expense ?? 0])->max() ?? 1);
        $monthLabel = now()->translatedFormat('F Y');
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-xl shadow-emerald-100/70">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-sm font-bold text-emerald-700">Ringkasan {{ $monthLabel }}</p>
                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950">Keuangan usaha lebih mudah dibaca.</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Pantau saldo, pemasukan, pengeluaran, dan transaksi terbaru dalam satu layar yang ringan.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">
                        <span aria-hidden="true">+</span>
                        Catat Transaksi
                    </a>
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center rounded-full border border-emerald-100 bg-emerald-50 px-5 py-2.5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100">
                        Lihat Laporan
                    </a>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-600 p-4 text-white shadow-sm">
                    <p class="text-xs font-bold uppercase text-emerald-50/80">Saldo Total</p>
                    <p class="mt-2 text-2xl font-extrabold">{{ $formatRupiah($saldo ?? 0) }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-xs font-bold uppercase text-emerald-700">Pemasukan Bulan Ini</p>
                    <p class="mt-2 text-2xl font-extrabold text-emerald-900">{{ $formatRupiah($monthlyIncome ?? 0) }}</p>
                </div>
                <div class="rounded-2xl border border-rose-100 bg-rose-50 p-4">
                    <p class="text-xs font-bold uppercase text-rose-700">Pengeluaran Bulan Ini</p>
                    <p class="mt-2 text-2xl font-extrabold text-rose-900">{{ $formatRupiah($monthlyExpense ?? 0) }}</p>
                </div>
                <div class="rounded-2xl border border-lime-100 bg-lime-50 p-4">
                    <p class="text-xs font-bold uppercase text-lime-700">Arus Kas Bersih</p>
                    <p class="mt-2 text-2xl font-extrabold {{ ($monthlyNet ?? 0) >= 0 ? 'text-lime-900' : 'text-amber-700' }}">{{ $formatRupiah($monthlyNet ?? 0) }}</p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-[1.5rem] border border-emerald-100 bg-white p-5 shadow-sm shadow-emerald-100/60">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Tren 30 Hari</h2>
                        <p class="text-sm text-slate-500">Perbandingan pemasukan dan pengeluaran harian.</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-500">
                        <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Pemasukan</span>
                        <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-rose-500"></span>Pengeluaran</span>
                    </div>
                </div>

                <div class="mt-6 flex h-72 items-end gap-2 overflow-x-auto border-b border-emerald-50 pb-3">
                    @forelse ($chartData as $point)
                        @php
                            $incomeHeight = max(4, (($point->income ?? 0) / $chartMax) * 100);
                            $expenseHeight = max(4, (($point->expense ?? 0) / $chartMax) * 100);
                        @endphp
                        <div class="flex min-w-10 flex-1 flex-col items-center justify-end gap-2">
                            <div class="flex h-56 items-end gap-1">
                                <div class="w-3 rounded-t bg-emerald-500" style="height: {{ $incomeHeight }}%" title="Pemasukan {{ $formatRupiah($point->income ?? 0) }}"></div>
                                <div class="w-3 rounded-t bg-rose-500" style="height: {{ $expenseHeight }}%" title="Pengeluaran {{ $formatRupiah($point->expense ?? 0) }}"></div>
                            </div>
                            <span class="text-[11px] font-medium text-slate-400">{{ \Carbon\Carbon::parse($point->transaction_date)->format('d/m') }}</span>
                        </div>
                    @empty
                        <div class="flex h-full w-full items-center justify-center rounded-2xl bg-emerald-50 text-sm font-medium text-emerald-700">Belum ada data transaksi untuk ditampilkan.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-emerald-100 bg-white p-5 shadow-sm shadow-emerald-100/60">
                <h2 class="text-lg font-bold text-slate-900">Kesehatan Kas</h2>
                <p class="mt-1 text-sm text-slate-500">Indikator cepat untuk bulan berjalan.</p>

                <div class="mt-5 space-y-5">
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-600">Rasio pengeluaran</span>
                            <span class="font-bold text-slate-900">{{ $expenseRatio }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-emerald-50">
                            <div class="h-2 rounded-full bg-rose-500" style="width: {{ $expenseRatio }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-emerald-50 p-4">
                            <p class="text-xs font-semibold uppercase text-slate-400">Transaksi</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $transactionCount ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl bg-lime-50 p-4">
                            <p class="text-xs font-semibold uppercase text-slate-400">Margin</p>
                            <p class="mt-1 text-2xl font-bold {{ $savingsRate >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $savingsRate }}%</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Pengeluaran terbesar</h3>
                        <div class="mt-3 space-y-3">
                            @forelse ($expenseByCategory as $item)
                                <div>
                                    <div class="mb-1 flex justify-between gap-3 text-sm">
                                        <span class="truncate font-medium text-slate-600">{{ $item->category->name ?? 'Tanpa kategori' }}</span>
                                        <span class="font-semibold text-slate-900">{{ $formatRupiah($item->total) }}</span>
                                    </div>
                                    <div class="h-1.5 rounded-full bg-emerald-50">
                                        <div class="h-1.5 rounded-full bg-emerald-500" style="width: {{ $monthlyExpense > 0 ? min(100, ($item->total / $monthlyExpense) * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-2xl bg-emerald-50 p-3 text-sm font-medium text-emerald-700">Belum ada pengeluaran bulan ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-[1.5rem] border border-emerald-100 bg-white shadow-sm shadow-emerald-100/60">
            <div class="flex items-center justify-between border-b border-emerald-50 px-5 py-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h2>
                    <p class="text-sm text-slate-500">Aktivitas terakhir yang tercatat.</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900">Semua transaksi</a>
            </div>

            <div class="divide-y divide-emerald-50">
                @forelse ($recentTransactions as $trx)
                    <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-slate-900">{{ $trx->description }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $trx->transaction_date->translatedFormat('d M Y') }} · {{ $trx->category->name ?? 'Tanpa kategori' }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="font-bold {{ $trx->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">{{ $trx->isIncome() ? '+' : '-' }} {{ $formatRupiah($trx->amount) }}</p>
                            <a href="{{ route('transactions.edit', $trx) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900">Edit</a>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-sm text-slate-500">Mulai catat pemasukan atau pengeluaran pertama Anda.</div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
