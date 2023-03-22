<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display the register view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return inertia('Auth/Register');
    }

    /**
     * Handle an incoming register request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request)
    {
        User::create(array_merge($request->validated(), ['password' => Hash::make($request->password)]));

        Auth::attempt($request->validated(), true);

        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
