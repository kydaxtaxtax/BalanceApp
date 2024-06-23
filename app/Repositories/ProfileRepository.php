<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class ProfileRepository
{
    public function updateUser($user, $name, $email)
    {
        $user->name = $name;
        $user->email = $email;

        if ($user->save()) {
            return $user;
        }

        return false;
    }

    public function findUserById($userId)
    {
        return Auth::user();
    }
}
