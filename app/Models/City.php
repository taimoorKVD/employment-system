<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\HasMany, Relations\BelongsTo};

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name'
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
