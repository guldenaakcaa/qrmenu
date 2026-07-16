<?php

namespace App\Http\Controllers;

use App\Models\UrunGrubu;
use App\Models\UrunKart;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $products = UrunKart::all();

        foreach ($products as $product) {
            $aciklama = mb_strtolower($product->UrunAciklama ?? '', 'UTF-8');
            if ($product->UrunAciklama === '0') $aciklama = '';
            
            // Algoritma için anahtar kelime matrisleri
            $sutUrunleri = ['süt', 'peynir', 'krema', 'tereyağı', 'yoğurt', 'cheddar', 'mozzarella', 'kaşar'];
            $glutenUrunleri = ['un', 'galeta', 'ekmek', 'lavaş', 'makarna', 'malt', 'arpa', 'krep', 'hamur', 'dürüm'];
            $hayvansalUrunler = array_merge($sutUrunleri, ['et', 'tavuk', 'kıyma', 'yumurta', 'bal', 'sucuk', 'sosis', 'bacon', 'jambon', 'biftek']);
            $aciUrunler = ['acı', 'jalapeno', 'chili', 'pul biber', 'acılı'];

            // Etiketleme (Flagging) - Tam Kelime (Word Boundary) Analizi
            $product->has_lactose = preg_match('/\b(' . implode('|', $sutUrunleri) . ')\b/u', $aciklama) ? 1 : 0;
            $product->has_gluten = preg_match('/\b(' . implode('|', $glutenUrunleri) . ')\b/u', $aciklama) ? 1 : 0;
            $product->is_vegan = (!preg_match('/\b(' . implode('|', $hayvansalUrunler) . ')\b/u', $aciklama) && $aciklama !== '') ? 1 : 0;
            $product->is_aci = preg_match('/\b(' . implode('|', $aciUrunler) . ')\b/u', $aciklama) ? 1 : 0;

            if (!empty($aciklama)) {
                $product->malzeme_listesi = array_values(array_filter(array_map('trim', explode(',', $product->UrunAciklama))));
            } else {
                $product->malzeme_listesi = [];
            }
        }

        // Ürünleri metin tabanlı 'UrunGrubu' sütununa göre grupla
        $productsByCategory = $products->groupBy('UrunGrubu');

        // Kategori isimleri (String koleksiyonu)
        $categories = $productsByCategory->keys();

        return view('menu_draft', compact('categories', 'productsByCategory'));
    }
}
