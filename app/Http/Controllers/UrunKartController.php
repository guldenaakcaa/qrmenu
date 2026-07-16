<?php

namespace App\Http\Controllers;

use App\Models\UrunKart;
use App\Models\UrunGrubu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UrunKartController extends Controller
{
    public function index()
    {
        $products = UrunKart::all();
        $categories = UrunGrubu::all()->keyBy('id');
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = UrunGrubu::orderBy('Sirano')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'UrunAd' => 'required|string|max:255',
            'UrunAciklama' => 'nullable|string',
            'FixFiyat' => 'nullable|numeric',
            'kalori' => 'nullable|string|max:255',
            'hazirlanma_suresi' => 'nullable|string|max:255',
            'UrunResimPath' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'UrunGrubu_id' => 'required|integer',
        ]);

        $data = $request->except('UrunResimPath');

        if ($request->hasFile('UrunResimPath')) {
            $path = $request->file('UrunResimPath')->store('products', 'public');
            $data['UrunResimPath'] = $path;
        }

        UrunKart::create($data);

        return redirect()->route('products.index')->with('success', 'Ürün başarıyla eklendi.');
    }

    public function edit($id)
    {
        $product = UrunKart::findOrFail($id);
        $categories = UrunGrubu::orderBy('Sirano')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'UrunAd' => 'required|string|max:255',
            'UrunAciklama' => 'nullable|string',
            'FixFiyat' => 'nullable|numeric',
            'kalori' => 'nullable|string|max:255',
            'hazirlanma_suresi' => 'nullable|string|max:255',
            'UrunResimPath' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'UrunGrubu_id' => 'required|integer',
        ]);

        $product = UrunKart::findOrFail($id);
        $data = $request->except('UrunResimPath');

        if ($request->hasFile('UrunResimPath')) {
            // Eski resmi sunucudan sil (eğer varsa ve geçerliyse)
            if ($product->UrunResimPath && $product->UrunResimPath !== '0') {
                Storage::disk('public')->delete($product->UrunResimPath);
            }

            // Yeni resmi kaydet
            $path = $request->file('UrunResimPath')->store('products', 'public');
            $data['UrunResimPath'] = $path;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $product = UrunKart::findOrFail($id);

        // Ürün silinirken bağlı olduğu resmi de sunucudan fiziksel olarak sil
        if ($product->UrunResimPath && $product->UrunResimPath !== '0') {
            Storage::disk('public')->delete($product->UrunResimPath);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Ürün başarıyla silindi.');
    }
}
