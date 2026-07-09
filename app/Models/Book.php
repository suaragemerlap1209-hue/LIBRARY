<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Book extends Model
{
    protected $fillable = ['category_id', 'title', 'author', 'isbn', 'cover', 'stock', 'description'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Alias untuk stock — jumlah salinan yang tersedia untuk dipinjam saat ini
     */
    protected function availableCopies(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stock,
        );
    }

    /**
     * Cek apakah buku ini masih bisa dipinjam
     */
    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }
}