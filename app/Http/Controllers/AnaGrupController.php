<?php

namespace App\Http\Controllers;

use App\Models\AnaGrup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnaGrupController extends Controller
{
    public function index()
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        
        $mainCategories = AnaGrup::orderBy('siraNo')->get();
        return view('admin.main_categories.index', compact('mainCategories'));
    }

    public function create()
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        
        return view('admin.main_categories.create');
    }

    public function store(Request $request)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');

        $request->validate([
            'anaGrup' => 'required|string|max:100',
            'siraNo' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = $request->only(['anaGrup', 'siraNo']);

        // Varsayılan değerler
        if (empty($data['siraNo'])) {
            $data['siraNo'] = (AnaGrup::max('siraNo') ?? 0) + 1;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('anagruplar', 'public');
            $data['anaGrupResimPath'] = $path;
        } else {
            $data['anaGrupResimPath'] = null; // Veya empty string
        }

        AnaGrup::create($data);

        return redirect()->route('main-categories.index')->with('success', 'Ana grup başarıyla eklendi.');
    }

    public function edit($id)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');

        $mainCategory = AnaGrup::findOrFail($id);
        return view('admin.main_categories.edit', compact('mainCategory'));
    }

    public function update(Request $request, $id)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');

        $request->validate([
            'anaGrup' => 'required|string|max:100',
            'siraNo' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $mainCategory = AnaGrup::findOrFail($id);
        
        $data = $request->only(['anaGrup', 'siraNo']);
        if (empty($data['siraNo'])) {
            $data['siraNo'] = $mainCategory->siraNo;
        }

        if ($request->has('remove_image') && $request->remove_image == 1) {
            $data['anaGrupResimPath'] = null;
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('anagruplar', 'public');
            $data['anaGrupResimPath'] = $path;
        }

        $mainCategory->update($data);

        return redirect()->route('main-categories.index')->with('success', 'Ana grup başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');

        $mainCategory = AnaGrup::findOrFail($id);
        $mainCategory->delete();

        // İsteğe bağlı olarak bu ana gruba bağlı alt kategorilerin AnaGrup değerini temizleyebiliriz
        // \App\Models\UrunGrubu::where('AnaGrup', $mainCategory->anaGrup)->update(['AnaGrup' => '']);

        return redirect()->route('main-categories.index')->with('success', 'Ana grup silindi.');
    }
}
