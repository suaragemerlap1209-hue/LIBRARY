<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'status', 'birth_date', 'address',
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
                $year = now()->year;

                $lastNumber = static::where('member_id', 'like', "LIB-{$year}-%")
                    ->orderByDesc('member_id')
                    ->value('member_id');

                $nextNumber = $lastNumber
                    ? ((int) substr($lastNumber, -4)) + 1
                    : 1;

                $user->member_id = sprintf('LIB-%d-%04d', $year, $nextNumber);
            }

            if ($user->role === 'member' && empty($user->expired_at)) {
                $user->expired_at = now()->addYears(3);
            }
        });
    }
}