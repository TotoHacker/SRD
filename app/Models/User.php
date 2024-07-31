<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['NameUser', 'email', 'password', 'role','StatusSesion'];

    public function loans()
    {
        return $this->hasMany(ResourceBorrowed::class);
    }
}
