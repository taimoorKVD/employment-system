<?php

namespace App\Models;

use App\Models\Scopes\TeamScope;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, Relations\HasMany};

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team_id',
        'user_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TeamScope());
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class,'team_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
