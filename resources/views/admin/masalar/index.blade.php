@extends('admin.layouts.app')

@section('title', 'Masalar ve Kasa Durumu')
@section('header_title', 'Masalar & Kasa Özeti')

@section('content')
<style>
    .kasa-widget {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .kasa-widget::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .kasa-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .kasa-item {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .kasa-item h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .kasa-item .amount {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .masalar-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.5rem;
    }

    .masa-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .masa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    .masa-card.dolu {
        border-top: 4px solid #ef4444;
    }

    .masa-card.dolu::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, rgba(239,68,68,0.05) 0%, rgba(255,255,255,0) 100%);
        pointer-events: none;
    }

    .masa-card.bos {
        border-top: 4px solid #22c55e;
    }

    .masa-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .masa-card.dolu .masa-icon { color: #ef4444; }
    .masa-card.bos .masa-icon { color: #22c55e; }

    .masa-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .masa-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ef4444;
    }

    .masa-status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .badge-dolu { background: #fef2f2; color: #ef4444; }
    .badge-bos { background: #f0fdf4; color: #22c55e; }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    /* Kasa Detayları CSS */
    .kasa-islemler-container {
        margin-top: 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1rem;
        position: relative;
        z-index: 2;
        max-height: 250px;
        overflow-y: auto;
    }
    .kasa-islemler-table {
        width: 100%;
        color: white;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    .kasa-islemler-table th, .kasa-islemler-table td {
        padding: 0.75rem;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        text-align: left;
    }
    .kasa-islemler-table th { font-weight: 600; opacity: 0.9; }
    
    .date-filter-form {
        display: flex;
        gap: 10px;
        align-items: center;
        background: rgba(0,0,0,0.2);
        padding: 10px;
        border-radius: 8px;
        position: relative;
        z-index: 2;
    }
    .date-filter-form input[type="date"] {
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        outline: none;
    }

    /* Scrollbar for kasa */
    .kasa-islemler-container::-webkit-scrollbar {
        width: 6px;
    }
    .kasa-islemler-container::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.1); 
        border-radius: 10px;
    }
    .kasa-islemler-container::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3); 
        border-radius: 10px;
    }

    /* Modal CSS */
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    .custom-modal.active { display: flex; }
    .modal-content {
        background-color: #fff;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        position: relative;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        max-height: 80vh;
        overflow-y: auto;
    }
    .close-modal {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        transition: color 0.2s;
    }
    .close-modal:hover { color: #ef4444; }
    
    .siparis-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    .siparis-table th, .siparis-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .siparis-table th { background: #f8fafc; font-weight: 600; color: #475569; }
</style>

<div class="kasa-widget">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; position: relative; z-index: 2;">
        <div>
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 600;">Kasa Özeti</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">Seçili Tarih: {{ date('d.m.Y', strtotime($seciliTarih)) }}</p>
        </div>
        
        <form action="{{ route('admin.masalar') }}" method="GET" class="date-filter-form">
            <label style="font-size: 0.9rem; font-weight: 500;">Tarih Seç:</label>
            <input type="date" name="tarih" value="{{ $seciliTarih }}" onchange="this.form.submit()">
        </form>
    </div>

    <div class="kasa-grid">
        <div class="kasa-item">
            <h4><i class="fa-solid fa-money-bill-wave"></i> Nakit</h4>
            <p class="amount">₺{{ number_format($gunluk_kasa->nakit_toplam ?? 0, 2, ',', '.') }}</p>
        </div>
        <div class="kasa-item">
            <h4><i class="fa-solid fa-credit-card"></i> Kredi Kartı</h4>
            <p class="amount">₺{{ number_format($gunluk_kasa->kredi_karti_toplam ?? 0, 2, ',', '.') }}</p>
        </div>
        <div class="kasa-item" style="background: rgba(255,255,255,0.3); border-color: rgba(255,255,255,0.5);">
            <h4><i class="fa-solid fa-sack-dollar"></i> Toplam Ciro</h4>
            <p class="amount">₺{{ number_format($gunluk_kasa->genel_toplam ?? 0, 2, ',', '.') }}</p>
        </div>
    </div>

    <div style="margin-top: 1.5rem; text-align: left; position: relative; z-index: 2;">
        @if(isset($kasa_islemleri) && $kasa_islemleri->count() > 0)
            <button onclick="toggleKasaDetay()" style="background: none; border: none; color: white; font-size: 1.1rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 0; opacity: 0.9;">
                <i class="fa-solid fa-list"></i> Kasa İşlem Detayları ({{ $kasa_islemleri->count() }} İşlem) <i class="fa-solid fa-chevron-down" id="kasaDetayIcon" style="font-size: 0.9rem; transition: transform 0.3s;"></i>
            </button>
            
            <div id="kasaDetayContainer" style="display: none; background: white; color: #1e293b; border-radius: 12px; margin-top: 1rem; padding: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); max-height: 300px; overflow-y: auto;">
                <table class="siparis-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th>Saat</th>
                            <th>İşlem Türü</th>
                            <th>Açıklama / Masa</th>
                            <th>Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kasa_islemleri as $islem)
                        <tr>
                            <td>{{ date('H:i', strtotime($islem->islem_saati)) }}</td>
                            <td>
                                @if(strtolower($islem->turu) == 'nakit')
                                    <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">{{ $islem->turu }}</span>
                                @else
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">{{ $islem->turu }}</span>
                                @endif
                            </td>
                            <td>{{ $islem->aciklama ?? '-' }}</td>
                            <td style="font-weight: bold;">₺{{ number_format($islem->tutar, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="color: white; opacity: 0.8; display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
                <i class="fa-solid fa-list"></i> Bu tarihe ait detaylı kasa işlemi bulunamadı.
            </div>
        @endif
    </div>
</div>

<div class="header-actions">
    <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0; color: #1e293b;">Tüm Masalar</h3>
    <button class="btn btn-secondary" onclick="location.reload()" style="background: white; color: #64748b; border: 1px solid #e2e8f0; border-radius: 8px;">
        <i class="fa-solid fa-rotate-right"></i> Yenile
    </button>
</div>

<div class="masalar-grid">
    @forelse($masalar as $masa)
        <div class="masa-card {{ $masa->durum == 1 ? 'dolu' : 'bos' }}" onclick="openMasaModal('{{ $masa->isim }}')">
            <div class="masa-icon">
                <i class="fa-solid fa-chair"></i>
            </div>
            
            @if($masa->durum == 1)
                <div class="masa-status-badge badge-dolu">Dolu</div>
            @else
                <div class="masa-status-badge badge-bos">Boş</div>
            @endif
            
            <div class="masa-name">{{ $masa->isim }}</div>
            
            @if($masa->durum == 1 && $masa->guncel_tutar > 0)
                <div class="masa-amount">₺{{ number_format($masa->guncel_tutar, 2, ',', '.') }}</div>
            @else
                <div style="height: 1.5rem;"></div> <!-- Boşluk koruması -->
            @endif
            
            <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 5px;">Detayları görmek için tıklayın</div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: white; border-radius: 12px; border: 1px dashed #cbd5e1; color: #64748b;">
            <i class="fa-solid fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
            <h3 style="font-weight: 500; margin: 0;">Henüz Hiç Masa Verisi Yok</h3>
            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Masaüstü uygulaması bağlandığında ve veri gönderdiğinde masalar burada görünecektir.</p>
        </div>
    @endforelse
</div>

<!-- Masa Siparişleri Modalı -->
<div id="masaModal" class="custom-modal">
    <div class="modal-content">
        <i class="fa-solid fa-xmark close-modal" onclick="closeMasaModal()"></i>
        <h3 id="modalMasaIsim" style="margin-top: 0; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Masa Detayı</h3>
        
        <div id="modalSiparisContent">
            <!-- Siparişler buraya JS ile yüklenecek -->
        </div>
    </div>
</div>

<script>
    // Laravel'den gelen sipariş verisini JS objesine dönüştürüyoruz
    const masaSiparisleri = @json($masa_siparisleri);

    function openMasaModal(masaIsim) {
        document.getElementById('modalMasaIsim').innerText = masaIsim + ' Sipariş Detayları';
        
        let contentHtml = '';
        
        if (masaSiparisleri[masaIsim] && masaSiparisleri[masaIsim].length > 0) {
            let siparisler = masaSiparisleri[masaIsim];
            let toplamTutar = 0;
            
            contentHtml += `
                <table class="siparis-table">
                    <thead>
                        <tr>
                            <th>Saat</th>
                            <th>Ürün</th>
                            <th>Adet</th>
                            <th>B. Fiyat</th>
                            <th>Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            siparisler.forEach(s => {
                let sSaat = s.siparis_saati ? new Date(s.siparis_saati).toLocaleTimeString('tr-TR', {hour: '2-digit', minute:'2-digit'}) : '-';
                let sFiyat = parseFloat(s.fiyat);
                let sAdet = parseInt(s.adet);
                let araToplam = sFiyat * sAdet;
                toplamTutar += araToplam;
                
                contentHtml += `
                    <tr>
                        <td>${sSaat}</td>
                        <td style="font-weight: 500;">${s.urun_adi}</td>
                        <td>${sAdet}</td>
                        <td>₺${sFiyat.toFixed(2)}</td>
                        <td style="font-weight: 600; color: #ef4444;">₺${araToplam.toFixed(2)}</td>
                    </tr>
                `;
            });
            
            contentHtml += `
                    </tbody>
                </table>
                <div style="margin-top: 15px; text-align: right; font-size: 1.25rem; font-weight: 700; color: #1e293b;">
                    Genel Toplam: <span style="color: #ef4444;">₺${toplamTutar.toFixed(2)}</span>
                </div>
            `;
        } else {
            contentHtml = `
                <div style="text-align: center; padding: 2rem; color: #64748b;">
                    <i class="fa-solid fa-receipt" style="font-size: 2.5rem; margin-bottom: 10px; opacity: 0.5;"></i>
                    <p>Bu masaya ait açık sipariş detayı bulunmuyor veya masaüstü uygulamasından henüz detaylar gönderilmedi.</p>
                </div>
            `;
        }
        
        document.getElementById('modalSiparisContent').innerHTML = contentHtml;
        document.getElementById('masaModal').classList.add('active');
    }

    function closeMasaModal() {
        document.getElementById('masaModal').classList.remove('active');
    }

    function toggleKasaDetay() {
        let container = document.getElementById('kasaDetayContainer');
        let icon = document.getElementById('kasaDetayIcon');
        if (container.style.display === 'none') {
            container.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            container.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Modal dışına tıklayınca kapatma
    window.onclick = function(event) {
        let modal = document.getElementById('masaModal');
        if (event.target == modal) {
            closeMasaModal();
        }
    }
</script>
@endsection
