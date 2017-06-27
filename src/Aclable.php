<?php

namespace iLaravel\Acl;

use App\Providers\AuthServiceProvider;
use iLaravel\Acl\Models\Permission;
use iLaravel\Acl\Models\Role;
use iLaravel\Billing\Models\Charge;
use iLaravel\Billing\Models\Invoice;

trait Aclable
{
    public static $roleOfPermission;
    public static $permissions;

    public function getPermissionsAttribute()
    {
        if (self::$roleOfPermission != $this->role) {
            $role = Role::with(['permissions'])
                ->where('name', $this->role)
                ->first();
            self::$permissions = $role->permissions->pluck('name')->all();

            self::$roleOfPermission = $this->role;
        }

        return self::$permissions;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getRoleAttribute()
    {
        $role = session('role');

        if (!$role) {
            $role = $this->roles[0]->name;
            $role = session('role', $role);
        }

        return $role;
    }

    public function hasPermission($policy = NULL, $method = NULL)
    {
        if (!$method)
            $method = debug_backtrace()[1]['function'];
        if (!$policy)
            $policy = debug_backtrace()[1]['class'];

        // Get Models
        $policiesClone = AuthServiceProvider::$policiesClone;
        $policiesClone = array_flip($policiesClone);
        $model = $policiesClone[$policy];

        // find in array
        $name = $model . '@' . $method;
        if (in_array($name, $this->permissions)) {
            return true;
        }

        return false;
    }
}