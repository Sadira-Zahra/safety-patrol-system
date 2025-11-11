<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeptManagerController extends Controller
{
    public function index()
    {
        $users = User::with('departemen')->where('role', 'DEPT_MANAGER')->get();
        $departemens = Departemen::all(); // Tambahkan ini
        return view('Master_User.manager', compact('users', 'departemens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'departemen_id' => 'required|exists:departemen,id',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $validated['role'] = 'DEPT_MANAGER';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('dept-managers.index')
            ->with('success', 'Manager Departemen berhasil ditambahkan');
    }

    public function show(User $deptManager)
    {
        return response()->json($deptManager);
    }

    public function update(Request $request, User $deptManager)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik,' . $deptManager->id,
            'email' => 'required|email|unique:users,email,' . $deptManager->id,
            'departemen_id' => 'required|exists:departemen,id',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $deptManager->update($validated);

        return redirect()->route('dept-managers.index')
            ->with('success', 'Manager Departemen berhasil diupdate');
    }

    public function destroy(User $deptManager)
    {
        $deptManager->delete();
        return redirect()->route('dept-managers.index')
            ->with('success', 'Manager Departemen berhasil dihapus');
    }
}
