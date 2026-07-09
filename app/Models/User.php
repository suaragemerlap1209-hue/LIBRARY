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

protected static function booted(): void
{
    static::creating(function (User $user) {
        if ($user->role === 'member' && empty($user->member_id)) {
            $last = static::where('role', 'member')->max('id') ?? 0;
            $user->member_id = 'MB-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        }
    });
}

}