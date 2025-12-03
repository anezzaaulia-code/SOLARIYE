<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query();

        if ($request->search) {
            $q->where('nama', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $q->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,kasir',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['status'] = 'aktif'; // Default status aktif

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'role' => 'required|in:admin,kasir',
            'status' => 'nullable|in:aktif,nonaktif'
        ]);

        $user->update($data);

        return redirect()->route('users.index')->with('success','User berhasil diupdate.');
    }

    // --- BAGIAN YANG TADI HILANG (TOGGLE STATUS) ---
    public function toggleStatus(User $user)
    {
        // Ubah status: Jika aktif jadi nonaktif, dan sebaliknya
        $user->status = ($user->status === 'aktif') ? 'nonaktif' : 'aktif';
        $user->save();

        return back()->with('success', 'Status user berhasil diubah menjadi ' . $user->status);
    }

    // --- BAGIAN RESET PASSWORD ---
    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success', 'Password berhasil direset.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success','User dihapus.');
    }
}