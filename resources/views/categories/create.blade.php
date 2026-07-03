<x-app-layout>
    <x-slot name="title">Tambah Kategori</x-slot>
    <x-slot name="header">Tambah Kategori</x-slot>

    @include('categories.partials.form', [
        'category' => null,
        'action' => route('categories.store'),
        'method' => 'POST',
        'title' => 'Tambah Kategori',
        'subtitle' => 'Buat kategori baru untuk transaksi pemasukan atau pengeluaran.',
        'button' => 'Simpan Kategori',
    ])
</x-app-layout>
