<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRepository
{
    public function getAllPermissions($orderColumn, $orderDirection, $searchId = null, $searchTitle = null, $searchGlobal = null)
    {
        return Permission::
        when($searchId, function ($query) use ($searchId) {
            $query->where('id', $searchId);
        })
            ->when($searchTitle, function ($query) use ($searchTitle) {
                $query->where('name', 'like', '%' . $searchTitle . '%');
            })
            ->when($searchGlobal, function ($query) use ($searchGlobal) {
                $query->where(function ($q) use ($searchGlobal) {
                    $q->where('id', $searchGlobal)
                        ->orWhere('name', 'like', '%' . $searchGlobal . '%');
                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);
    }

    public function createPermission($data)
    {
        $permission = new Permission();
        $permission->name = $data['name'];
        $permission->guard_name = 'web';

        if ($permission->save()) {
            return $permission;
        }

        return null;
    }

    public function getPermissionById($id)
    {
        return Permission::find($id);
    }

    public function updatePermission(Permission $permission, $data)
    {
        $permission->name = $data['name'];

        if ($permission->save()) {
            return $permission;
        }

        return null;
    }

    public function deletePermission(Permission $permission)
    {
        return $permission->delete();
    }

    public function getRolePermissions($id)
    {
        return Role::findById($id, 'web')->permissions;
    }

    public function updateRolePermissions($roleId, $permissions)
    {
        $role = Role::findById($roleId, 'web');
        $permissions_where = Permission::whereIn('id', $permissions)->get();
        $role->syncPermissions($permissions_where);

        return $permissions_where;
    }
}
