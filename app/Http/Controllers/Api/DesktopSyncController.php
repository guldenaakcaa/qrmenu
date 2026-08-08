<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masa;
use App\Models\Kasa;
use Illuminate\Support\Facades\DB;

class DesktopSyncController extends Controller
{
    /**
     * Masaüstü uygulaması için API Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = \Illuminate\Support\Facades\DB::table('users')->where('email', $request->email)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            // Token üret
            $token = \Illuminate\Support\Str::random(60);

            // Veritabanına kaydet
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', $user->id)
                ->update(['api_token' => $token]);

            return response()->json([
                'success' => true,
                'message' => 'Giriş başarılı.',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Giriş bilgileri hatalı.'
        ], 401);
    }

    /**
     * Masaüstü uygulamasından masaların güncel durumunu alır.
     * Beklenen JSON formatı:
     * {
     *   "masalar": [
     *      {"isim": "Masa 1", "durum": 1, "guncel_tutar": 550.00},
     *      {"isim": "Bahçe 1", "durum": 0, "guncel_tutar": 0.00}
     *   ]
     * }
     */
    public function syncTables(Request $request)
    {
        $request->validate([
            'masalar' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->masalar as $masaData) {
                $masa = Masa::where('isim', $masaData['isim'])->first();
                if (!$masa) {
                    $slugBase = \Illuminate\Support\Str::slug($masaData['isim']);
                    if (empty($slugBase)) $slugBase = 'masa';
                    $qrCode = $slugBase . '-' . strtolower(\Illuminate\Support\Str::random(4));
                    while (\App\Models\QrCodeKart::where('QRCode', $qrCode)->exists() || \App\Models\Masa::where('slug', $qrCode)->exists()) {
                        $qrCode = $slugBase . '-' . strtolower(\Illuminate\Support\Str::random(4));
                    }
                    $masa = Masa::create([
                        'isim' => $masaData['isim'],
                        'slug' => $qrCode,
                        'durum' => $masaData['durum'],
                        'guncel_tutar' => $masaData['guncel_tutar'] ?? 0
                    ]);
                    \App\Models\QrCodeKart::create([
                        'QRCode' => $qrCode,
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
                } else {
                    $masa->update([
                        'durum' => $masaData['durum'],
                        'guncel_tutar' => $masaData['guncel_tutar'] ?? 0
                    ]);
                }

                // Siparişleri güncelle (Masa detayları)
                if (isset($masaData['siparisler']) && is_array($masaData['siparisler'])) {
                    // Mevcut siparişleri temizle
                    \App\Models\MasaSiparis::where('masa_isim', $masa->isim)->delete();
                    
                    // Yeni siparişleri ekle
                    foreach ($masaData['siparisler'] as $siparis) {
                        \App\Models\MasaSiparis::create([
                            'masa_isim' => $masa->isim,
                            'urun_adi' => $siparis['urun_adi'] ?? 'Bilinmeyen Ürün',
                            'adet' => $siparis['adet'] ?? 1,
                            'fiyat' => $siparis['fiyat'] ?? 0,
                            'siparis_saati' => $siparis['siparis_saati'] ?? now()
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Masalar başarıyla eşitlendi.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Hata: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Masaüstü uygulamasından günlük kasa verisini alır.
     * Beklenen JSON formatı:
     * {
     *   "tarih": "2026-07-22",
     *   "nakit_toplam": 1500.50,
     *   "kredi_karti_toplam": 3000.00
     * }
     */
    public function syncKasa(Request $request)
    {
        $request->validate([
            'tarih' => 'required|date',
            'nakit_toplam' => 'required|numeric',
            'kredi_karti_toplam' => 'required|numeric',
            'yemek_karti_toplam' => 'nullable|numeric',
            'veresiye_toplam' => 'nullable|numeric'
        ]);

        $yemek_karti = $request->input('yemek_karti_toplam', 0);
        $veresiye = $request->input('veresiye_toplam', 0);

        $genel_toplam = $request->nakit_toplam + $request->kredi_karti_toplam + $yemek_karti + $veresiye;

        Kasa::updateOrCreate(
            ['tarih' => $request->tarih],
            [
                'nakit_toplam' => $request->nakit_toplam,
                'kredi_karti_toplam' => $request->kredi_karti_toplam,
                'yemek_karti_toplam' => $yemek_karti,
                'veresiye_toplam' => $veresiye,
                'genel_toplam' => $genel_toplam
            ]
        );

        // Kasa işlemlerini güncelle (Kasa detayları)
        if ($request->has('islemler') && is_array($request->islemler)) {
            // Önce bu güne ait mevcut işlemleri temizle (tekrar senkronize edildiğinde mükerrer olmaması için)
            \App\Models\KasaIslem::where('tarih', $request->tarih)->delete();

            foreach ($request->islemler as $islem) {
                \App\Models\KasaIslem::create([
                    'tarih' => $request->tarih,
                    'islem_saati' => $islem['islem_saati'] ?? now(),
                    'turu' => $islem['turu'] ?? 'Belirsiz',
                    'tutar' => $islem['tutar'] ?? 0,
                    'aciklama' => $islem['aciklama'] ?? null
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Kasa verisi başarıyla eşitlendi.']);
    }

    /**
     * Mevcut durumu masaüstüne veya mobile döner.
     */
    public function getStatus()
    {
        $masalar = Masa::all();
        $gunluk_kasa = Kasa::where('tarih', date('Y-m-d'))->first();
        $cagrilar = \App\Models\QrCodeCagri::where('Status', 0)->get();

        return response()->json([
            'masalar' => $masalar,
            'kasa' => $gunluk_kasa,
            'cagrilar' => $cagrilar
        ]);
    }
    /**
     * Masaüstü uygulamasından menü verilerini (Kategori ve Ürünler) alır ve web'e kaydeder/günceller.
     * Beklenen JSON formatı:
     * {
     *   "ana_gruplar": [...],
     *   "urun_gruplari": [...],
     *   "urunler": [...]
     * }
     */
    public function syncMenuPost(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. AnaGruplar (Üst Kategoriler)
            if ($request->has('ana_gruplar') && is_array($request->ana_gruplar)) {
                foreach ($request->ana_gruplar as $ag) {
                    if (isset($ag['id'])) {
                        $updateData = [
                            'anaGrup' => $ag['anaGrup'] ?? '',
                            'siraNo' => $ag['siraNo'] ?? 0,
                        ];
                        if (!empty($ag['anaGrupResimPath'])) {
                            $updateData['anaGrupResimPath'] = $ag['anaGrupResimPath'];
                        }
                        \App\Models\AnaGrup::updateOrCreate(['id' => $ag['id']], $updateData);
                    }
                }
            }

            // 2. UrunGruplari (Alt Kategoriler)
            if ($request->has('urun_gruplari') && is_array($request->urun_gruplari)) {
                foreach ($request->urun_gruplari as $ug) {
                    if (isset($ug['UrunGrubu_id'])) {
                        $updateData = [
                            'Urungrubu' => $ug['Urungrubu'] ?? '',
                            'Sirano' => $ug['Sirano'] ?? 0,
                            'Dil_id' => $ug['Dil_id'] ?? 1,
                            'AnaGrup' => $ug['AnaGrup'] ?? null
                        ];
                        if (!empty($ug['UrunGrubuResimPath'])) {
                            $updateData['UrunGrubuResimPath'] = $ug['UrunGrubuResimPath'];
                        }
                        \App\Models\UrunGrubu::updateOrCreate(['UrunGrubu_id' => $ug['UrunGrubu_id']], $updateData);
                    }
                }
            }

            // 3. UrunKartlari (Ürünler)
            if ($request->has('urunler') && is_array($request->urunler)) {
                foreach ($request->urunler as $urun) {
                    if (isset($urun['id'])) {
                        $updateData = [
                            'UrunTip' => $urun['UrunTip'] ?? 0,
                            'UrunKod' => $urun['UrunKod'] ?? '',
                            'UrunAd' => $urun['UrunAd'] ?? '',
                            'UrunAdKisa' => $urun['UrunAdKisa'] ?? '',
                            'UrunAciklama' => $urun['UrunAciklama'] ?? null,
                            'alerjenler' => $urun['alerjenler'] ?? null,
                            'UrunGrubu' => $urun['UrunGrubu'] ?? null,
                            'UrunGrubu_id' => $urun['UrunGrubu_id'] ?? null,
                            'FixFiyat' => $urun['FixFiyat'] ?? 0,
                            'SiraNo' => $urun['SiraNo'] ?? 0,
                            'P_Yarim' => $urun['P_Yarim'] ?? 0,
                            'P_Birbucuk' => $urun['P_Birbucuk'] ?? 0,
                            'P_Duble' => $urun['P_Duble'] ?? 0,
                            'Porsiyon' => $urun['Porsiyon'] ?? 0,
                            'ExtraOzellik' => $urun['ExtraOzellik'] ?? null,
                            'Barkod' => $urun['Barkod'] ?? null,
                            'UrunBirim' => $urun['UrunBirim'] ?? null,
                            'FixFiyat2' => $urun['FixFiyat2'] ?? 0,
                            'FixFiyat3' => $urun['FixFiyat3'] ?? 0,
                            'Departman' => $urun['Departman'] ?? null,
                            'AltGrup' => $urun['AltGrup'] ?? null,
                            'Ch_Gram' => $urun['Ch_Gram'] ?? 0,
                            'CokSatan' => $urun['CokSatan'] ?? 0,
                            'textraozellik' => $urun['textraozellik'] ?? null,
                            'P_Tanim' => $urun['P_Tanim'] ?? null,
                            'kalori' => $urun['kalori'] ?? null,
                            'hazirlanma_suresi' => $urun['hazirlanma_suresi'] ?? null,
                            'has_lactose' => $urun['has_lactose'] ?? 0,
                            'Upd_Tarih' => $urun['Upd_Tarih'] ?? now()
                        ];
                        if (!empty($urun['UrunResimPath'])) {
                            $updateData['UrunResimPath'] = $urun['UrunResimPath'];
                        }
                        \App\Models\UrunKart::updateOrCreate(['id' => $urun['id']], $updateData);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Menü verileri başarıyla eşitlendi.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Hata: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Web'deki güncel menü verilerini masaüstüne çeker.
     */
    public function syncMenuGet()
    {
        $ana_gruplar = \App\Models\AnaGrup::all();
        $urun_gruplari = \App\Models\UrunGrubu::all();
        $urunler = \App\Models\UrunKart::all();

        return response()->json([
            'ana_gruplar' => $ana_gruplar,
            'urun_gruplari' => $urun_gruplari,
            'urunler' => $urunler
        ]);
    }

    /**
     * Masaüstü uygulamasının bekleyen garson çağrılarını çekmesi için kullanılır.
     */
    public function getWaiterCalls(Request $request)
    {
        $pendingCalls = \App\Models\QrCodeCagri::where('Status', 0)->get();

        if ($request->input('mark_as_pulled') == 1 && $pendingCalls->count() > 0) {
            \App\Models\QrCodeCagri::where('Status', 0)->update(['Status' => 1]);
        }

        return response()->json([
            'success' => true,
            'cagrilar' => $pendingCalls
        ]);
    }
}
