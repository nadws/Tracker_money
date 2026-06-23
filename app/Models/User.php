<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'business_name',
        'business_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    /** User memiliki banyak transaksi */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /** User memiliki banyak kategori custom */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    // -------------------------------------------------------
    // Helper
    // -------------------------------------------------------

    /** Nama yang ditampilkan: nama usaha atau nama user */
    public function getDisplayNameAttribute(): string
    {
        return $this->business_name ?? $this->name;
    }
}
