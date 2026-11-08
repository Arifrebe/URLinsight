<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Hanya akun aktif yang boleh login
        $credentials['is_active'] = true;

        // Proses autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended('/panel')
                    ->with('success', 'Selamat datang, Admin!');
            }

            return redirect()->intended('/')
    ->with('success', 'Berhasil login!');
        }

        // Jika autentikasi gagal
        return back()
            ->withErrors([
                'email' => 'Email, password, atau status akun tidak valid.',
            ])
            ->onlyInput('email');
    }


    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validasi data registrasi
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Simpan user
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus session lama
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout!');
    }
}