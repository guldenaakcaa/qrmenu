<?php

namespace App\Http\Traits;

use App\Models\QrCodeCagri;
use App\Models\QrCodeKart;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait QrCodeTrait
{
    public function GetAllQrCodeKarts() : Collection
    {
        $qrs = QrCodeKart::all();
        return $qrs;
    }

    public function GetQrCodeKart($qrCode)
    {
        $qr = QrCodeKart::where('QRCode', $qrCode)->first();
        return $qr;
    }

    public function AddCallToTable($qrCode)
    {
        $qrcodeKart = QrCodeKart::where('QRCode', $qrCode)->first();
        $masa = null;

        if (!$qrcodeKart) {
            $cleanName = str_replace('-', ' ', $qrCode);
            $masa = \App\Models\Masa::where('slug', $qrCode)
                ->orWhere('isim', $qrCode)
                ->orWhere('isim', $cleanName)
                ->orWhere('isim', 'LIKE', '%' . $qrCode . '%')
                ->orWhere('id', $qrCode)
                ->first();
                
            if (!$masa && is_numeric($qrCode)) {
                $masa = \App\Models\Masa::where('isim', 'Masa ' . $qrCode)->orWhere('isim', 'MASA ' . $qrCode)->first();
            }

            if ($masa) {
                $qrcodeKart = QrCodeKart::where('Masa_id', $masa->id)->first();
            }
        }

        if ($qrcodeKart || $masa){
            date_default_timezone_set('Europe/Istanbul');

            $actualQrCode = $qrcodeKart ? $qrcodeKart->QRCode : $qrCode;
            $masaId = $qrcodeKart ? $qrcodeKart->Masa_id : $masa->id;
            $masaIsmi = $qrcodeKart ? $qrcodeKart->Masaismi : $masa->isim;

            $timeSub1Min = (new DateTime())->modify('-1 minutes')->format("Y-m-d H:i:s");

            $kontrolQrCagri = QrCodeCagri::where('QRCode', $actualQrCode)->where('Cagri_zamani', '>', $timeSub1Min)->count();

            if ($kontrolQrCagri > 0)
                return "1 Dakikada 1 kere garson çağırabilirsiniz!";

            $qrcagri = new QrCodeCagri;

            $qrcagri->Masa_id = $masaId;
            $qrcagri->QRCode = $actualQrCode;
            $qrcagri->Masaismi = $masaIsmi;
            $qrcagri->Cagri_zamani = now();
            $qrcagri->Personel_id = 0;
            $qrcagri->Status = 0;

            $qrcagri->save();

            return "ok";
        }
        else{
            return "qrcode couldnt found.";
        }
    }
}
