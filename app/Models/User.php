<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Facades\Filament;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Relations\BelongsTo, Relations\BelongsToMany, Model, Relations\HasMany};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Panel;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property mixed $teams
 * @property mixed $is_admin
 * @method static create(array $array)
 * @method static truncate()
 * @method static associatedUsers()
 */
class User extends Authenticatable implements HasTenants, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'is_admin',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'created_by' => 'integer'
    ];

    public function scopeAssociatedUsers($query)
    {
        $email = env('ADMIN_EMAIL');
        $loggedUser = auth()->user();
        $query
            ->where('email', '!=', $email)
            ->whereHas('teams', function ($query) {
                $query->where('team_id', Filament::getTenant()->id);
            });
        if ($loggedUser->email != $email) {
            $query
                ->where('is_admin', '=', 0)
                ->where('created_by', $loggedUser->id);
        }
        return $query->latest();
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@fp2.com') && $this->hasVerifiedEmail();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
