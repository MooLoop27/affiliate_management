<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->paginate(10);
        return view('user-management.index', compact('users'));
    }

    public function create(): View
    {
        return view('user-management.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:owner,admin,finance',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        ActivityLog::log('Created user: ' . $validated['name']);

        return redirect()->route('user-management.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('user-management.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:owner,admin,finance',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        ActivityLog::log('Updated user: ' . $user->name);

        return redirect()->route('user-management.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->isOwner()) {
            return redirect()->back()->with('error', 'Cannot delete Owner account.');
        }

        $name = $user->name;
        $user->delete();

        ActivityLog::log('Deleted user: ' . $name);

        return redirect()->route('user-management.index')
            ->with('success', 'User deleted successfully.');
    }
}

