<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('name')->paginate(20);
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_type' => 'required|string',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ]);

        Material::create([
            'name' => $request->name,
            'unit' => $request->unit,
            'material_type' => $request->material_type,
            'current_stock' => 0,
        ]);
        
        return redirect()->route('materials.index')->with('success', 'Material added successfully.');
    }
}
