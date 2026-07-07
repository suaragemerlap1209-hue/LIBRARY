<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    protected $fillable = ['fine_id', 'file_path', 'status'];

    public function fine()
    {
        return $this->belongsTo(Fine::class);
    }
}
