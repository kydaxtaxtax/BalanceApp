<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function getAllRoles($orderColumn, $orderDirection, $searchId = null, $searchTitle = null, $searchGlobal = null)
    {
        return Role::
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

    public function createRole($data)
    {
        $role = new Role();
        $role->name = $data['name'];
        $role->guard_name = 'web';

        if ($role->save()) {
            return $role;
        }

        return null;
    }

    public function getRoleById($id)
    {
        return Role::find($id);
    }

    public function updateRole(Role $role, $data)
    {
        $role->name = $data['name'];

        if ($role->save()) {
            return $role;
        }

        return null;
    }

    public function deleteRole(Role $role)
    {
        return $role->delete();
    }

    public function getAllRolesList()
    {
        return Role::all();
    }
}
