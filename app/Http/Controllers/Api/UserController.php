<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $users = $this->userRepository->getAllUsers(
            $orderColumn,
            $orderDirection,
            request('search_id'),
            request('search_title'),
            request('search_global')
        );

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->createUser($request->validated());

        if ($user) {
            return new UserResource($user);
        }

        return response()->json(['status' => 405, 'success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        $user = $this->userRepository->getUserById($user->id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userRepository->updateUser($user, $request->validated());

        if ($user) {
            return new UserResource($user);
        }

        return response()->json(['status' => 405, 'success' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('user-delete');
        $this->userRepository->deleteUser($user);

        return response()->noContent();
    }
}
