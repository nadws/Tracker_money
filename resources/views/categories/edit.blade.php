<x-app-layout>
    <x-slot name="title">Edit Kategori</x-slot>
    <x-slot name="header">Edit Kategori</x-slot>

    @include('categories.partials.form', [
        'category' => $category,
        'action' => route('categories.update', $category),
        'method' => 'PUT',
        'title' => 'Edit Kategori',
        'subtitle' => 'Perbarui nama, jenis, ikon, atau warna kategori.',
        'button' => 'Simpan Perubahan',
    ])
</x-app-layout>
