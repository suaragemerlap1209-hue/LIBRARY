<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name', 'email', 'password',
    'role', 'status', 'birth_date', 'phone', 'address',
    'member_id', 'max_loans', 'expired_at',
];

protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'expired_at' => 'date',
    ];
}

public function loans()
{
    return $this->hasMany(Loan::class);
}

public function scopeMembers($query)
{
    return $query->where('role', 'member');
}

}