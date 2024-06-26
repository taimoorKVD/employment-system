<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static exceptAdmin()
 */
class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    private const IgnorableRole = 'Admin';

    protected static function booted()
    {
        static::addGlobalScope('exceptAdmin', function (Builder $builder) {
            $builder->where('name', '!=', self::IgnorableRole);
        });
    }
}
