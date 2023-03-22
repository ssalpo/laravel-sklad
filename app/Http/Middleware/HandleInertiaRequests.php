<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Permission\Models\Permission;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        $userPermissions = $request->user()?->getPermissionsViaRoles()->pluck('name') ?? [];

        if (auth()->check() && $request->user()->hasRole('admin')) {
            $userPermissions = Permission::pluck('name');
        }

        return array_merge(parent::share($request), [
            'shared' => [
                'isAuth' => auth()->check(),
                'userId' => auth()->id(),
                'userRoles' => $request->user()?->roles->pluck('name') ?? [],
                'userPermissions' => $userPermissions,
            ],
            'flash' => [
                'isCreated' => fn() => $request->session()->get('isCreated')
            ],
        ]);
    }
}
