<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\HasMany};

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
