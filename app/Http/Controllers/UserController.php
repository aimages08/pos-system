<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('label')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'status'   => 'required|in:active,disabled',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect('/users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::orderBy('label')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'status'   => 'required|in:active,disabled',
        ]);

        // Safety: cannot change own role or status
        if ($user->id === Auth::id()) {
            $data['role_id'] = $user->role_id;
            $data['status']  = $user->status;
        }

        // Safety: cannot remove last admin
        if ($user->role && $user->role->name === 'admin') {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole && $data['role_id'] != $adminRole->id) {
                $adminCount = User::where('role_id', $adminRole->id)->count();
                if ($adminCount <= 1) {
                    return back()->withInput()->withErrors(['role_id' => 'Cannot change role — this is the last admin.']);
                }
            }
        }

        // Password: only update if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect('/users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Safety: cannot delete yourself
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        // Safety: cannot delete the last admin
        if ($user->role && $user->role->name === 'admin') {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole && User::where('role_id', $adminRole->id)->count() <= 1) {
                return back()->withErrors(['error' => 'Cannot delete the last admin.']);
            }
        }

        $user->delete();

        return redirect('/users')->with('success', 'User deleted successfully.');
    }
}