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

    public function masalar()
    {
        $masalar = \App\Models\Masa::all();
        $gunluk_kasa = \App\Models\Kasa::where('tarih', date('Y-m-d'))->first();
        
        return view('admin.masalar.index', compact('masalar', 'gunluk_kasa'));
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

        // Checkbox values (since unchecked checkboxes aren't sent)
        $data = $request->except(['_token', 'logo', 'favicon', 'karsilama_gorsel']);
        $data['menu_durumu'] = $request->has('menu_durumu') ? 1 : 0;
        $data['coklu_dil_aktif'] = $request->has('coklu_dil_aktif') ? 1 : 0;

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
