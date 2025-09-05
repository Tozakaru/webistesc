<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ========== Admin CRUD ==========

    public function index(Request $request)
{
    $search = $request->input('search');

    $users = User::when($search, function ($q) use ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('username', 'like', "%{$search}%");
            });
        })
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    // tambahkan ini ⤵
    $roles = [1 => 'Admin', 2 => 'User'];

    return view('pages.account-list.index', compact('users', 'search', 'roles'));
}


    public function create()
    {
        // role_id: 1=Admin, 2=User (sesuai seed di DB)
        $roles = [
            1 => 'Admin',
            2 => 'User',
        ];
        return view('pages.account-list.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'role_id'  => ['required', Rule::in([1,2])],
            'is_active'=> ['nullable', 'boolean'],
        ]);

        User::create([
            'name'      => $data['name'],
            'username'  => $data['username'],
            'password'  => Hash::make($data['password']),
            'role_id'   => (int)$data['role_id'],
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = [
            1 => 'Admin',
            2 => 'User',
        ];
        return view('pages.account-list.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', Rule::unique('users','username')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id'  => ['required', Rule::in([1,2])],
            'is_active'=> ['nullable', 'boolean'],
        ]);

        $payload = [
            'name'      => $data['name'],
            'username'  => $data['username'],
            'role_id'   => (int)$data['role_id'],
            'is_active' => (bool)($data['is_active'] ?? $user->is_active),
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()->route('users.index')->with('success', 'Akun diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Akun dihapus.');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('success', 'Akun diaktifkan.');
    }

    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);
        return back()->with('success', 'Akun dinonaktifkan.');
    }

    // ========== Profil (opsional) ==========

    public function profile_view()
    {
        return view('pages.profile.index');
    }

    public function update_profile(Request $request, $userId)
    {
        $request->validate([
            'name'     => ['required','string','min:3','max:255'],
            'username' => ['nullable','string','min:3','max:255','alpha_dash', Rule::unique('users','username')->ignore($userId)],
        ]);

        $user = User::findOrFail($userId);
        $user->name = $request->input('name');
        if ($request->filled('username')) {
            $user->username = $request->input('username');
        }
        $user->save();

        return back()->with('success', 'Berhasil mengubah data');
    }

    public function change_password_view()
    {
        return view('pages.profile.change-password');
    }

    public function change_password(Request $request, $userId)
    {
        $request->validate([
            'old_password' => ['required','min:8'],
            'new_password' => ['required','min:8'],
        ]);

        $user = User::findOrFail($userId);

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return back()->with('error', 'Gagal mengubah password, password lama tidak valid');
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return back()->with('success', 'Berhasil mengubah password');
    }
}
