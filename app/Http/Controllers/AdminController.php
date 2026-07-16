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
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Başarıyla giriş yapıldı.');
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz e-posta veya şifre hatalı.',
        ])->withInput($request->only('email'));
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
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

    public function settings()
    {
        $settings = \App\Models\Ayar::first();
        if (!$settings) {
            $settings = new \App\Models\Ayar();
            $settings->save();
        }
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $settings = \App\Models\Ayar::first();

        // Checkbox values (since unchecked checkboxes aren't sent)
        $data = $request->except(['_token', 'logo', 'favicon', 'karsilama_gorsel']);
        $data['menu_durumu'] = $request->has('menu_durumu') ? 1 : 0;
        $data['coklu_dil_aktif'] = $request->has('coklu_dil_aktif') ? 1 : 0;

        // Handle File Uploads
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            $data['logo'] = $path;
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            $data['favicon'] = $path;
        }

        if ($request->hasFile('karsilama_gorsel')) {
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

        $user = \Illuminate\Support\Facades\DB::table('users')->where('email', 'admin@centercafe.com')->first();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifreniz yanlış.']);
        }

        \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => bcrypt($request->new_password)]);

        return back()->with('success', 'Şifreniz başarıyla güncellendi.');
    }
}
