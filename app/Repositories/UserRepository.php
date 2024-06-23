<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRepository
{
    public function getAllUsers($orderColumn, $orderDirection, $searchId = null, $searchTitle = null, $searchGlobal = null)
    {
        return User::
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

    public function createUser($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        if ($user->save()) {
            if (isset($data['role_id'])) {
                $role = Role::find($data['role_id']);
                if ($role) {
                    $user->assignRole($role);
                }
            }
            return $user;
        }

        return null;
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        $user->load('roles');
        return $user;
    }

    public function updateUser(User $user, $data)
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']) ?? $user->password;
        }

        if ($user->save()) {
            if (isset($data['role_id'])) {
                $role = Role::find($data['role_id']);
                if ($role) {
                    $user->syncRoles($role);
                }
            }
            return $user;
        }

        return null;
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}
