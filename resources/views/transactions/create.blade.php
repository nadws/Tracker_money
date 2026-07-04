<x-app-layout>
    <x-slot name="title">Catat Transaksi</x-slot>

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-emerald-700">Input Baru</p>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Catat Transaksi</h1>
                <p class="mt-1 text-sm text-slate-500">Rekam pemasukan atau pengeluaran dengan detail yang mudah diaudit.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900">Kembali</a>
        </div>

        <form method="POST" action="{{ route('transactions.store') }}" x-data="{ type: @js(old('type', request('type', 'income'))) }" class="rounded-[1.5rem] border border-emerald-100 bg-white p-6 shadow-sm shadow-emerald-100/60">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Jenis Transaksi</label>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="cursor-pointer rounded-xl border p-4 transition" :class="type === 'income' ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="type" value="income" x-model="type" class="sr-only">
                            <span class="block text-sm font-bold text-slate-900">Pemasukan</span>
                            <span class="mt-1 block text-xs text-slate-500">Uang masuk dari penjualan, jasa, atau sumber lain.</span>
                        </label>
                        <label class="cursor-pointer rounded-xl border p-4 transition" :class="type === 'expense' ? 'border-rose-300 bg-rose-50' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="type" value="expense" x-model="type" class="sr-only">
                            <span class="block text-sm font-bold text-slate-900">Pengeluaran</span>
                            <span class="mt-1 block text-xs text-slate-500">Biaya operasional, pembelian, dan pembayaran usaha.</span>
                        </label>
                    </div>
                    @error('type')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Kategori</label>
                        <div x-show="type === 'income'">
                            <select name="category_id" :disabled="type !== 'income'" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories['income'] ?? collect() as $cat)
                                    <option value="{{ $cat->id }}" @selected((string) old('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-show="type === 'expense'">
                            <select name="category_id" :disabled="type !== 'expense'" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories['expense'] ?? collect() as $cat)
                                    <option value="{{ $cat->id }}" @selected((string) old('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="transaction_date" class="mb-1.5 block text-sm font-bold text-slate-700">Tanggal</label>
                        <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('transaction_date')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="amount" class="mb-1.5 block text-sm font-bold text-slate-700">Nominal</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-500">Rp</span>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="1" step="1" placeholder="0" class="w-full rounded-xl border-slate-200 pl-10 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    @error('amount')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-bold text-slate-700">Deskripsi</label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}" maxlength="255" placeholder="Contoh: Penjualan tunai, bayar listrik, beli bahan baku" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('description')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="reference_number" class="mb-1.5 block text-sm font-bold text-slate-700">Nomor Referensi</label>
                        <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" maxlength="100" placeholder="Invoice, struk, atau kode transfer" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label for="notes" class="mb-1.5 block text-sm font-bold text-slate-700">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Opsional" class="w-full resize-none rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('transactions.index') }}" class="inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batal</a>
                    <button type="submit" class="inline-flex justify-center rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</x-app-layout>
