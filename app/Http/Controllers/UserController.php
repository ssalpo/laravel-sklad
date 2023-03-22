<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read_users')->only('index');
        $this->middleware('can:create_users')->only(['create', 'store']);
        $this->middleware('can:edit_users')->only(['edit', 'update']);
        $this->middleware('can:toggle_activity_users')->only('toggleActivity');
    }

    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at->format('d.m.Y'),
                'roles' => $user->roles->pluck('readable_name') ?? []
            ]);

        return inertia('Users/Index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('readable_name', 'name');

        return inertia('Users/Edit', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create($request->validated());
            $user->assignRole($request->role);
        });

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('readable_name', 'name');

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'role' => $user->roles[0]->name,
        ];

        return inertia('Users/Edit', [
            'roles' => $roles,
            'user' => $userData
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {
            $user->update($request->validated());
            $user->syncRoles($request->role);
        });

        return redirect()->route('users.index');
    }

    public function toggleActivity(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }
}
