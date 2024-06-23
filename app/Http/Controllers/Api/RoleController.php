<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Repositories\RoleRepository;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
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

        $roles = $this->roleRepository->getAllRoles(
            $orderColumn,
            $orderDirection,
            request('search_id'),
            request('search_title'),
            request('search_global')
        );

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RoleResource
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize('role-create');

        $role = $this->roleRepository->createRole($request->validated());

        if ($role) {
            return new RoleResource($role);
        }

        return response()->json(['status' => 405, 'success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Role $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        $this->authorize('role-edit');

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Role $role
     * @param StoreRoleRequest $request
     * @return RoleResource
     * @throws AuthorizationException
     */
    public function update(Role $role, StoreRoleRequest $request)
    {
        $this->authorize('role-edit');

        $role = $this->roleRepository->updateRole($role, $request->validated());

        if ($role) {
            return new RoleResource($role);
        }

        return response()->json(['status' => 405, 'success' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $this->authorize('role-delete');
        $this->roleRepository->deleteRole($role);

        return response()->noContent();
    }

    public function getList()
    {
        $roles = $this->roleRepository->getAllRolesList();
        return RoleResource::collection($roles);
    }
}
