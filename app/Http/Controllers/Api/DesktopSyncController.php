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
                Masa::updateOrCreate(
                    ['isim' => $masaData['isim']],
                    [
                        'durum' => $masaData['durum'],
                        'guncel_tutar' => $masaData['guncel_tutar'] ?? 0
                    ]
                );
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
            'kredi_karti_toplam' => 'required|numeric'
        ]);

        $genel_toplam = $request->nakit_toplam + $request->kredi_karti_toplam;

        Kasa::updateOrCreate(
            ['tarih' => $request->tarih],
            [
                'nakit_toplam' => $request->nakit_toplam,
                'kredi_karti_toplam' => $request->kredi_karti_toplam,
                'genel_toplam' => $genel_toplam
            ]
        );

        return response()->json(['success' => true, 'message' => 'Kasa verisi başarıyla eşitlendi.']);
    }

    /**
     * Mevcut durumu masaüstüne veya mobile döner.
     */
    public function getStatus()
    {
        $masalar = Masa::all();
        $gunluk_kasa = Kasa::where('tarih', date('Y-m-d'))->first();

        return response()->json([
            'masalar' => $masalar,
            'kasa' => $gunluk_kasa
        ]);
    }
}
