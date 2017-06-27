<?php

namespace iLaravel\Acl\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissions($permissions)
    {
        return $this->permissions()->sync($permissions);
    }
}
