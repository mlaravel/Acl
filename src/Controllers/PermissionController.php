<?php

namespace iLaravel\Acl\Controllers;

use App\Http\Controllers\Controller;
use iLaravel\Acl\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function getPermissions()
    {
        $permissions = Permission::all();

        return $permissions;
    }
}
