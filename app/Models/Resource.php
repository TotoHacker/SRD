<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
    ];

    // Relación uno a muchos con los préstamos de recursos
    public function resourceBorroweds()
    {
        return $this->hasMany(ResourceBorrowed::class);
    }
}
