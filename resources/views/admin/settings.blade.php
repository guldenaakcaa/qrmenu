@extends('admin.layouts.app')

@section('title', 'Sistem Ayarları')
@section('header_title', 'Sistem Ayarları')

@section('content')

<div class="settings-container" style="display: grid; grid-template-columns: 1fr; gap: 2rem; max-width: 1000px;">

    <!-- 1. Firma ve İletişim Ayarları -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
            <i class="fa-solid fa-store" style="color: #4f46e5; margin-right: 8px;"></i> Firma ve İletişim Ayarları
        </h3>
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label>Restoran Adı</label>
                    <input type="text" name="baslik" class="form-control" value="{{ $settings->baslik }}">
                </div>
                
                <div class="form-group">
                    <label>Slogan</label>
                    <input type="text" name="slogan" class="form-control" value="{{ $settings->slogan }}" placeholder="Örn: Lezzetin Yeni Adresi">
                </div>
                
                <div class="form-group">
                    <label>Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if($settings->logo)
                        <div style="margin-top: 10px; display: flex; align-items: center; gap: 15px; padding: 10px; border: 1px dashed var(--border-color); border-radius: 8px;">
                            <img src="{{ asset('storage/' . $settings->logo) }}" style="max-height: 40px; border-radius: 4px;">
                            <button type="submit" name="remove_logo" value="1" class="btn btn-sm" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 4px 10px; font-size: 0.8rem; border-radius: 4px; margin-left: auto;" onclick="return confirm('Logoyu silmek istediğinize emin misiniz?')">
                                <i class="fa-solid fa-trash"></i> Sil
                            </button>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Tarayıcı İkonu (Favicon)</label>
                    <input type="file" name="favicon" class="form-control" accept="image/*">
                    @if($settings->favicon)
                        <div style="margin-top: 10px; display: flex; align-items: center; gap: 15px; padding: 10px; border: 1px dashed var(--border-color); border-radius: 8px;">
                            <img src="{{ asset('storage/' . $settings->favicon) }}" style="max-height: 32px; border-radius: 4px;">
                            <button type="submit" name="remove_favicon" value="1" class="btn btn-sm" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 4px 10px; font-size: 0.8rem; border-radius: 4px; margin-left: auto;" onclick="return confirm('Faviconu silmek istediğinize emin misiniz?')">
                                <i class="fa-solid fa-trash"></i> Sil
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <h4 style="margin: 1.5rem 0 1rem; font-size: 1rem; color: #475569;">İletişim & Sosyal Medya</h4>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label>Telefon Numarası</label>
                    <input type="text" name="telefon" class="form-control" value="{{ $settings->telefon }}" placeholder="05XX XXX XX XX">
                </div>

                <div class="form-group">
                    <label>WhatsApp Numarası</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ $settings->whatsapp_number }}" placeholder="905XXXXXXXXX">
                </div>
                
                <div class="form-group">
                    <label>Instagram Linki</label>
                    <input type="url" name="instagram_url" class="form-control" value="{{ $settings->instagram_url }}" placeholder="https://instagram.com/hesapadi">
                </div>

                <div class="form-group">
                    <label>Google Haritalar Linki</label>
                    <input type="url" name="google_map_url" class="form-control" value="{{ $settings->google_map_url }}">
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Açık Adres</label>
                    <textarea name="adres" class="form-control" rows="3">{{ $settings->adres }}</textarea>
                </div>
            </div>

            <h4 style="margin: 1.5rem 0 1rem; font-size: 1rem; color: #475569;">Wi-Fi Bilgileri</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label>Ağ Adı (SSID)</label>
                    <input type="text" name="wifi_ssid" class="form-control" value="{{ $settings->wifi_ssid }}">
                </div>
                <div class="form-group">
                    <label>Şifre</label>
                    <input type="text" name="wifi_password" class="form-control" value="{{ $settings->wifi_password }}">
                </div>
            </div>

            <div style="text-align: right; margin-top: 1rem;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Ayarları Kaydet</button>
            </div>
        </form>
    </div>

    <!-- 2. Görünüm ve Tema Ayarları -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
            <i class="fa-solid fa-palette" style="color: #ec4899; margin-right: 8px;"></i> Görünüm ve Tema Ayarları
        </h3>
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Karşılama Ekranı Arka Plan Görseli</label>
                <input type="file" name="karsilama_gorsel" class="form-control" accept="image/*">
                @if($settings->karsilama_gorsel)
                    <div style="margin-top: 15px; display: flex; align-items: flex-start; gap: 15px; padding: 15px; border: 1px dashed var(--border-color); border-radius: 8px; max-width: 400px;">
                        <img src="{{ asset('storage/' . $settings->karsilama_gorsel) }}" style="max-height: 100px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <button type="submit" name="remove_karsilama_gorsel" value="1" class="btn btn-sm" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 6px 12px; font-size: 0.85rem; border-radius: 4px; margin-left: auto;" onclick="return confirm('Karşılama görselini silmek istediğinize emin misiniz?')">
                            <i class="fa-solid fa-trash"></i> Görseli Sil
                        </button>
                    </div>
                @endif
                <small style="color: #64748b; margin-top: 8px; display: block;">Müşteriler QR kodu okuttuğunda çıkan ilk ekrandaki arkaplan görselini buradan değiştirebilirsiniz. (Boş bırakılırsa standart tasarım kullanılır).</small>
            </div>
            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Görünümü Kaydet</button>
            </div>
        </form>
    </div>





</div>

@endsection
