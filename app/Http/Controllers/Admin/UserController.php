<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role', 'all');

        $query = User::query()->where('role', '!=', User::ROLE_ADMIN)->orderByDesc('created_at');

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        $users = $query->with(['patient', 'doctor'])->paginate(15)->withQueryString();

        $counts = [
            'all' => User::where('role', '!=', User::ROLE_ADMIN)->count(),
            'doctor' => User::where('role', User::ROLE_DOCTOR)->count(),
            'nurse' => User::where('role', User::ROLE_NURSE)->count(),
            'patient' => User::where('role', User::ROLE_PATIENT)->count(),
        ];

        return view('admin.users.index', compact('users', 'role', 'counts'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in([User::ROLE_DOCTOR, User::ROLE_NURSE, User::ROLE_PATIENT])],
            'password' => 'required|string|min:8|confirmed',
            'specialty' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($user->role === User::ROLE_DOCTOR) {
            $user->doctor()->create([
                'specialty' => $validated['specialty'] ?? 'General',
            ]);
        } elseif ($user->role === User::ROLE_PATIENT) {
            $user->patient()->create([
                'patient_id' => 'PAT-' . date('y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot edit admin accounts.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot edit admin accounts.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if ($user->role === User::ROLE_DOCTOR && !empty($validated['specialty'])) {
            $user->doctor()->updateOrCreate(['user_id' => $user->id], ['specialty' => $validated['specialty']]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete admin accounts.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
