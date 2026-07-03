<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function assignRole(AssignRoleRequest $request, User $user): RedirectResponse
    {
        $user->syncRoles([$request->input('role')]);

        return back()->with('success', "Rol de {$user->name} actualizado a {$request->input('role')}.");
    }
}
