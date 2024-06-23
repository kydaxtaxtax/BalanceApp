<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
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

        $permissions = $this->permissionRepository->getAllPermissions(
            $orderColumn,
            $orderDirection,
            request('search_id'),
            request('search_title'),
            request('search_global')
        );

        return PermissionResource::collection($permissions);
    }

    public function store(StorePermissionRequest $request)
    {
        $this->authorize('permission-create');

        $permission = $this->permissionRepository->createPermission($request->validated());

        if ($permission) {
            return new PermissionResource($permission);
        }

        return response()->json(['status' => 405, 'success' => false]);
    }

    public function show(Permission $permission)
    {
        $this->authorize('permission-edit');

        return new PermissionResource($permission);
    }

    public function update(Permission $permission, StorePermissionRequest $request)
    {
        $this->authorize('permission-edit');

        $permission = $this->permissionRepository->updatePermission($permission, $request->validated());

        if ($permission) {
            return new PermissionResource($permission);
        }

        return response()->json(['status' => 405, 'success' => false]);
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('permission-delete');
        $this->permissionRepository->deletePermission($permission);

        return response()->noContent();
    }

    public function getRolePermissions($id)
    {
        $permissions = $this->permissionRepository->getRolePermissions($id);
        return PermissionResource::collection($permissions);
    }

    public function updateRolePermissions(Request $request)
    {
        $this->authorize('role-edit');

        $permissions = json_decode($request->permissions, true);
        $permissions_where = $this->permissionRepository->updateRolePermissions($request->role_id, $permissions);

        return PermissionResource::collection($permissions_where);
    }
}
