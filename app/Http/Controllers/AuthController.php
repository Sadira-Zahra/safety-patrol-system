<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman welcome
     */
    public function welcome()
    {
        // Set data kosong dulu
        $totalFindings = 0;
        $inProgress = 0;
        $completed = 0;
        $closed = 0;
        $rows = collect();

        return view('welcome', compact('totalFindings', 'inProgress', 'completed', 'closed', 'rows'));
    }

    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index'); // Perbaikan: tambahkan .index
        }

        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'Nama wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Cari user berdasarkan NAMA (case-insensitive)
        $user = User::whereRaw('LOWER(name) = ?', [strtolower($request->name)])->first();

        // Validasi kredensial
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['name' => 'Nama atau password salah'])
                ->withInput($request->only('name'));
        }

        // Login user
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        // Redirect berdasarkan role - PERBAIKAN
        return redirect()->route('dashboard.index')
            ->with('success', 'Selamat datang, ' . $user->name);
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah logout');
    }
}
