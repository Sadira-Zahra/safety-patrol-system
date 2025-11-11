<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SafetyAdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'SAFETY_ADMIN')->get();
        return view('Master_User.safety_admin', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $validated['role'] = 'SAFETY_ADMIN';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('safety-admins.index')
            ->with('success', 'Safety Admin berhasil ditambahkan');
    }

    public function show(User $safetyAdmin)
    {
        return response()->json($safetyAdmin);
    }

    public function update(Request $request, User $safetyAdmin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik,' . $safetyAdmin->id,
            'email' => 'required|email|unique:users,email,' . $safetyAdmin->id,
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $safetyAdmin->update($validated);

        return redirect()->route('safety-admins.index')
            ->with('success', 'Safety Admin berhasil diupdate');
    }

    public function destroy(User $safetyAdmin)
    {
        $safetyAdmin->delete();
        return redirect()->route('safety-admins.index')
            ->with('success', 'Safety Admin berhasil dihapus');
    }
}
