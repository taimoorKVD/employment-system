<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo};
use Filament\Facades\Filament;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->where('team_id', Filament::getTenant()->id);
    }

    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }
}
