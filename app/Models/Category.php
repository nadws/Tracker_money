<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'icon',
        'color',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    /** Kategori dimiliki oleh user (null = kategori sistem) */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Satu kategori memiliki banyak transaksi */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    /** Filter kategori milik user tertentu + kategori default sistem */
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhereNull('user_id'); // kategori default sistem
        });
    }

    /** Filter hanya kategori pemasukan */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /** Filter hanya kategori pengeluaran */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}
