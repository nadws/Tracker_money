<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('transactions', 'amount_encrypted')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->text('amount_encrypted')->nullable()->after('amount');
            });
        }

        DB::table('transactions')
            ->whereNull('amount_encrypted')
            ->orderBy('id')
            ->select('id', 'amount')
            ->chunkById(100, function ($transactions) {
                foreach ($transactions as $transaction) {
                    DB::table('transactions')
                        ->where('id', $transaction->id)
                        ->update([
                            'amount_encrypted' => Crypt::encryptString(number_format((float) $transaction->amount, 2, '.', '')),
                            'amount' => 0,
                        ]);
                }
            });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('transactions', 'amount_encrypted')) {
            return;
        }

        DB::table('transactions')
            ->whereNotNull('amount_encrypted')
            ->orderBy('id')
            ->select('id', 'amount_encrypted')
            ->chunkById(100, function ($transactions) {
                foreach ($transactions as $transaction) {
                    DB::table('transactions')
                        ->where('id', $transaction->id)
                        ->update([
                            'amount' => (float) Crypt::decryptString($transaction->amount_encrypted),
                        ]);
                }
            });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('amount_encrypted');
        });
    }
};
