<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at->format('d.m.Y')
            ]);

        return inertia('Users/Index', compact('users'));
    }

    public function create()
    {
        return inertia('Users/Edit');
    }

    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username
        ];

        return inertia('Users/Edit', [
            'user' => $userData
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.index');
    }

    public function toggleActivity(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
    }
}
