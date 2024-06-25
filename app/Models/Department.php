<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, Relations\HasMany};
use Filament\Facades\Filament;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team_id'
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class)->where('team_id', Filament::getTenant()->id);
    }

    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class,'team_id', 'id');
    }
}
