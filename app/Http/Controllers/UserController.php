<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // index() — list semua user, filter by role
    public function index(Request $request)
    {
        $query = User::with('role')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->role) {
            $query->whereHas('role', fn($q) => $q->where('name', $request->role));
        }

        $users = $query->paginate(10);
        
        if ($request->wantsJson()) {
            return response()->json($users);
        }

        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    // show() — detail user
    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    // create() — form tambah user (admin only)
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // store() — simpan user baru (admin only)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'role_id'  => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil ditambahkan.');
    }

    // edit() — form edit user (admin only)
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // update() — update user (admin only)
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil diperbarui.');
    }

    // destroy() — hapus user (admin only)
    public function destroy(User $user)
    {
        // Cegah admin hapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                             ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil dihapus.');
    }
}
