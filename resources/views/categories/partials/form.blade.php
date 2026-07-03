@php
    $selectedType = old('type', $category->type ?? 'expense');
    $selectedColor = old('color', $category->color ?? '#10b981');
@endphp

<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-emerald-700">Master Data</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">{{ $title }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
        </div>
        <a href="{{ route('categories.index') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900">Kembali</a>
    </div>

    <form method="POST" action="{{ $action }}" class="rounded-[1.5rem] border border-emerald-100 bg-white p-6 shadow-sm shadow-emerald-100/60">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="space-y-5">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-bold text-slate-700">Nama Kategori</label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" maxlength="100" placeholder="Contoh: Bahan baku, Bonus, Transport" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('name')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Jenis Kategori</label>
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="cursor-pointer rounded-xl border p-4 transition {{ $selectedType === 'income' ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" name="type" value="income" class="sr-only" @checked($selectedType === 'income')>
                        <span class="block text-sm font-bold text-slate-900">Pemasukan</span>
                        <span class="mt-1 block text-xs text-slate-500">Untuk uang masuk, penjualan, jasa, atau pendapatan lain.</span>
                    </label>
                    <label class="cursor-pointer rounded-xl border p-4 transition {{ $selectedType === 'expense' ? 'border-rose-300 bg-rose-50' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" name="type" value="expense" class="sr-only" @checked($selectedType === 'expense')>
                        <span class="block text-sm font-bold text-slate-900">Pengeluaran</span>
                        <span class="mt-1 block text-xs text-slate-500">Untuk biaya operasional, belanja, dan pembayaran.</span>
                    </label>
                </div>
                @error('type')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="icon" class="mb-1.5 block text-sm font-bold text-slate-700">Ikon Singkat</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $category->icon ?? '') }}" maxlength="20" placeholder="Contoh: B, GJ, TR" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('icon')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="color" class="mb-1.5 block text-sm font-bold text-slate-700">Warna</label>
                    <div class="flex gap-3">
                        <input type="color" id="color" name="color" value="{{ $selectedColor }}" class="h-11 w-16 rounded-xl border border-slate-200 bg-white p-1">
                        <input type="text" value="{{ $selectedColor }}" disabled class="min-w-0 flex-1 rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-500">
                    </div>
                    @error('color')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('categories.index') }}" class="inline-flex justify-center rounded-full border border-emerald-100 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700">Batal</a>
            <button type="submit" class="inline-flex justify-center rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">{{ $button }}</button>
        </div>
    </form>
</div>
