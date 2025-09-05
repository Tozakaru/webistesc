<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Coba login dengan username + password
        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();

            // Blokir jika akun nonaktif
            if (!Auth::user()->is_active) {
                $this->_logout($request);
                return back()->withErrors([
                    'username' => 'Akun Anda dinonaktifkan. Hubungi admin.',
                ])->onlyInput('username');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    // ====== Nonaktifkan pendaftaran mandiri ======
    public function registerView()
    {
        abort(404); // atau return redirect('/login');
    }

    public function register(Request $request)
    {
        abort(404); // atau return redirect('/login');
    }
    // =============================================

    public function _logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $this->_logout($request);
        return redirect('/login');
    }
}
