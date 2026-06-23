<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard dengan ringkasan keuangan bulan ini.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $month  = now()->month;
        $year   = now()->year;

        // ── Ringkasan bulan ini ──────────────────────────────────
        $summary = Transaction::forUser($userId)
            ->forMonth($month, $year)
            ->selectRaw("
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END)  as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
                COUNT(*) as total_transactions
            ")
            ->first();

        $totalIncome   = $summary->total_income   ?? 0;
        $totalExpense  = $summary->total_expense  ?? 0;
        $netProfit     = $totalIncome - $totalExpense;

        // ── 5 transaksi terbaru ──────────────────────────────────
        $recentTransactions = Transaction::forUser($userId)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ── Pengeluaran per kategori bulan ini (untuk chart) ─────
        $expenseByCategory = Transaction::forUser($userId)
            ->forMonth($month, $year)
            ->expense()
            ->with('category')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();


        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $saldo = $totalIncome - $totalExpense;
        $chartData = Transaction::where('user_id', $userId)
            ->selectRaw('transaction_date, 
                    SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income,
                    SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            ->groupBy('transaction_date')
            ->orderBy('transaction_date', 'asc')
            ->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'recentTransactions',
            'expenseByCategory',
            'totalIncome',
            'totalExpense',
            'saldo',
            'chartData',
        ));
    }
}
