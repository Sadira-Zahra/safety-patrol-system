<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'ADMINISTRATOR')->get();
        return view('Master_User.administrator', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $validated['role'] = 'ADMINISTRATOR';
        $validated['nik'] = 'ADM-' . time(); // Generate NIK otomatis
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('administrators.index')
            ->with('success', 'Administrator berhasil ditambahkan');
    }

    public function show(User $administrator)
    {
        return response()->json($administrator);
    }

    public function update(Request $request, User $administrator)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $administrator->id,
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $administrator->update($validated);

        return redirect()->route('administrators.index')
            ->with('success', 'Administrator berhasil diupdate');
    }

    public function destroy(User $administrator)
    {
        $administrator->delete();
        return redirect()->route('administrators.index')
            ->with('success', 'Administrator berhasil dihapus');
    }
}
