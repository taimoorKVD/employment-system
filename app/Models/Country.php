<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'phone_code'
    ];

    public function states(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(State::class);
    }
}
