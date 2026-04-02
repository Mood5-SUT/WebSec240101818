<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['list', 'show']);
    }

    public function list()
    {
        if (!auth()->check() || !auth()->user()->hasPermissionTo('view_roles')) {
            abort(403);
        }

        $roles = Role::with('permissions')->orderBy('name')->get();

        return view('roles.list', compact('roles'));
    }
}
