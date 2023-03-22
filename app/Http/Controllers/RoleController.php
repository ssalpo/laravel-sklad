<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_roles')->only('index');
        $this->middleware('can:create_roles')->only(['create', 'store']);
        $this->middleware('can:edit_roles')->only(['edit', 'update']);
    }

    public function index()
    {
        $roles = Role::orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($user) => [
                'id' => $user->id,
                'readable_name' => $user->readable_name
            ]);

        return inertia('Roles/Index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::pluck('readable_name', 'id');

        return inertia('Roles/Edit', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->validated() + ['guard_name' => 'web']);

        $role->syncPermissions(Permission::whereIn('id', $request->permissions)->pluck('name'));

        return redirect()->route('roles.index');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::pluck('readable_name', 'id');

        return inertia('Roles/Edit', [
            'permissions' => $permissions,
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'readable_name' => $role->readable_name,
                'permissions' => $role->permissions->pluck('id')
            ]
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update(
            $request->user()->hasRole('admin')
                ? Arr::except($request->validated(), 'name')
                : $request->validated()
        );

        $role->syncPermissions(Permission::whereIn('id', $request->permissions)->pluck('name'));

        return redirect()->route('roles.index');
    }
}
