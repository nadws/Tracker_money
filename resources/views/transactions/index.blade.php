<x-app-layout>
    <x-slot name="title">Laporan Transaksi</x-slot>

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Transaksi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Riwayat dan laporan keuangan usaha Anda</p>
        </div>
        <a href="{{ route('transactions.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Catat Transaksi
        </a>
    </div>

    {{-- ── Filter Bulan ─────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('transactions.index') }}"
        class="bg-white rounded-xl border border-gray-200 p-4 mb-5 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
            <select name="month"
                class="rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
            <select name="year"
                class="rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach (range(now()->year, now()->year - 3) as $y)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
            Tampilkan
        </button>
    </form>

    {{-- ── Ringkasan Bulan Ini ───────────────────────────────────── --}}
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div class="bg-green-50 border border-green-100 rounded-xl p-4">
            <p class="text-xs font-medium text-green-600 mb-1">Pemasukan</p>
            <p class="text-lg font-bold text-green-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-4">
            <p class="text-xs font-medium text-red-500 mb-1">Pengeluaran</p>
            <p class="text-lg font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
            @php $net = $totalIncome - $totalExpense; @endphp
            <p class="text-xs font-medium text-indigo-500 mb-1">{{ $net >= 0 ? 'Laba' : 'Rugi' }}</p>
            <p class="text-lg font-bold {{ $net >= 0 ? 'text-indigo-700' : 'text-orange-600' }}">
                Rp {{ number_format(abs($net), 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- ── Tabel Transaksi ──────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @if ($transactions->isEmpty())
            <div class="py-16 text-center">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-400 text-sm">Belum ada transaksi di bulan ini.</p>
                <a href="{{ route('transactions.create') }}"
                    class="mt-2 inline-block text-sm text-indigo-600 hover:underline">
                    Catat transaksi →
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Deskripsi</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Kategori</th>
                            <th
                                class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nominal</th>
                            <th
                                class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($transactions as $trx)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $trx->transaction_date->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="text-sm font-medium text-gray-800">{{ $trx->description }}</div>
                                    @if ($trx->notes)
                                        <div class="text-xs text-gray-400 truncate max-w-xs">{{ $trx->notes }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 hidden sm:table-cell">
                                    <span
                                        class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ $trx->category->icon ?? '' }} {{ $trx->category->name }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <span
                                        class="text-sm font-semibold {{ $trx->isIncome() ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $trx->isIncome() ? '+' : '−' }} Rp
                                        {{ number_format($trx->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('transactions.edit', $trx) }}"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                        <span class="text-gray-300">|</span>
                                        <form method="POST" action="{{ route('transactions.destroy', $trx) }}" x-data
                                            @submit.prevent="if(confirm('Hapus transaksi ini?')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-red-400 hover:text-red-600 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($transactions->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
