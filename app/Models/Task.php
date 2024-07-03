<?php

namespace App\Models;

use App\Models\Scopes\TeamScope;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo};

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'due_date',
        'created_by',
        'user_id',
        'team_id'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TeamScope());
    }

    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class,'team_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
