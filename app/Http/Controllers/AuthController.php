<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $demoUsers = User::with('role', 'department')->where('is_active', true)->get();
        return view('auth.login', compact('demoUsers'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            AuditLog::log($user, 'Login', 'Authentication', 'Pengguna berhasil masuk ke sistem.');
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, ' . $user->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function quickLogin(Request $request)
    {
        $userId = $request->validate(['user_id' => 'required|exists:users,id'])['user_id'];
        $user = User::findOrFail($userId);
        
        Auth::login($user);
        $request->session()->regenerate();

        AuditLog::log($user, 'Simulasi Login Peran', 'Authentication', 'Beralih akses akun ke ' . $user->name . ' (' . $user->role->display_name . ')');
        return redirect()->route('dashboard')->with('success', 'Beralih ke akun: ' . $user->name . ' (' . $user->role->display_name . ')');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AuditLog::log($user, 'Logout', 'Authentication', 'Pengguna telah keluar dari sistem.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
