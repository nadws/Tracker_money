<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'amount',
        'amount_encrypted',
        'transaction_date',
        'description',
        'notes',
        'reference_number',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    /** Transaksi dimiliki oleh user */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Transaksi memiliki satu kategori */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // -------------------------------------------------------
    // Scopes – untuk query laporan dan filter
    // -------------------------------------------------------

    /** Filter transaksi milik user tertentu */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /** Filter berdasarkan bulan dan tahun */
    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->whereMonth('transaction_date', $month)
                     ->whereYear('transaction_date', $year);
    }

    /** Filter hanya pemasukan */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /** Filter hanya pengeluaran */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    // -------------------------------------------------------
    // Accessor & Helper
    // -------------------------------------------------------

    /** Format nominal ke Rupiah: Rp 1.500.000 */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getAmountAttribute($value): float
    {
        $encryptedAmount = $this->attributes['amount_encrypted'] ?? null;

        if ($encryptedAmount) {
            return (float) Crypt::decryptString($encryptedAmount);
        }

        return (float) $value;
    }

    public function setAmountAttribute($value): void
    {
        $amount = number_format((float) $value, 2, '.', '');

        $this->attributes['amount_encrypted'] = Crypt::encryptString($amount);
        $this->attributes['amount'] = 0;
    }

    /** Apakah transaksi ini pemasukan? */
    public function isIncome(): bool
    {
        return $this->type === 'income';
    }
}
