<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Department[] $roles
 * @property-read int|null $roles_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Permission extends Model
{
    use HasRoles;

    public static function boot()
    {
        parent::boot();

        self::creating(function (self $role) {
            if (empty($role->guard_name)) {
                $role->guard_name = Guard::getDefaultName(static::class);
            }
        });
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_permissions'),
            'permission_id',
            config('permission.column_names.model_morph_key')
        );
    }

    // public static function findByName($name, $guardName = null)
    // {
    //     $guardName = $guardName ?? Guard::getDefaultName(static::class);
    //     $permission = static::getPermissions(['name' => $name, 'guard_name' => $guardName])->first();
    //     if (! $permission) {
    //         throw PermissionDoesNotExist::create($name, $guardName);
    //     }

    //     return $permission;
    // }

    // public static function findById($id, $guardName = null)
    // {
    //     $guardName = $guardName ?? Guard::getDefaultName(static::class);
    //     $permission = static::getPermissions(['id' => $id, 'guard_name' => $guardName])->first();

    //     if (! $permission) {
    //         throw PermissionDoesNotExist::withId($id);
    //     }

    //     return $permission;
    // }

    // public static function findOrCreate($name, $guardName = null)
    // {
    //     $guardName = $guardName ?? Guard::getDefaultName(static::class);
    //     $permission = static::getPermissions(['name' => $name, 'guard_name' => $guardName])->first();

    //     if ($permission) {
    //         return $permission;
    //     }

    //     return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
    // }

    protected static function getPermissions($params = [])
    {
        return app(PermissionRegistrar::class)->getPermissions($params);
    }
}
