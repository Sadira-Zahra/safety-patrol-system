<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeptPicController extends Controller
{
    public function index()
    {
        $users = User::with('departemen')->where('role', 'DEPT_PIC')->get();
        $departemens = Departemen::all();
        
        // PERBAIKI PATH VIEW - ganti dengan yang benar
        return view('master_user.pic_departemen', compact('users', 'departemens'));
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

        $validated['role'] = 'DEPT_PIC';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('dept-pics.index')
            ->with('success', 'PIC Departemen berhasil ditambahkan');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $deptPic = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik,' . $deptPic->id,
            'email' => 'required|email|unique:users,email,' . $deptPic->id,
            'departemen_id' => 'required|exists:departemen,id',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $deptPic->update($validated);

        return redirect()->route('dept-pics.index')
            ->with('success', 'PIC Departemen berhasil diupdate');
    }

    public function destroy($id)
    {
        $deptPic = User::findOrFail($id);
        $deptPic->delete();
        
        return redirect()->route('dept-pics.index')
            ->with('success', 'PIC Departemen berhasil dihapus');
    }
}
