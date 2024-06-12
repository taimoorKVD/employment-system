<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, Relations\HasMany};

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
