<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

/**
 * UserController Admin - kelola data user
 * CRUD: tampilin, tambah, edit, hapus user
 */
class UserController extends Controller
{
    public function __construct()
    {
        // Pastiin yang akses udah login dan punya role admin
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Tampilin semua daftar user
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tambah user baru
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru ke database
     * Password otomatis di-hash pake bcrypt
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,admin',
        ]);

        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat!');
    }

    /**
     * Form edit user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user
     * Password cuma diupdate kalo diisi (optional)
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:user,admin',
        ]);

        // Update password cuma kalo diisi
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    /**
     * Hapus user dari database
     * Ga bisa hapus akun sendiri biar ga ke-lock
     */
    public function destroy(User $user)
    {
        // Cegah admin hapus akunnya sendiri
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
