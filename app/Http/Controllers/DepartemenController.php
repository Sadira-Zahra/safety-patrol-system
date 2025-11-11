<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index()
    {
        $departemens = Departemen::all();
        return view('Master_Data.departemen', compact('departemens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departemen,name'
        ]);

        Departemen::create($validated);

        return redirect()->route('departemens.index')
            ->with('success', 'Departemen berhasil ditambahkan');
    }

    public function show(Departemen $departemen)
    {
        return response()->json($departemen);
    }

    public function update(Request $request, Departemen $departemen)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departemen,name,' . $departemen->id
        ]);

        $departemen->update($validated);

        return redirect()->route('departemens.index')
            ->with('success', 'Departemen berhasil diupdate');
    }

    public function destroy(Departemen $departemen)
    {
        try {
            $departemen->delete();
            return redirect()->route('departemens.index')
                ->with('success', 'Departemen berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('departemens.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih digunakan');
        }
    }
}
