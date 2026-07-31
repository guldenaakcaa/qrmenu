<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
    {
        if (session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = \Illuminate\Support\Facades\DB::table('users')->where('email', $request->email)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            session([
                'admin_logged_in' => true,
                'admin_id' => $user->id,
                'admin_name' => $user->name,
                'admin_email' => $user->email,
                'admin_role' => (string) $user->kullanicitipi
            ]);
            return redirect()->route('admin.dashboard')->with('success', 'Başarıyla giriş yapıldı.');
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz e-posta veya şifre hatalı.',
        ])->withInput($request->only('email'));
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email', 'admin_role']);
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $totalProducts = \App\Models\UrunKart::count();
        $totalCategories = \App\Models\UrunGrubu::count();
        $recentProducts = \App\Models\UrunKart::orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'recentProducts'));
    }

    public function qrStudio()
    {
        $settings = \App\Models\Ayar::first();
        return view('admin.qr-studio', compact('settings'));
    }

    public function masalar(\Illuminate\Http\Request $request)
    {
        $seciliTarih = $request->get('tarih', date('Y-m-d'));

        $masalar = \App\Models\Masa::all();
        $masa_siparisleri = \App\Models\MasaSiparis::all()->groupBy('masa_isim');
        
        // QR kodları masalara göre al
        $qrCodes = \App\Models\QrCodeKart::whereIn('Masa_id', $masalar->pluck('id'))->get()->keyBy('Masa_id');

        $gunluk_kasa = \App\Models\Kasa::where('tarih', $seciliTarih)->first();
        $kasa_islemleri = \App\Models\KasaIslem::where('tarih', $seciliTarih)->orderBy('islem_saati', 'desc')->get();
        
        return view('admin.masalar.index', compact('masalar', 'masa_siparisleri', 'gunluk_kasa', 'kasa_islemleri', 'seciliTarih', 'qrCodes'));
    }

    public function storeMasa(\Illuminate\Http\Request $request)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        
        $request->validate([
            'isim' => 'required|string|max:191'
        ]);

        $masa = \App\Models\Masa::create([
            'isim' => $request->isim,
            'durum' => 0,
            'guncel_tutar' => 0
        ]);

        $slugBase = \Illuminate\Support\Str::slug($request->isim);
        if (empty($slugBase)) {
            $slugBase = 'masa';
        }
        
        $qrCode = $slugBase . '-' . strtolower(\Illuminate\Support\Str::random(4));
        while(\App\Models\QrCodeKart::where('QRCode', $qrCode)->exists()){
            $qrCode = $slugBase . '-' . strtolower(\Illuminate\Support\Str::random(4));
        }

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

        return back()->with('success', 'Masa başarıyla eklendi ve karekodu oluşturuldu.');
    }

    public function updateMasa(\Illuminate\Http\Request $request, $id)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        
        $request->validate([
            'isim' => 'required|string|max:191'
        ]);

        $masa = \App\Models\Masa::findOrFail($id);
        $eskiIsim = $masa->isim;
        $masa->update(['isim' => $request->isim]);

        // İlişkili tabloları güncelle
        \App\Models\QrCodeKart::where('Masa_id', $id)->update(['Masaismi' => $request->isim]);
        \App\Models\MasaSiparis::where('masa_isim', $eskiIsim)->update(['masa_isim' => $request->isim]);

        return back()->with('success', 'Masa ismi başarıyla güncellendi.');
    }

    public function destroyMasa($id)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        
        $masa = \App\Models\Masa::findOrFail($id);
        
        // Siparişleri sil
        \App\Models\MasaSiparis::where('masa_isim', $masa->isim)->delete();
        // QrCodeKart kaydını sil
        \App\Models\QrCodeKart::where('Masa_id', $id)->delete();
        // Masayı sil
        $masa->delete();

        return back()->with('success', 'Masa ve tüm ilişkili kayıtlar başarıyla silindi.');
    }

    public function checkoutMasa(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'odeme_turu' => 'required|in:Nakit,Kredi Kartı'
        ]);

        $masa = \App\Models\Masa::findOrFail($id);
        $tutar = $masa->guncel_tutar > 0 ? $masa->guncel_tutar : \App\Models\MasaSiparis::where('masa_isim', $masa->isim)->sum(\Illuminate\Support\Facades\DB::raw('fiyat * adet'));

        if ($tutar > 0) {
            $bugun = date('Y-m-d');
            $kasa = \App\Models\Kasa::where('tarih', $bugun)->first();
            
            if (!$kasa) {
                $kasa = \App\Models\Kasa::create([
                    'tarih' => $bugun,
                    'nakit_toplam' => 0,
                    'kredi_karti_toplam' => 0,
                    'genel_toplam' => 0
                ]);
            }

            if ($request->odeme_turu == 'Nakit') {
                $kasa->increment('nakit_toplam', $tutar);
            } else {
                $kasa->increment('kredi_karti_toplam', $tutar);
            }
            $kasa->increment('genel_toplam', $tutar);

            \App\Models\KasaIslem::create([
                'tarih' => $bugun,
                'islem_saati' => date('H:i:s'),
                'turu' => $request->odeme_turu,
                'tutar' => $tutar,
                'aciklama' => $masa->isim . ' hesabı kapatıldı'
            ]);
        }

        // Masayı sıfırla
        \App\Models\MasaSiparis::where('masa_isim', $masa->isim)->delete();
        $masa->update([
            'durum' => 0,
            'guncel_tutar' => 0
        ]);

        return back()->with('success', 'Ödeme başarıyla alındı ve masa sıfırlandı.');
    }

    public function settings()
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        $settings = \App\Models\Ayar::first();
        if (!$settings) {
            $settings = new \App\Models\Ayar();
            $settings->save();
        }
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        $settings = \App\Models\Ayar::first();

        // Form 1 gönderilmişse baslik alanını doğrula
        if (array_key_exists('baslik', $request->all())) {
            $request->validate([
                'baslik' => 'nullable|string|max:255'
            ]);
        }

        $data = $request->except(['_token', 'logo', 'favicon', 'karsilama_gorsel', 'remove_logo', 'remove_favicon', 'remove_karsilama_gorsel']);

        // Handle File Uploads and Removals
        if ($request->has('remove_logo')) {
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            $data['logo'] = $path;
        }

        if ($request->has('remove_favicon')) {
            $data['favicon'] = null;
        } elseif ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            $data['favicon'] = $path;
        }

        if ($request->has('remove_karsilama_gorsel')) {
            $data['karsilama_gorsel'] = null;
        } elseif ($request->hasFile('karsilama_gorsel')) {
            $path = $request->file('karsilama_gorsel')->store('settings', 'public');
            $data['karsilama_gorsel'] = $path;
        }

        $settings->update($data);

        return back()->with('success', 'Ayarlar başarıyla güncellendi.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $userId = session('admin_id');
        if (!$userId) {
            return back()->withErrors(['current_password' => 'Oturum süresi dolmuş. Lütfen tekrar giriş yapın.']);
        }
        $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifreniz yanlış.']);
        }

        \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => bcrypt($request->new_password)]);

        return back()->with('success', 'Şifreniz başarıyla güncellendi.');
    }

    // Admin Management Methods
    public function admins()
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        $admins = \Illuminate\Support\Facades\DB::table('users')->orderBy('id', 'asc')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $maxId = \Illuminate\Support\Facades\DB::table('users')->max('id_kullanici') ?? 0;
        
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'id_kullanici' => $maxId + 1,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'yetki' => 'tahsilat|odeme|satisrapor',
            'kullanicitipi' => $request->kullanicitipi ?? 0,
            'subeyetki' => '1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Yönetici başarıyla eklendi.');
    }

    public function updateAdmin(Request $request, $id)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:6|confirmed'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'kullanicitipi' => $request->has('kullanicitipi') ? $request->kullanicitipi : 0,
            'updated_at' => now()
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        \Illuminate\Support\Facades\DB::table('users')->where('id', $id)->update($data);

        // Update session if editing own profile
        if (session('admin_id') == $id) {
            session(['admin_name' => $request->name, 'admin_email' => $request->email]);
        }

        return back()->with('success', 'Yönetici bilgileri başarıyla güncellendi.');
    }

    public function destroyAdmin($id)
    {
        if (session('admin_role') !== '0') return redirect()->route('admin.dashboard')->with('error', 'Yetkisiz erişim.');
        if (session('admin_id') == $id) {
            return back()->withErrors(['Hata' => 'Kendi hesabınızı silemezsiniz.']);
        }

        // Pre-check if it's the very last admin
        $adminCount = \Illuminate\Support\Facades\DB::table('users')->count();
        if ($adminCount <= 1) {
            return back()->withErrors(['Hata' => 'Sistemde tek yönetici kaldığı için silemezsiniz.']);
        }

        \Illuminate\Support\Facades\DB::table('users')->where('id', $id)->delete();
        return back()->with('success', 'Yönetici silindi.');
    }
}
