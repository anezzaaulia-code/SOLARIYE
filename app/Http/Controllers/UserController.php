<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List semua user (admin bisa lihat semua)
    public function index()
    {
        return view('admin.user.index', [
            'users' => User::orderBy('role')->get()
        ]);
    }

    // Create user baru (admin tambah admin/kasir)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,kasir',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'status'   => 'aktif',
        ]);

        return back()->with('success', 'User berhasil dibuat');
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,kasir',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return back()->with('success', 'User berhasil diupdate');
    }

    // Reset password user
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil direset');
    }

    // Nonaktifkan user
    public function nonaktifkan(User $user)
    {
        $user->update(['status' => 'nonaktif']);
        return back()->with('success', 'User dinonaktifkan');
    }

    // Aktifkan user
    public function aktifkan(User $user)
    {
        $user->update(['status' => 'aktif']);
        return back()->with('success', 'User diaktifkan');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}
