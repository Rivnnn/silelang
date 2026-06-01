<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Proses Masuk (Login)
     */
    public function loginProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // CEK AKTIVASI: Jika petugas belum aktif, paksa logout
            if ($user->role === 'petugas' && !$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Akun Anda belum diaktifkan oleh admin. Mohon tunggu.');
            }
            
            // Redirect sesuai role menggunakan path langsung agar aman
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
            } else {
                return redirect()->intended('/petugas/dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
            }
        }

        // Jika login gagal
        return back()->with('error', 'Email atau password salah')->withInput();
    }

    /**
     * Tampilkan halaman register
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Proses Registrasi User Baru
     */
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6', // Tidak pakai 'confirmed' sesuai View Anda
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // 1. Simpan data user ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
            'is_active' => false, // Set false agar dikonfirmasi admin dulu
        ]);

        // 2. Redirect ke login (JANGAN Auth::login karena belum aktif)
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan tunggu aktivasi dari admin sebelum login.');
    }

    /**
     * Proses Keluar (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Berhasil logout');
    }
}