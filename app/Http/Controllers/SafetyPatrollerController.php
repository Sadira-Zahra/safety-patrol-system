<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SafetyPatrollerController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'SAFETY_PATROLLER')->get();
        return view('Master_User.safety_patroller', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $validated['role'] = 'SAFETY_PATROLLER';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('safety-patrollers.index')
            ->with('success', 'Safety Patroller berhasil ditambahkan');
    }

    public function show(User $safetyPatroller)
    {
        return response()->json($safetyPatroller);
    }

    public function update(Request $request, User $safetyPatroller)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik,' . $safetyPatroller->id,
            'email' => 'required|email|unique:users,email,' . $safetyPatroller->id,
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $safetyPatroller->update($validated);

        return redirect()->route('safety-patrollers.index')
            ->with('success', 'Safety Patroller berhasil diupdate');
    }

    public function destroy(User $safetyPatroller)
    {
        $safetyPatroller->delete();
        return redirect()->route('safety-patrollers.index')
            ->with('success', 'Safety Patroller berhasil dihapus');
    }
}
