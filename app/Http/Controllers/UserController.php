<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // optionally: $this->middleware('role:admin')->except(['profile']);
    }

    public function index()
    {
        $users = User::orderBy('id','desc')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
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

        DB::transaction(function() use ($data) {
            User::create($data);
        });

        return redirect()->route('users.index')->with('success','User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'role' => 'required|in:admin,kasir',
        ]);

        $user->update($data);
        return redirect()->route('users.index')->with('success','User berhasil diupdate.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success','Password berhasil direset.');
    }

    public function toggleStatus(User $user)
    {
        // toggling via 'status' column (aktif/nonaktif)
        $user->status = ($user->status === 'aktif') ? 'nonaktif' : 'aktif';
        $user->save();

        return back()->with('success','Status user diubah.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error','Tidak bisa menghapus akun sendiri.');
        }

        // safe delete
        $user->delete();
        return redirect()->route('users.index')->with('success','User dihapus.');
    }
}
