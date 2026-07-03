<x-app-layout>
    <x-slot name="title">Edit Transaksi</x-slot>

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-emerald-700">Perbarui Data</p>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Edit Transaksi</h1>
                <p class="mt-1 text-sm text-slate-500">Pastikan nominal, kategori, dan tanggal sudah sesuai.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900">Kembali</a>
        </div>

        <form method="POST" action="{{ route('transactions.update', $transaction) }}" x-data="{ type: @js(old('type', $transaction->type)) }" class="rounded-[1.5rem] border border-emerald-100 bg-white p-6 shadow-sm shadow-emerald-100/60">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Jenis Transaksi</label>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="cursor-pointer rounded-xl border p-4 transition" :class="type === 'income' ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="type" value="income" x-model="type" class="sr-only">
                            <span class="block text-sm font-bold text-slate-900">Pemasukan</span>
                            <span class="mt-1 block text-xs text-slate-500">Dana masuk ke usaha atau kas pribadi.</span>
                        </label>
                        <label class="cursor-pointer rounded-xl border p-4 transition" :class="type === 'expense' ? 'border-rose-300 bg-rose-50' : 'border-slate-200 hover:border-slate-300'">
                            <input type="radio" name="type" value="expense" x-model="type" class="sr-only">
                            <span class="block text-sm font-bold text-slate-900">Pengeluaran</span>
                            <span class="mt-1 block text-xs text-slate-500">Biaya yang mengurangi saldo kas.</span>
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
                                    <option value="{{ $cat->id }}" @selected((string) old('category_id', $transaction->category_id) === (string) $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-show="type === 'expense'">
                            <select name="category_id" :disabled="type !== 'expense'" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories['expense'] ?? collect() as $cat)
                                    <option value="{{ $cat->id }}" @selected((string) old('category_id', $transaction->category_id) === (string) $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="transaction_date" class="mb-1.5 block text-sm font-bold text-slate-700">Tanggal</label>
                        <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('transaction_date')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="amount" class="mb-1.5 block text-sm font-bold text-slate-700">Nominal</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-500">Rp</span>
                        <input type="number" id="amount" name="amount" value="{{ old('amount', (int) $transaction->amount) }}" min="1" step="1" class="w-full rounded-xl border-slate-200 pl-10 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    @error('amount')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-bold text-slate-700">Deskripsi</label>
                    <input type="text" id="description" name="description" value="{{ old('description', $transaction->description) }}" maxlength="255" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('description')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="reference_number" class="mb-1.5 block text-sm font-bold text-slate-700">Nomor Referensi</label>
                        <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number', $transaction->reference_number) }}" maxlength="100" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label for="notes" class="mb-1.5 block text-sm font-bold text-slate-700">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full resize-none rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('notes', $transaction->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <button type="submit" form="delete-transaction" class="rounded-lg border border-rose-200 px-4 py-2.5 text-sm font-bold text-rose-700 transition hover:bg-rose-50">Hapus</button>
                <div class="flex flex-col-reverse gap-3 sm:flex-row">
                    <a href="{{ route('transactions.index') }}" class="inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batal</a>
                    <button type="submit" class="inline-flex justify-center rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">Simpan Perubahan</button>
                </div>
            </div>
        </form>

        <form id="delete-transaction" method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Hapus transaksi ini?')">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-app-layout>
