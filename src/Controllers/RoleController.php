<?php

namespace iLaravel\Acl\Controllers;

use App\Http\Controllers\Controller;
use iLaravel\Acl\Models\Permission;
use iLaravel\Acl\Models\Role;
use iLaravel\Acl\Requests\StoreRoleRequest;
use iLaravel\Acl\Requests\UpdateRoleRequest;
use iLaravel\Acl\Requests\SyncPermissionRoleRequest;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function create()
    {
        return view('ilaravel-acl::role.create');
    }

    public function store(StoreRoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->label = $request->label;
        $role->save();

        return [
            'status' => true,
        ];
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('ilaravel-acl::role.edit', compact('role'));
    }

    public function update($id, UpdateRoleRequest $request)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->label = $request->label;
        $role->save();

        return [
            'status' => true,
        ];
    }

    public function getSyncPermissions()
    {
        $roles = Role::pluck('name', 'id')->all();

        $data = [];

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $data[$permission->model][$permission->id] = $permission->method;
        }
        $permissions = $data;

        return view('ilaravel-acl::role.sync_permission', compact('roles', 'permissions'));
    }

    public function syncPermissions(SyncPermissionRoleRequest $request)
    {
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        /** @var Role $role */
        $role = Role::where('id', $request->role)->first();
        $role->givePermissions($permissions);

        return [
            'status' => true,
        ];
    }

    public function getRoles()
    {
        $roles = Role::all();

        return $roles;
    }
}
