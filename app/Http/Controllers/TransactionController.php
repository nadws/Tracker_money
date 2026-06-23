<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi dengan filter bulan.
     */
    public function index(Request $request): View
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);

        // Ambil transaksi user yang login, filter per bulan
        $transactions = Transaction::forUser(Auth::id())
            ->forMonth($month, $year)
            ->with('category')                // eager load relasi kategori
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        // Hitung total pemasukan & pengeluaran bulan ini
        $totals = Transaction::forUser(Auth::id())
            ->forMonth($month, $year)
            ->selectRaw("
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
            ")
            ->first();

        return view('transactions.index', [
            'transactions'  => $transactions,
            'totalIncome'   => $totals->total_income ?? 0,
            'totalExpense'  => $totals->total_expense ?? 0,
            'selectedMonth' => $month,
            'selectedYear'  => $year,
        ]);
    }

    /**
     * Tampilkan form tambah transaksi baru.
     */
    public function create(): View
    {
        // Ambil kategori milik user + kategori default sistem
        $categories = Category::forUser(Auth::id())
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type'); // group: ['income' => [...], 'expense' => [...]]

        return view('transactions.create', compact('categories'));
    }

    /**
     * Simpan transaksi baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'             => 'required|in:income,expense',
            'category_id'      => 'required|exists:categories,id',
            'amount'           => 'required|numeric|min:1|max:999999999999',
            'transaction_date' => 'required|date|before_or_equal:today',
            'description'      => 'required|string|max:255',
            'notes'            => 'nullable|string|max:1000',
            'reference_number' => 'nullable|string|max:100',
        ], [
            // Pesan validasi dalam Bahasa Indonesia
            'type.required'             => 'Jenis transaksi wajib dipilih.',
            'category_id.required'      => 'Kategori wajib dipilih.',
            'amount.required'           => 'Nominal wajib diisi.',
            'amount.numeric'            => 'Nominal harus berupa angka.',
            'amount.min'                => 'Nominal minimal Rp 1.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'description.required'      => 'Deskripsi wajib diisi.',
        ]);

        // Pastikan kategori memang milik user ini atau kategori default
        $category = Category::forUser(Auth::id())->findOrFail($validated['category_id']);

        // Pastikan tipe transaksi sesuai dengan tipe kategori
        if ($category->type !== $validated['type']) {
            return back()->withErrors(['category_id' => 'Kategori tidak sesuai dengan jenis transaksi.'])->withInput();
        }

        Transaction::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dicatat.');
    }

    /**
     * Tampilkan detail satu transaksi.
     */
    public function show(Transaction $transaction): View
    {
        // Pastikan transaksi milik user yang login
        $this->authorize('view', $transaction);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Tampilkan form edit transaksi.
     */
    public function edit(Transaction $transaction): View
    {
        $this->authorize('update', $transaction);

        $categories = Category::forUser(Auth::id())
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update transaksi yang sudah ada.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'type'             => 'required|in:income,expense',
            'category_id'      => 'required|exists:categories,id',
            'amount'           => 'required|numeric|min:1|max:999999999999',
            'transaction_date' => 'required|date|before_or_equal:today',
            'description'      => 'required|string|max:255',
            'notes'            => 'nullable|string|max:1000',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $category = Category::forUser(Auth::id())->findOrFail($validated['category_id']);

        if ($category->type !== $validated['type']) {
            return back()->withErrors(['category_id' => 'Kategori tidak sesuai dengan jenis transaksi.'])->withInput();
        }

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Hapus transaksi.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
