<x-app-layout>
    <x-slot name="title">Catat Transaksi</x-slot>

    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('transactions.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Catat Transaksi</h1>
                <p class="text-sm text-gray-500 mt-0.5">Tambah pemasukan atau pengeluaran baru</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('transactions.store') }}" x-data="{
            type: '{{ old('type', 'income') }}',
            // Filter kategori berdasarkan type yang dipilih
            get filteredCategories() {
                return this.type;
            }
        }"
            class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            @csrf

            {{-- ── Jenis Transaksi ─────────────────────────────── --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Transaksi</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" x-model="type" class="sr-only">
                        <div :class="type === 'income'
                            ?
                            'border-green-500 bg-green-50 text-green-700' :
                            'border-gray-200 text-gray-600 hover:border-gray-300'"
                            class="border-2 rounded-lg px-4 py-3 text-center transition-colors">
                            <span class="text-xl block mb-1">📈</span>
                            <span class="text-sm font-semibold">Pemasukan</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" x-model="type" class="sr-only">
                        <div :class="type === 'expense'
                            ?
                            'border-red-400 bg-red-50 text-red-700' :
                            'border-gray-200 text-gray-600 hover:border-gray-300'"
                            class="border-2 rounded-lg px-4 py-3 text-center transition-colors">
                            <span class="text-xl block mb-1">📉</span>
                            <span class="text-sm font-semibold">Pengeluaran</span>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Kategori ────────────────────────────────────── --}}
            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Kategori
                </label>

                {{-- Tampilkan kategori pemasukan --}}
                {{-- :disabled agar select yang hidden tidak ikut tersubmit --}}
                <div x-show="type === 'income'">
                    <select name="category_id" id="category_id" :disabled="type !== 'income'"
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories['income'] ?? collect() as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id && old('type') === 'income' ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tampilkan kategori pengeluaran --}}
                <div x-show="type === 'expense'">
                    <select name="category_id" :disabled="type !== 'expense'"
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories['expense'] ?? collect() as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id && old('type') === 'expense' ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @error('category_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Nominal ─────────────────────────────────────── --}}
            <div>
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nominal (Rp)
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-500">Rp</span>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="1"
                        step="1" placeholder="0"
                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                                  @error('amount') border-red-400 @enderror">
                </div>
                @error('amount')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Tanggal ──────────────────────────────────────── --}}
            <div>
                <label for="transaction_date" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Tanggal Transaksi
                </label>
                <input type="date" id="transaction_date" name="transaction_date"
                    value="{{ old('transaction_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                              @error('transaction_date') border-red-400 @enderror">
                @error('transaction_date')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Deskripsi ────────────────────────────────────── --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Deskripsi
                </label>
                <input type="text" id="description" name="description" value="{{ old('description') }}"
                    placeholder="Contoh: Beli tepung 5 kg, Bayar listrik Januari" maxlength="255"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                              @error('description') border-red-400 @enderror">
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Catatan Tambahan (opsional) ──────────────────── --}}
            <div>
                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea id="notes" name="notes" rows="2" placeholder="Informasi tambahan jika ada..."
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                                 resize-none @error('notes') border-red-400 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Nomor Referensi (opsional) ──────────────────── --}}
            <div>
                <label for="reference_number" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nomor Referensi / Struk <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input type="text" id="reference_number" name="reference_number"
                    value="{{ old('reference_number') }}" placeholder="Nomor struk, invoice, atau faktur"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- ── Tombol Submit ─────────────────────────────────── --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('transactions.index') }}"
                    class="flex-1 text-center px-4 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition-colors">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
