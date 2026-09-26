<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->latest()->paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Group permissions by their "group" field
        $permissions = Permission::orderBy('group')->orderBy('module')->get()
            ->groupBy('group');

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name'          => 'required|string|max:50|unique:roles,name|alpha_dash',
        'label'         => 'required|string|max:100',
        'description'   => 'nullable|string|max:255',
        'permissions'   => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    $role = Role::create([
        'name'        => strtolower($data['name']),
        'label'       => $data['label'],
        'description' => $data['description'] ?? null,
    ]);

    $role->permissions()->sync($request->permissions ?? []);

    return redirect('/roles')->with('success', 'Role created successfully.');
}

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $permissions = Permission::orderBy('group')->orderBy('module')->get()
            ->groupBy('group');

        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:50|alpha_dash|unique:roles,name,' . $id,
            'label'         => 'required|string|max:100',
            'description'   => 'nullable|string|max:255',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Prevent renaming the built-in 'admin' role's name
        if ($role->name === 'admin' && strtolower($data['name']) !== 'admin') {
            return back()->withInput()->withErrors(['name' => 'The admin role name cannot be changed.']);
        }

        $role->update([
            'name'        => strtolower($data['name']),
            'label'       => $data['label'],
            'description' => $data['description'] ?? null,
        ]);

        // Admin role always keeps all permissions
        if ($role->name === 'admin') {
            $role->permissions()->sync(Permission::pluck('id'));
        } else {
            $role->permissions()->sync($request->permissions ?? []);
        }

        return redirect('/roles')->with('success', 'Role updated successfully.');
    }

   public function destroy($id)
{
    $role = Role::findOrFail($id);

    // Protect the admin role only (so you don't lock yourself out)
    if ($role->name === 'admin') {
        return back()->withErrors(['error' => 'The admin role cannot be deleted.']);
    }

    // Cannot delete a role that has users assigned
    if ($role->users()->count() > 0) {
        return back()->withErrors(['error' => 'Cannot delete — this role has users assigned.']);
    }

    $role->permissions()->detach();
    $role->delete();

    return redirect('/roles')->with('success', 'Role deleted successfully.');
}
}