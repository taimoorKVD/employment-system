<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\HasMany};

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'phone_code'
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class, 'country_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'country_id');
    }
}
