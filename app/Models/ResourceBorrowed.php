<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ResourceBorrowed extends Model
{
    protected $table = 'resources_borrowed';

    protected $fillable = [
        'resource_id',
        'user_id',
        'status_id',
        'loan_date',
        'return_date',
        'Observation',
    ];

    protected $dates = [
        'loan_date',
        'return_date',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el modelo Resource
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
}
