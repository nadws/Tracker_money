<x-app-layout>
    <x-slot name="title">Detail Transaksi</x-slot>

    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    @endphp

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-emerald-700">Detail Transaksi</p>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">{{ $transaction->description }}</h1>
                <p class="mt-1 text-sm text-slate-500">Dicatat pada {{ $transaction->created_at->translatedFormat('d M Y H:i') }}</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900">Kembali</a>
        </div>

        <section class="overflow-hidden rounded-[1.5rem] border border-emerald-100 bg-white shadow-sm shadow-emerald-100/60">
            <div class="border-b border-slate-100 p-6">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $transaction->isIncome() ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $transaction->isIncome() ? 'Pemasukan' : 'Pengeluaran' }}
                </span>
                <p class="mt-4 text-4xl font-bold {{ $transaction->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $transaction->isIncome() ? '+' : '-' }} {{ $formatRupiah($transaction->amount) }}
                </p>
            </div>

            <dl class="grid gap-px bg-slate-100 sm:grid-cols-2">
                <div class="bg-white p-5">
                    <dt class="text-xs font-bold uppercase text-slate-500">Tanggal</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $transaction->transaction_date->translatedFormat('d M Y') }}</dd>
                </div>
                <div class="bg-white p-5">
                    <dt class="text-xs font-bold uppercase text-slate-500">Kategori</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $transaction->category->name ?? 'Tanpa kategori' }}</dd>
                </div>
                <div class="bg-white p-5">
                    <dt class="text-xs font-bold uppercase text-slate-500">Referensi</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $transaction->reference_number ?: '-' }}</dd>
                </div>
                <div class="bg-white p-5">
                    <dt class="text-xs font-bold uppercase text-slate-500">Terakhir diperbarui</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $transaction->updated_at->translatedFormat('d M Y H:i') }}</dd>
                </div>
                <div class="bg-white p-5 sm:col-span-2">
                    <dt class="text-xs font-bold uppercase text-slate-500">Catatan</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $transaction->notes ?: 'Tidak ada catatan tambahan.' }}</dd>
                </div>
            </dl>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 p-5 sm:flex-row sm:justify-end">
                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg border border-rose-200 px-4 py-2.5 text-sm font-bold text-rose-700 transition hover:bg-rose-50 sm:w-auto">Hapus</button>
                </form>
                <a href="{{ route('transactions.edit', $transaction) }}" class="inline-flex justify-center rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">Edit Transaksi</a>
            </div>
        </section>
    </div>
</x-app-layout>
