<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * @throws ValidationException
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $name = $request->name;
        $email = $request->email;

        $updatedUser = $this->profileRepository->updateUser($user, $name, $email);

        if ($updatedUser) {
            return $this->successResponse($updatedUser, 'User updated');
        }

        return response()->json(['status' => 403, 'success' => false]);
    }

    public function user(Request $request)
    {
        $user = $this->profileRepository->findUserById(Auth::id());

        return $this->successResponse($user, 'User found');
    }
}
