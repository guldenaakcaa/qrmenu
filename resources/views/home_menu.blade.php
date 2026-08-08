<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>
        @php $titleVal = $settings ? $settings->baslik : 'Center Cafe'; @endphp
        {{ $titleVal ? $titleVal . ' | Menü' : 'Menü' }}
    </title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Eski (Beğenilen) Açık Renk Teması */
            --bg-gradient: linear-gradient(135deg, #f0f8ff 0%, #f0fff4 100%);
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
            --card-shadow: 0 10px 20px rgba(0,0,0,0.06);
            --accent: #2b6cb0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        
        body {
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            background: var(--bg-gradient);
        }

       /* Karşılama Görseli - Yarıdan İtibaren Yavaşça Silinerek Zemine Harmanlanır */
       @if($settings && $settings->karsilama_gorsel)
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 92vh;
            z-index: -2;
            pointer-events: none;
            background: url('{{ asset("storage/" . $settings->karsilama_gorsel) }}') center/cover no-repeat;
            -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 25%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.2) 75%, rgba(0,0,0,0) 98%);
            mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 25%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.2) 75%, rgba(0,0,0,0) 98%);
        }
        @endif

        /* Tül / Silikleştirici Blur Tamamen Kaldırıldı */
        body::after {
            display: none !important;
        }

        .app-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem 4rem; /* Alttaki boşluk artırıldı */
            display: flex;
            flex-direction: column;
            flex: 1;
            width: 100%;
            justify-content: center;
        }

        header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            /* Header margin-top dinamik olarak HTML içinde eklenecek */
            margin-bottom: 3rem; /* Fotoğraflara daha yakın olması için azaltıldı */
        }

        .brand-link {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            margin-bottom: 0.8rem;
        }

        .brand-logo {
            @if(empty($settings->baslik))
                max-width: 350px;
                max-height: 350px;
            @else
                max-width: 250px;
                max-height: 250px;
            @endif
            margin-bottom: 1rem;
            object-fit: contain;
            border-radius: 24px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: clamp(2rem, 5vw, 2.8rem);
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            margin-bottom: 0.5rem; 
            text-shadow: 0 2px 10px rgba(255,255,255,0.8);
        }

        p.hero-text {
            font-size: 1.1rem;
            font-weight: 800;
            color: #4b5563; /* Kırmızı olmasın diye düzeltildi */
            margin-top: -0.7rem; /* Center Cafe'ye yaklaştırmak için */
            margin-bottom: 1rem; /* Masa badge ile arası */
            letter-spacing: 0.5px;
        }

        /* Bütünleşik Arama & Menüye Git Alanı */
        html { scroll-behavior: smooth; }
        .unified-search-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 550px;
            margin: 0 auto;
            background: var(--glass-bg, rgba(255,255,255,0.9));
            border: 1px solid var(--glass-border, rgba(255,255,255,0.8));
            border-radius: 100px;
            padding: 5px 6px 5px 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .unified-search-box:focus-within {
            background: #ffffff;
            border-color: #cbd5e1;
            box-shadow: 0 8px 25px rgba(0,0,0,0.09);
        }
        .search-form-inner {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 0;
            position: relative;
        }
        .search-btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0 10px 0 0;
            outline: none;
            color: var(--text-secondary, #64748b);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        .search-form-inner input {
            width: 100%;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0.6rem 0.5rem 0.6rem 0 !important;
            font-size: 0.95rem;
            color: var(--text-primary, #1e293b);
            outline: none;
        }
        .search-form-inner input:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        .btn-goto-menu-inner {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #0f172a;
            color: #ffffff !important;
            border-radius: 100px;
            padding: 0.7rem 1.4rem;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
            transition: all 0.25s ease;
            flex-shrink: 0;
        }
        .btn-goto-menu-inner:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.25);
        }
        .btn-goto-menu-inner i {
            color: #f59e0b;
            font-size: 0.95rem;
        }
        #menu-bolumu {
            scroll-margin-top: 20px;
        }

        /* Hızlı Erişim Butonları (Pills) */
        .quick-links-strip {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            max-width: 600px;
            margin: 0.2rem auto 2rem auto;
            padding: 0 0.5rem;
        }
        .quick-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        .quick-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }
        .quick-pill i {
            font-size: 0.95rem;
        }
        /* Wi-Fi - Koyu elegant */
        .pill-wifi {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: #e2e8f0;
        }
        .pill-wifi i { color: #38bdf8; }
        /* Instagram - Pembe-mor gradyan */
        .pill-instagram {
            background: linear-gradient(135deg, #f43f5e, #a855f7);
            color: #fff;
        }
        /* Google Yorum - Sarı-turuncu canlı */
        .pill-review {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: #fff;
        }
        .pill-review i { color: #fef3c7; }
        /* WhatsApp - Yeşil */
        .pill-whatsapp {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
        }
        /* Harita / Konum - Mavi */
        .pill-map {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
        }
        /* Telefon - Slate */
        .pill-phone {
            background: linear-gradient(135deg, #475569, #1e293b);
            color: #e2e8f0;
        }
        .pill-phone i { color: #4ade80; }

        /* Kategoriler */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); 
            gap: 0.75rem;
            width: 100%;
        }
        
        @media (min-width: 600px) {
            .bento-grid { grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        }

        .bento-card {
            position: relative;
            background: #fff;
            border-radius: 16px;
            height: 120px; 
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-decoration: none;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.5);
        }

        @media (min-width: 600px) { .bento-card { height: 140px; } }

        .bento-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .card-bg-img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.5s ease;
            z-index: 0;
        }
        
        .bento-card:hover .card-bg-img { transform: scale(1.05); }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.1) 60%, transparent 100%);
            z-index: 1;
        }

        .card-content {
            position: relative;
            z-index: 2;
            padding: 0.75rem;
            width: 100%;
        }

        .card-title {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }

        .dark-thin-footer {
            background-color: #1a1a1a;
            color: #9ca3af;
            width: 100%;
            padding: 1rem 1rem; /* Daha ince yapıldı */
            margin-top: auto;
            font-size: 0.8rem;
        }

        .footer-contacts {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .footer-contacts a, .footer-contacts span {
            color: #d1d5db;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.2s;
            font-size: 0.85rem;
        }
        .footer-contacts a:hover { color: #fff; }

        .footer-divider {
            height: 1px;
            background-color: #333;
            width: 100%;
            margin-bottom: 0.75rem;
        }

        .footer-bottom {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            width: 100%;
        }

        .footer-copyright {
            justify-self: start;
        }
        .footer-powered {
            justify-self: center;
            opacity: 0.6; 
            text-transform: uppercase; 
            font-size: 0.7rem; 
            letter-spacing: 0.5px;
        }
        .footer-admin-link {
            justify-self: end;
            color: #fff; 
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700; 
            transition: color 0.2s;
        }
        .footer-admin-link:hover { color: #facc15; }

        /* Mobil Alt Sabit Menü (Süt Evi Tarzı) */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; width: 100%;
            background: #ffffff;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.08);
            z-index: 1000;
            border-radius: 24px 24px 0 0; /* Üst köşeler yuvarlak */
            padding: 0.75rem 1rem;
            justify-content: space-around;
        }
        .mobile-bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 700;
            gap: 6px;
            transition: all 0.3s;
        }
        .mobile-bottom-nav a i { font-size: 1.3rem; }
        .mobile-bottom-nav a.active { color: #8B5A2B; } /* Aktif renk */

        @media (max-width: 768px) {
            .footer-bottom {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
                padding-bottom: 60px; /* Mobil menü boşluğu */
            }
            .mobile-bottom-nav {
                display: flex;
            }
            
            /* Sıkı Mobil Optimizasyon (Tek Ekrana Sığdırma) */
            header.header-bg { margin-top: 12vh !important; }
            header.header-logo { margin-top: 1.5rem !important; }
            header.header-normal { margin-top: 1.5rem !important; }
            
            .brand-logo {
                max-height: 140px !important;
                max-width: 180px !important;
                margin-bottom: 0.5rem !important;
                border-radius: 16px !important;
            }
            
            h1 { font-size: 1.8rem !important; margin-bottom: 0.2rem !important; }
            p.hero-text { font-size: 0.9rem !important; margin-top: -0.2rem !important; margin-bottom: 0.8rem !important; }
            
            #home-table-badge { 
                margin-bottom: 1rem !important; 
                padding: 4px 16px !important; 
                font-size: 0.85rem !important; 
            }
            
            .quick-links-strip { margin: 0.5rem auto 1rem auto !important; gap: 0.4rem !important; }
            .quick-pill { padding: 6px 12px !important; font-size: 0.75rem !important; }
            
            .unified-search-box { padding: 4px 5px 4px 12px !important; max-width: 90% !important; }
            .search-form-inner input { padding: 0.5rem 0.4rem 0.5rem 0 !important; font-size: 0.9rem !important; }
            .btn-goto-menu-inner { padding: 0.6rem 1rem !important; font-size: 0.85rem !important; }
            
            #fab-waiter-home { bottom: 85px !important; width: 50px !important; height: 50px !important; left: 15px !important; }
            #fab-waiter-home i { font-size: 1.3rem !important; }
            
            .app-container { justify-content: flex-start; padding-top: 1rem; padding-bottom: 5rem; }
        }
        
        /* Desktop Header Margin Classes */
        header.header-bg { margin-top: 22vh; }
        header.header-logo { margin-top: 3rem; }
        header.header-normal { margin-top: 2rem; }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Şirket adı vb. resim varsa sayfanın yarısından (silikleşmeye başladığı yerde - 48vh) başlar -->
        @php 
            $headerClass = 'header-normal';
            if ($settings && $settings->karsilama_gorsel) { $headerClass = 'header-bg'; }
            elseif ($settings && $settings->logo && file_exists(storage_path('app/public/' . $settings->logo))) { $headerClass = 'header-logo'; }
        @endphp
        <header class="{{ $headerClass }}">
            <!-- Tıklanabilir Logo & Başlık -->
            <a href="{{ route('home') }}" class="brand-link">
                @if($settings && $settings->logo && file_exists(storage_path('app/public/' . $settings->logo)))
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="brand-logo">
                @endif
                @php $baslikVal = $settings ? $settings->baslik : 'Center Cafe'; @endphp
                @if($baslikVal)
                    <h1>{{ $baslikVal }}</h1>
                @endif
            </a>
            
            @php $sloganVal = $settings ? $settings->slogan : 'Lezzetin yeni adresi'; @endphp
            @if($sloganVal)
            <p class="hero-text">
                {{ $sloganVal }}
            </p>
            @endif
            
            <!-- Masa Badge - Logodan kurtulup arama kutusunun hemen üstünde merkezi konumlandı -->
            <div style="display: flex; justify-content: center; width: 100%;">
                <div id="home-table-badge" style="display: {{ (isset($qrCodeCart) && $qrCodeCart) || session('current_masaismi') ? 'inline-flex' : 'none' }}; background: linear-gradient(135deg, #1e293b, #0f172a); color: #f8fafc; padding: 6px 20px; border-radius: 25px; font-size: 0.95rem; font-weight: 700; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.12); border: 1px solid rgba(255,255,255,0.15); margin-bottom: 1.9rem;">
                    <i class="fa-solid fa-chair" style="color: #fcd34d; font-size: 1.1rem;"></i>
                    <span id="home-table-name">{{ $qrCodeCart ? $qrCodeCart->Masaismi : (session('current_masaismi') ?? '') }}</span>
                </div>
            </div>

            <!-- Bütünleşik Arama & Menüye Git (Beyaz Kutunun İçinde) -->
            <div class="unified-search-box">
                <form action="{{ route('menu.search') }}" method="GET" class="search-form-inner">
                    <button type="submit" class="search-btn-icon" title="Ara">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <input type="text" name="q" placeholder="Lezzet arayın..." required>
                </form>
                @php $currentMasaParam = $qrcode ? '?masa=' . $qrcode : (session('current_qrcode') ? '?masa=' . session('current_qrcode') : ''); @endphp
                <a href="{{ $mainCategories->count() > 0 ? route('menu.show', urlencode($mainCategories->first()->anaGrup)) . $currentMasaParam : '#' }}" class="btn-goto-menu-inner" title="Menüye Git">
                    <i class="fa-solid fa-utensils"></i>
                    <span>Menüye Git</span>
                </a>
            </div>
        </header>

        <!-- Hızlı Erişim Butonları (Pills) -->
        @if($settings && ($settings->wifi_ssid || $settings->instagram_url || $settings->google_review_url || $settings->whatsapp_number || $settings->google_map_url || $settings->telefon))
        <div class="quick-links-strip">
            @if($settings->wifi_ssid)
                <span class="quick-pill pill-wifi">
                    <i class="fa-solid fa-wifi"></i>
                    Wi-Fi: {{ $settings->wifi_ssid }}@if($settings->wifi_password) / {{ $settings->wifi_password }}@endif
                </span>
            @endif
            @if($settings->google_review_url)
                <a href="{{ $settings->google_review_url }}" target="_blank" class="quick-pill pill-review">
                    <i class="fa-solid fa-star"></i>
                    Bizi Değerlendirin
                </a>
            @endif
            @if($settings->instagram_url)
                <a href="{{ $settings->instagram_url }}" target="_blank" class="quick-pill pill-instagram">
                    <i class="fa-brands fa-instagram"></i>
                    Instagram
                </a>
            @endif
            @if($settings->whatsapp_number)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number) }}" target="_blank" class="quick-pill pill-whatsapp">
                    <i class="fa-brands fa-whatsapp"></i>
                    WhatsApp
                </a>
            @endif
            @if($settings->google_map_url)
                <a href="{{ $settings->google_map_url }}" target="_blank" class="quick-pill pill-map">
                    <i class="fa-solid fa-location-dot"></i>
                    Konumu Göster
                </a>
            @endif
            @if($settings->telefon)
                <a href="tel:{{ $settings->telefon }}" class="quick-pill pill-phone">
                    <i class="fa-solid fa-phone"></i>
                    {{ $settings->telefon }}
                </a>
            @endif
        </div>
        @endif
    </div>

    <!-- İnce Koyu Footer -->
    <div class="dark-thin-footer">

        <!-- En Alt Şerit -->
        <div class="footer-bottom">
            <div class="footer-copyright">© {{ date('Y') }}. Tüm hakları saklıdır.</div>
            <div class="footer-powered">Powered by Mıkale Yazılım</div>
            <a href="{{ route('admin.dashboard') }}" class="footer-admin-link" title="Yönetim Paneli">
                <i class="fa-solid fa-user-lock"></i> Admin
            </a>
        </div>
    </div>

    <div class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="active">
            <i class="fa-solid fa-house"></i>
            <span>Ana Sayfa</span>
        </a>
        @php $currentMasaParam = $qrcode ? '?masa=' . $qrcode : (session('current_qrcode') ? '?masa=' . session('current_qrcode') : ''); @endphp
        <a href="{{ $mainCategories->count() > 0 ? route('menu.show', urlencode($mainCategories->first()->anaGrup)) . $currentMasaParam : '#' }}" style="color: #8B5A2B;">
            <i class="fa-solid fa-utensils"></i>
            <span>Menü</span>
        </a>
        <a href="{{ route('admin.dashboard') }}">
            <i class="fa-solid fa-user-lock"></i>
            <span>Admin</span>
        </a>
    </div>

    <!-- Sol Altta Sabit ve Tam Duyarlı (Responsive) Yüzen Şık Garson Çağrısı Butonu (FAB) -->
    <a href="javascript:void(0)" onclick="callWaiterNew(event)" id="fab-waiter-home" title="Garson Çağır">
        <i class="fa-solid fa-bell-concierge"></i>
    </a>
    <style>
        #fab-waiter-home {
            position: fixed;
            bottom: clamp(75px, 15vh, 90px);
            left: clamp(10px, 3.5vw, 25px);
            z-index: 9990;
            background: linear-gradient(135deg, #9c7b5c, #7d5e41);
            width: clamp(42px, 11vw, 62px);
            height: clamp(42px, 11vw, 62px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 6px 20px rgba(125, 94, 65, 0.45);
            border: 2px solid rgba(255, 255, 255, 0.3);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        #fab-waiter-home i {
            font-size: clamp(1.15rem, 3.8vw, 1.65rem);
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
        }
        #fab-waiter-home:hover { transform: scale(1.08); box-shadow: 0 10px 25px rgba(125, 94, 65, 0.65); }
        @media (min-width: 769px) {
            #fab-waiter-home { bottom: 30px; left: 30px; width: 62px; height: 62px; }
            #fab-waiter-home i { font-size: 1.65rem; }
        }
    </style>

    <!-- Garson Çağır Modal / Bildirim -->
    <div id="waiter-notification" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#22c55e; color:white; padding:12px 24px; border-radius:30px; font-weight:600; font-size:0.95rem; z-index:9999; box-shadow:0 10px 25px rgba(34,197,94,0.3); align-items:center; gap:8px;">
        <i class="fa-solid fa-check-circle"></i> Garson çağrıldı!
    </div>
    
    <div id="waiter-error" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#ef4444; color:white; padding:12px 24px; border-radius:30px; font-weight:600; font-size:0.95rem; z-index:9999; box-shadow:0 10px 25px rgba(239,68,68,0.3); align-items:center; gap:8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> Lütfen masanızdaki QR kodu okutun!
    </div>

    <input type="hidden" id="session-qrcode" value="{{ $qrcode ?? (session('current_qrcode') ?? '') }}">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentQr = document.getElementById('session-qrcode') ? document.getElementById('session-qrcode').value : '';
            let serverTableName = document.getElementById('home-table-name') ? document.getElementById('home-table-name').textContent.trim() : '';
            
            if (currentQr && serverTableName) {
                localStorage.setItem('menu_qrcode', currentQr);
                localStorage.setItem('menu_table_name', serverTableName);
            } else {
                // Eğer sayfa masalı olmayan yalın linkten açıldıysa asla eski masayı zorla ekrana yazma!
                localStorage.removeItem('menu_qrcode');
                localStorage.removeItem('menu_table_name');
                if (document.getElementById('home-table-badge')) {
                    document.getElementById('home-table-badge').style.display = 'none';
                }
            }
        });

        function callWaiterNew(e) {
            e.preventDefault();
            let qr = document.getElementById('session-qrcode').value || localStorage.getItem('menu_qrcode');
            
            if(!qr) {
                let err = document.getElementById('waiter-error');
                err.style.display = 'flex';
                setTimeout(() => { err.style.display = 'none'; }, 2500);
                return;
            }

            fetch('/api/v1/call/waiter/' + qr, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            }).then(r => r.text()).then(res => {
                let notif = document.getElementById('waiter-notification');
                if(res.trim() === 'ok') {
                    notif.innerHTML = '<i class="fa-solid fa-check-circle"></i> Garson çağrıldı!';
                    notif.style.background = '#22c55e';
                } else {
                    notif.innerHTML = '<i class="fa-solid fa-clock"></i> ' + res;
                    notif.style.background = '#f59e0b';
                }
                notif.style.display = 'flex';
                setTimeout(() => { notif.style.display = 'none'; }, 3000);
            }).catch(e => console.error(e));
        }
    </script>

</body>
</html>
