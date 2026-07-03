<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::forUser(Auth::id())
            ->withCount('transactions')
            ->orderBy('type')
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        Category::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'is_default' => false,
        ]));

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        $this->ensureOwnCategory($category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->ensureOwnCategory($category);

        $category->update($this->validatedData($request));

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->ensureOwnCategory($category);

        if ($category->transactions()->exists()) {
            return back()->withErrors([
                'category' => 'Kategori tidak bisa dihapus karena sudah dipakai transaksi.',
            ]);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:20',
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'type.required' => 'Jenis kategori wajib dipilih.',
            'type.in' => 'Jenis kategori tidak valid.',
            'color.required' => 'Warna kategori wajib dipilih.',
            'color.regex' => 'Format warna harus berupa hex, contoh #10b981.',
        ]);
    }

    private function ensureOwnCategory(Category $category): void
    {
        abort_if($category->user_id !== Auth::id(), 403);
    }
}
