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
        $month = now()->month;
        $year = now()->year;

        // ── Ringkasan bulan ini ──────────────────────────────────
        $monthlyTransactions = Transaction::forUser($userId)
            ->forMonth($month, $year)
            ->with('category')
            ->get();

        $monthlyIncome = $monthlyTransactions
            ->where('type', 'income')
            ->sum(fn (Transaction $transaction) => $transaction->amount);
        $monthlyExpense = $monthlyTransactions
            ->where('type', 'expense')
            ->sum(fn (Transaction $transaction) => $transaction->amount);
        $monthlyNet = $monthlyIncome - $monthlyExpense;
        $netProfit = $monthlyNet;
        $transactionCount = $monthlyTransactions->count();

        // ── 5 transaksi terbaru ──────────────────────────────────
        $recentTransactions = Transaction::forUser($userId)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ── Pengeluaran per kategori bulan ini (untuk chart) ─────
        $expenseByCategory = $monthlyTransactions
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($transactions) {
                $firstTransaction = $transactions->first();

                return (object) [
                    'category' => $firstTransaction?->category,
                    'total' => $transactions->sum(fn (Transaction $transaction) => $transaction->amount),
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();


        $allTransactions = Transaction::where('user_id', $userId)->get();
        $totalIncome = $allTransactions
            ->where('type', 'income')
            ->sum(fn (Transaction $transaction) => $transaction->amount);
        $totalExpense = $allTransactions
            ->where('type', 'expense')
            ->sum(fn (Transaction $transaction) => $transaction->amount);
        $saldo = $totalIncome - $totalExpense;
        $chartData = Transaction::where('user_id', $userId)
            ->where('transaction_date', '>=', now()->subDays(29)->toDateString())
            ->orderBy('transaction_date')
            ->get()
            ->groupBy(fn (Transaction $transaction) => $transaction->transaction_date->toDateString())
            ->map(fn ($transactions, $date) => (object) [
                'transaction_date' => $date,
                'income' => $transactions->where('type', 'income')->sum(fn (Transaction $transaction) => $transaction->amount),
                'expense' => $transactions->where('type', 'expense')->sum(fn (Transaction $transaction) => $transaction->amount),
            ])
            ->values();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'recentTransactions',
            'expenseByCategory',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyNet',
            'transactionCount',
            'totalIncome',
            'totalExpense',
            'saldo',
            'chartData',
        ));
    }
}
