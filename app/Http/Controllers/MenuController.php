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
            // $product->has_lactose veritabanından geliyor
            $product->has_gluten = preg_match('/\b(' . implode('|', $glutenUrunleri) . ')\b/u', $aciklama) ? 1 : 0;
            $product->is_vegan = (!preg_match('/\b(' . implode('|', $hayvansalUrunler) . ')\b/u', $aciklama) && $aciklama !== '') ? 1 : 0;
            $product->is_aci = preg_match('/\b(' . implode('|', $aciUrunler) . ')\b/u', $aciklama) ? 1 : 0;

            if (!empty($aciklama)) {
                $product->malzeme_listesi = array_values(array_filter(array_map('trim', explode(',', $product->UrunAciklama))));
            } else {
                $product->malzeme_listesi = [];
            }

            // Otomatik Kalori ve Süre Tahmini (Veritabanında boşsa)
            if (empty($product->kalori) || empty($product->hazirlanma_suresi)) {
                $isimVeKategori = mb_strtolower($product->UrunAd . ' ' . $product->UrunGrubu, 'UTF-8');
                
                $kalori = 350;
                $sure = "10-15 dk";

                if (preg_match('/(pizza)/i', $isimVeKategori)) {
                    $kalori = 850; $sure = "20-25 dk";
                } elseif (preg_match('/(burger|hamburger)/i', $isimVeKategori)) {
                    $kalori = 650; $sure = "15-20 dk";
                } elseif (preg_match('/(salata|salad)/i', $isimVeKategori)) {
                    $kalori = 220; $sure = "10-15 dk";
                } elseif (preg_match('/(tatlı|pasta|kek|dessert|waffle|pancake|sufle|künefe|baklava)/i', $isimVeKategori)) {
                    $kalori = 480; $sure = "5-10 dk";
                } elseif (preg_match('/(kahve|coffee|çay|tea|içecek|meşrubat|drink|su|soda|ayran|kola|fanta|sprite|limonata|frappe|frozen|milkshake)/i', $isimVeKategori)) {
                    $kalori = 120; $sure = "3-5 dk";
                    if (preg_match('/(su|soda|çay|filtre)/i', $isimVeKategori)) $kalori = 5;
                } elseif (preg_match('/(kahvaltı|breakfast|serpme)/i', $isimVeKategori)) {
                    $kalori = 750; $sure = "15-20 dk";
                } elseif (preg_match('/(et|tavuk|ızgara|grill|kebap|steak|ana yemek|köfte|schnitzel)/i', $isimVeKategori)) {
                    $kalori = 700; $sure = "20-30 dk";
                } elseif (preg_match('/(atıştırmalık|snack|cips|patates|çıtır|soğan halkası)/i', $isimVeKategori)) {
                    $kalori = 400; $sure = "10-15 dk";
                } elseif (preg_match('/(çorba|soup)/i', $isimVeKategori)) {
                    $kalori = 180; $sure = "5-10 dk";
                }

                // Rastgelelik katmak için id tabanlı küçük bir değişim
                $kalori += ($product->id % 15) * 10; 
                
                if (empty($product->kalori)) $product->kalori = (string)$kalori;
                if (empty($product->hazirlanma_suresi)) $product->hazirlanma_suresi = $sure;
            }
        }

        // Ürünleri metin tabanlı 'UrunGrubu' sütununa göre grupla
        $productsByCategory = $products->groupBy('UrunGrubu');

        // Kategori isimleri (String koleksiyonu)
        $categories = $productsByCategory->keys();
        $settings = \App\Models\Ayar::first();

        // Öne çıkan (Featured) ürünleri filtrele
        $featuredProducts = $products->where('one_cikan', 1);

        return view('menu_draft', compact('categories', 'productsByCategory', 'settings', 'featuredProducts'));
    }
}
