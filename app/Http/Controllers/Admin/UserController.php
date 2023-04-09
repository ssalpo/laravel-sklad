<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Toast;

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
                'is_admin' => $user->is_admin,
                'telegram_user_id' => $user->telegram_user_id,
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

        Toast::success('Новый сотрудник успешно создан!');

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

        Toast::success('Данные сотрудника успешно изменены!');

        return redirect()->route('users.index');
    }

    public function toggleActivity(User $user)
    {
        $status = !$user->is_active;

        $user->update(['is_active' => $status]);

        if($status) {
            Toast::success('Сотрудник активен.');
        }

        if(!$status) {
            Toast::error('Сотрудник отключен.');
        }

        return back();
    }

    public function toggleAdminStatus(User $user)
    {
        $status = !$user->is_admin;

        $user->update(['is_admin' => $status]);

        if($status) {
            Toast::error('Теперь сотрудник может авторизироваться как администратор');
        }

        return back();
    }
}
