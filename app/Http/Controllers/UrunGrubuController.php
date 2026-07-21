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
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        $request->validate([
            'Urungrubu' => 'required|string|max:255',
            'Sirano' => 'nullable|integer|unique:t_urungrubu,Sirano',
        ], [
            'Sirano.unique' => 'Bu sıra numarası zaten başka bir kategoride kullanılıyor. Lütfen farklı bir numara girin.'
        ]);

        $data = $request->all();
        
        if (!isset($data['UrunGrubu_id'])) {
            $data['UrunGrubu_id'] = (\App\Models\UrunGrubu::max('UrunGrubu_id') ?? 0) + 1;
        }
        if (!isset($data['AnaGrup'])) {
            $data['AnaGrup'] = '';
        }

        UrunGrubu::create($data);

        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla eklendi.');
    }

    public function edit($id)
    {
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }
        $category = UrunGrubu::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        $request->validate([
            'Urungrubu' => 'required|string|max:255',
            'Sirano' => 'nullable|integer|unique:t_urungrubu,Sirano,' . $id,
        ], [
            'Sirano.unique' => 'Bu sıra numarası zaten başka bir kategoride kullanılıyor. Lütfen farklı bir numara girin.'
        ]);

        $category = UrunGrubu::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        $category = UrunGrubu::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla silindi.');
    }
}
