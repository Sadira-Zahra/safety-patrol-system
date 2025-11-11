<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all();
        return view('Master_Data.grades', compact('grades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:grades,code',
            'sla_days' => 'required|integer|min:1'
        ]);

        Grade::create($validated);

        return redirect()->route('grades.index')
            ->with('success', 'Grade berhasil ditambahkan');
    }

    public function show(Grade $grade)
    {
        return response()->json($grade);
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:grades,code,' . $grade->id,
            'sla_days' => 'required|integer|min:1'
        ]);

        $grade->update($validated);

        return redirect()->route('grades.index')
            ->with('success', 'Grade berhasil diupdate');
    }

    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            return redirect()->route('grades.index')
                ->with('success', 'Grade berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('grades.index')
                ->with('error', 'Grade tidak dapat dihapus karena masih digunakan');
        }
    }
}
