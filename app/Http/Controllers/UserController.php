<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roleFilter = $request->get('role', 'peserta'); // Default to peserta
        
        if (!in_array($roleFilter, ['peserta', 'panitia'])) {
            $roleFilter = 'peserta';
        }

        $users = User::where('role', $roleFilter)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'roleFilter'));
    }

    public function create(Request $request)
    {
        $roleFilter = $request->get('role', 'peserta');
        return view('users.create', compact('roleFilter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:peserta,panitia',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Sync Spatie Permission role
        $spatieRole = Role::findOrCreate($request->role);
        $user->assignRole($spatieRole);

        return redirect()->route('users.index', ['role' => $request->role])
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roleFilter = $user->role;
        return view('users.edit', compact('user', 'roleFilter'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:peserta,panitia',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $oldRole = $user->role;
        $user->update($data);

        // Sync Spatie Permission role if role changed
        if ($oldRole !== $request->role) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('users.index', ['role' => $request->role])
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $role = $user->role;

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index', ['role' => $role])
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->registrations()->count() > 0) {
            return redirect()->route('users.index', ['role' => $role])
                ->with('error', 'User tidak dapat dihapus karena memiliki pendaftaran event.');
        }

        $user->delete();

        return redirect()->route('users.index', ['role' => $role])
            ->with('success', 'User berhasil dihapus.');
    }
}
