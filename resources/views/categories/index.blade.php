<x-app-layout>
    <x-slot name="title">Kategori</x-slot>
    <x-slot name="header">Kategori</x-slot>

    @php
        $typeLabels = ['income' => 'Pemasukan', 'expense' => 'Pengeluaran'];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold text-emerald-700">Master Data</p>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Kategori Transaksi</h1>
                <p class="mt-1 text-sm text-slate-500">Atur kategori pemasukan dan pengeluaran sesuai kebutuhan usaha.</p>
            </div>
            <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-700">
                <span aria-hidden="true">+</span>
                Tambah Kategori
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @error('category')
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ $message }}
            </div>
        @enderror

        <div class="grid gap-6 lg:grid-cols-2">
            @foreach (['income', 'expense'] as $type)
                <section class="overflow-hidden rounded-[1.5rem] border border-emerald-100 bg-white shadow-sm shadow-emerald-100/60">
                    <div class="border-b border-emerald-50 px-5 py-4">
                        <h2 class="text-lg font-extrabold text-slate-950">{{ $typeLabels[$type] }}</h2>
                        <p class="text-sm text-slate-500">{{ ($categories[$type] ?? collect())->count() }} kategori tersedia</p>
                    </div>

                    <div class="divide-y divide-emerald-50">
                        @forelse ($categories[$type] ?? collect() as $category)
                            <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl text-lg font-extrabold text-white" style="background-color: {{ $category->color }}">
                                        {{ $category->icon ?: strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-bold text-slate-900">{{ $category->name }}</p>
                                        <p class="text-xs font-medium text-slate-500">
                                            {{ $category->is_default ? 'Kategori sistem' : 'Kategori custom' }} - {{ $category->transactions_count }} transaksi
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 sm:justify-end">
                                    @if (! $category->is_default && $category->user_id === auth()->id())
                                        <a href="{{ route('categories.edit', $category) }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900">Edit</a>
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-bold text-rose-600 hover:text-rose-700">Hapus</button>
                                        </form>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">Default</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-slate-500">Belum ada kategori.</div>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
