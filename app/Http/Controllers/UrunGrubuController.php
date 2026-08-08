<?php

namespace App\Http\Controllers;

use App\Models\UrunGrubu;
use Illuminate\Http\Request;

class UrunGrubuController extends Controller
{
    public function index()
    {
        $categories = UrunGrubu::orderBy('Sirano')->get();
        $anaGruplar = \App\Models\AnaGrup::pluck('anaGrup', 'id');
        
        $categoriesByMain = $categories->groupBy(function($item) use ($anaGruplar) {
            $mainId = $item->AnaGrup;
            if ($mainId && isset($anaGruplar[$mainId])) {
                return $anaGruplar[$mainId];
            }
            // If it's already a string name (legacy) and not an ID, use it
            if ($mainId && !is_numeric($mainId)) {
                return $mainId;
            }
            return 'Diğer / Ana Grup Yok';
        });
        
        return view('admin.categories.index', compact('categories', 'categoriesByMain'));
    }

    public function create()
    {
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }
        $mainCategories = \App\Models\AnaGrup::orderBy('siraNo')->get();
        return view('admin.categories.create', compact('mainCategories'));
    }

    public function store(Request $request)
    {
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        $request->validate([
            'Urungrubu' => 'required|string|max:255',
            'Sirano' => 'nullable|integer',
        ]);

        $data = $request->all();
        
        if (empty($data['Sirano'])) {
            $data['Sirano'] = (\App\Models\UrunGrubu::max('Sirano') ?? 0) + 1;
        }

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
        $mainCategories = \App\Models\AnaGrup::orderBy('siraNo')->get();
        return view('admin.categories.edit', compact('category', 'mainCategories'));
    }

    public function update(Request $request, $id)
    {
        if (session('admin_role') !== '0') {
            return redirect()->route('categories.index')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        $request->validate([
            'Urungrubu' => 'required|string|max:255',
            'Sirano' => 'nullable|integer',
        ]);

        $category = UrunGrubu::findOrFail($id);
        $data = $request->all();
        if (empty($data['Sirano'])) {
            $data['Sirano'] = $category->Sirano;
        }
        $category->update($data);

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
