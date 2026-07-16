<?php

namespace App\Http\Controllers;

use App\Models\UrunGrubu;
use Illuminate\Http\Request;

class UrunGrubuController extends Controller
{
    public function index()
    {
        $categories = UrunGrubu::orderBy('Sirano')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Urungrubu' => 'required|string|max:255',
            'Sirano' => 'nullable|integer',
        ]);

        UrunGrubu::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla eklendi.');
    }

    public function edit($id)
    {
        $category = UrunGrubu::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Urungrubu' => 'required|string|max:255',
            'Sirano' => 'nullable|integer',
        ]);

        $category = UrunGrubu::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $category = UrunGrubu::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla silindi.');
    }
}
