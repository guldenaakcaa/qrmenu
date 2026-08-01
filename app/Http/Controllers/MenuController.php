<?php

namespace App\Http\Controllers;

use App\Models\AnaGrup;
use App\Models\UrunGrubu;
use App\Models\UrunKart;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private function resolveQrCode($qrcode = null)
    {
        if (!$qrcode && session()->has('current_qrcode')) {
            $qrcode = session()->get('current_qrcode');
        }
        if (!$qrcode && isset($_GET['masa'])) {
            $qrcode = $_GET['masa'];
        }
        if (!$qrcode && isset($_GET['qr'])) {
            $qrcode = $_GET['qr'];
        }

        $qrCodeCart = null;
        if ($qrcode) {
            $qrCodeCart = \App\Models\QrCodeKart::where('QRCode', $qrcode)
                            ->orWhere('Masaismi', $qrcode)
                            ->orWhere('Masa_id', $qrcode)
                            ->first();

            if (!$qrCodeCart) {
                $cleanName = str_replace('-', ' ', $qrcode);
                $masa = \App\Models\Masa::where('slug', $qrcode)
                            ->orWhere('isim', $qrcode)
                            ->orWhere('isim', $cleanName)
                            ->orWhere('isim', 'LIKE', '%' . $qrcode . '%')
                            ->orWhere('id', $qrcode)
                            ->first();

                if (!$masa && is_numeric($qrcode)) {
                    $masa = \App\Models\Masa::where('isim', 'Masa ' . $qrcode)->orWhere('isim', 'MASA ' . $qrcode)->first();
                }

                if ($masa) {
                    $qrCodeCart = \App\Models\QrCodeKart::where('Masa_id', $masa->id)->first();
                    if (!$qrCodeCart) {
                        $newQr = $masa->slug ?? ('masa-' . $masa->id);
                        $qrCodeCart = \App\Models\QrCodeKart::create([
                            'QRCode' => $newQr,
                            'Cari_id' => 1,
                            'QRTur' => 1,
                            'KullaniciParola' => '',
                            'Masa_id' => $masa->id,
                            'Masaismi' => $masa->isim,
                            'MusteriAd' => '',
                            'KullaniciAd' => '',
                            'Personel_id' => 0,
                            'Status' => 1
                        ]);
                    }
                }
            }

            if ($qrCodeCart) {
                $qrcode = $qrCodeCart->QRCode;
                session()->put('current_qrcode', $qrcode);
                session()->put('current_masaismi', $qrCodeCart->Masaismi);
            } else {
                session()->put('current_qrcode', $qrcode);
                if (is_numeric($qrcode)) {
                    session()->put('current_masaismi', 'Masa ' . $qrcode);
                } else {
                    session()->put('current_masaismi', ucwords(str_replace('-', ' ', $qrcode)));
                }
            }
        }

        return [$qrcode, $qrCodeCart];
    }

    public function index($qrcode = null)
    {
        $settings = \App\Models\Ayar::first();
        $mainCategories = AnaGrup::orderBy('siraNo')->get();
        
        [$qrcode, $qrCodeCart] = $this->resolveQrCode($qrcode);

        return view('home_menu', compact('mainCategories', 'settings', 'qrCodeCart', 'qrcode'));
    }

    public function show($mainCategory)
    {
        $mainCategory = urldecode($mainCategory);
        $settings = \App\Models\Ayar::first();
        
        $subCategories = UrunGrubu::where('AnaGrup', $mainCategory)->pluck('Urungrubu')->toArray();
        
        if (empty($subCategories)) {
            return redirect()->route('home');
        }

        [$qrcode, $qrCodeCart] = $this->resolveQrCode(null);

        $products = UrunKart::whereIn('UrunGrubu', $subCategories)->get();

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

        // Öne çıkan (Featured) ürünleri filtrele
        $featuredProducts = $products->where('one_cikan', 1);

        // Ana kategorileri de gönderiyoruz ki üst kısımda yatay menü olarak çıksın
        $mainCategories = AnaGrup::orderBy('siraNo')->get();

        return view('menu_draft', compact('categories', 'productsByCategory', 'settings', 'featuredProducts', 'mainCategory', 'mainCategories', 'qrcode', 'qrCodeCart'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $settings = \App\Models\Ayar::first();
        
        if (empty($query)) {
            return redirect()->route('home');
        }

        $products = UrunKart::where('UrunAd', 'LIKE', '%' . $query . '%')
            ->orWhere('UrunAciklama', 'LIKE', '%' . $query . '%')
            ->get();

        foreach ($products as $product) {
            $aciklama = mb_strtolower($product->UrunAciklama ?? '', 'UTF-8');
            if ($product->UrunAciklama === '0') $aciklama = '';
            
            $sutUrunleri = ['süt', 'peynir', 'krema', 'tereyağı', 'yoğurt', 'cheddar', 'mozzarella', 'kaşar'];
            $glutenUrunleri = ['un', 'galeta', 'ekmek', 'lavaş', 'makarna', 'malt', 'arpa', 'krep', 'hamur', 'dürüm'];
            $hayvansalUrunler = array_merge($sutUrunleri, ['et', 'tavuk', 'kıyma', 'yumurta', 'bal', 'sucuk', 'sosis', 'bacon', 'jambon', 'biftek']);
            $aciUrunler = ['acı', 'jalapeno', 'chili', 'pul biber', 'acılı'];

            $product->has_gluten = preg_match('/\b(' . implode('|', $glutenUrunleri) . ')\b/u', $aciklama) ? 1 : 0;
            $product->is_vegan = (!preg_match('/\b(' . implode('|', $hayvansalUrunler) . ')\b/u', $aciklama) && $aciklama !== '') ? 1 : 0;
            $product->is_aci = preg_match('/\b(' . implode('|', $aciUrunler) . ')\b/u', $aciklama) ? 1 : 0;

            if (!empty($aciklama)) {
                $product->malzeme_listesi = array_values(array_filter(array_map('trim', explode(',', $product->UrunAciklama))));
            } else {
                $product->malzeme_listesi = [];
            }

            if (empty($product->kalori) || empty($product->hazirlanma_suresi)) {
                $isimVeKategori = mb_strtolower($product->UrunAd . ' ' . $product->UrunGrubu, 'UTF-8');
                $kalori = 350; $sure = "10-15 dk";

                if (preg_match('/(pizza)/i', $isimVeKategori)) { $kalori = 850; $sure = "20-25 dk"; }
                elseif (preg_match('/(burger|hamburger)/i', $isimVeKategori)) { $kalori = 650; $sure = "15-20 dk"; }
                elseif (preg_match('/(salata|salad)/i', $isimVeKategori)) { $kalori = 220; $sure = "10-15 dk"; }
                elseif (preg_match('/(tatlı|pasta|kek|dessert|waffle|pancake|sufle|künefe|baklava)/i', $isimVeKategori)) { $kalori = 480; $sure = "5-10 dk"; }
                elseif (preg_match('/(kahve|coffee|çay|tea|içecek|meşrubat|drink|su|soda|ayran|kola|fanta|sprite|limonata|frappe|frozen|milkshake)/i', $isimVeKategori)) {
                    $kalori = 120; $sure = "3-5 dk";
                    if (preg_match('/(su|soda|çay|filtre)/i', $isimVeKategori)) $kalori = 5;
                }
                elseif (preg_match('/(kahvaltı|breakfast|serpme)/i', $isimVeKategori)) { $kalori = 750; $sure = "15-20 dk"; }
                elseif (preg_match('/(et|tavuk|ızgara|grill|kebap|steak|ana yemek|köfte|schnitzel)/i', $isimVeKategori)) { $kalori = 700; $sure = "20-30 dk"; }
                elseif (preg_match('/(atıştırmalık|snack|cips|patates|çıtır|soğan halkası)/i', $isimVeKategori)) { $kalori = 400; $sure = "10-15 dk"; }
                elseif (preg_match('/(çorba|soup)/i', $isimVeKategori)) { $kalori = 180; $sure = "5-10 dk"; }

                $kalori += ($product->id % 15) * 10; 
                
                if (empty($product->kalori)) $product->kalori = (string)$kalori;
                if (empty($product->hazirlanma_suresi)) $product->hazirlanma_suresi = $sure;
            }
        }

        $productsByCategory = $products->groupBy('UrunGrubu');
        $categories = $productsByCategory->keys();
        $featuredProducts = $products->where('one_cikan', 1);
        $mainCategory = 'Arama: ' . $query;
        $mainCategories = AnaGrup::orderBy('siraNo')->get();

        [$qrcode, $qrCodeCart] = $this->resolveQrCode(null);

        return view('menu_draft', compact('categories', 'productsByCategory', 'settings', 'featuredProducts', 'mainCategory', 'mainCategories', 'qrcode', 'qrCodeCart'));
    }
}
