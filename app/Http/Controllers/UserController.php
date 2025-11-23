<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ============================
    // LIST USER
    // ============================
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('users.index', compact('users'));
    }

    // ============================
    // FORM CREATE
    // ============================
    public function create()
    {
        return view('users.create');
    }

    // ============================
    // STORE USER BARU
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => ['required', Rule::in(['admin','kasir'])],
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'aktif',
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // ============================
    // EDIT USER
    // ============================
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // ============================
    // UPDATE USER
    // ============================
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin','kasir'])],
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    // ============================
    // DELETE USER
    // ============================
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin tidak boleh dihapus.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    // ============================
    // RESET PASSWORD
    // ============================
    public function resetPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('password123'), // default reset
        ]);

        return back()->with('success', 'Password berhasil direset ke: password123');
    }

    // ============================
    // NONAKTIFKAN / AKTIFKAN USER
    // ============================
    public function toggleStatus(User $user)
    {
        $user->status = ($user->status === 'aktif') ? 'nonaktif' : 'aktif';
        $user->save();

        return back()->with('success', 'Status user berhasil diubah.');
    }
}
