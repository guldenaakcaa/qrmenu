@extends('admin.layouts.app')

@section('title', 'Masalar ve Kasa Durumu')
@section('header_title', 'Masalar & Günlük Kasa Özeti')

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
    }

    .masa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    .masa-card.dolu {
        border-top: 4px solid #ef4444; /* Kırmızı / Turuncu */
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
        border-top: 4px solid #22c55e; /* Yeşil */
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
</style>

<div class="kasa-widget">
    <div style="margin-bottom: 1.5rem;">
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 600;">Günlük Kasa Özeti</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.8; font-size: 0.9rem;">{{ date('d.m.Y') }} tarihli masaüstü verileri</p>
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
</div>

<div class="header-actions">
    <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0; color: #1e293b;">Tüm Masalar</h3>
    <button class="btn btn-secondary" onclick="location.reload()" style="background: white; color: #64748b; border: 1px solid #e2e8f0; border-radius: 8px;">
        <i class="fa-solid fa-rotate-right"></i> Yenile
    </button>
</div>

<div class="masalar-grid">
    @forelse($masalar as $masa)
        <div class="masa-card {{ $masa->durum == 1 ? 'dolu' : 'bos' }}">
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
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: white; border-radius: 12px; border: 1px dashed #cbd5e1; color: #64748b;">
            <i class="fa-solid fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
            <h3 style="font-weight: 500; margin: 0;">Henüz Hiç Masa Verisi Yok</h3>
            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Masaüstü uygulaması bağlandığında ve veri gönderdiğinde masalar burada görünecektir.</p>
        </div>
    @endforelse
</div>

@endsection
