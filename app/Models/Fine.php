<?php
// app/Models/Fine.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'loan_id',
        'amount',
        'status',
        'order_id',
        'transaction_id',
        'payment_type',
        'snap_token',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'amount'  => 'integer',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // Helper: apakah denda ini bisa dibayar?
    public function isPayable(): bool
    {
        return in_array($this->status, ['unpaid', 'pending']);
    }

    // Helper: apakah sudah lunas?
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}