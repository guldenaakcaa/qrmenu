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
        }

        /* Arka Plan Görseli */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -2;
            @if($settings && $settings->karsilama_gorsel)
                background: url('{{ asset("storage/" . $settings->karsilama_gorsel) }}') center/cover no-repeat;
            @else
                background: var(--bg-gradient);
            @endif
        }

        /* Silikleştirme / Beyazlatma Efekti (Eski Haline Döndü) */
        body::after {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            @if($settings && $settings->karsilama_gorsel)
                background: rgba(244, 249, 249, 0.88); 
                backdrop-filter: blur(8px); 
                -webkit-backdrop-filter: blur(8px);
            @else
                background: rgba(244, 249, 249, 1);
            @endif
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
            margin-bottom: 1.5rem;
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
            margin-bottom: 1rem; 
            text-shadow: 0 2px 10px rgba(255,255,255,0.8);
        }

        p.hero-text {
            font-size: 1.1rem;
            font-weight: 800;
            color: #4b5563; /* Kırmızı olmasın diye düzeltildi */
            margin-top: -0.7rem; /* Center Cafe'ye yaklaştırmak için */
            margin-bottom: 3.5rem; /* Arama çubuğu ile arası açıldı */
            letter-spacing: 0.5px;
        }

        /* Arama Çubuğu */
        .search-bar {
            width: 100%;
            max-width: 400px;
            position: relative;
            margin: 0 auto;
        }
        .search-bar input {
            width: 100%;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 100px;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.9rem;
            color: var(--text-primary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .search-bar input:focus {
            outline: none;
            background: rgba(255,255,255,0.95);
            border-color: #a0aec0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            opacity: 0.7;
            font-size: 0.9rem;
        }

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
        }
    </style>
</head>
<body>

    <div class="app-container">
        <header style="{{ ($settings && $settings->logo && file_exists(storage_path('app/public/' . $settings->logo))) ? 'margin-top: 3rem;' : 'margin-top: 0.8rem;' }}">
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
            
            <form action="{{ route('menu.search') }}" method="GET" class="search-bar" style="display: flex; align-items: center;">
                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0; outline: none; position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); z-index: 10;">
                    <i class="fa-solid fa-magnifying-glass" style="position: static; transform: none; font-size: 1.1rem;"></i>
                </button>
                <input type="text" name="q" placeholder="Lezzet arayın..." required style="padding-left: 3rem;">
            </form>
        </header>

        <main class="bento-grid">
            @foreach($mainCategories as $index => $kategori)
                <a href="{{ route('menu.show', urlencode($kategori->anaGrup)) }}" class="bento-card">
                    @if($kategori->anaGrupResimPath && file_exists(storage_path('app/public/' . $kategori->anaGrupResimPath)))
                        <div class="card-bg-img" style="background-image: url('{{ asset('storage/' . $kategori->anaGrupResimPath) }}');"></div>
                    @else
                        <div class="card-bg-img" style="background: linear-gradient(45deg, #a1c4fd, #c2e9fb);"></div>
                    @endif
                    <div class="card-overlay"></div>
                    
                    <div class="card-content">
                        <h3 class="card-title">{{ mb_strtoupper($kategori->anaGrup, 'UTF-8') }}</h3>
                    </div>
                </a>
            @endforeach
        </main>
    </div>

    <!-- İnce Koyu Footer -->
    <div class="dark-thin-footer">
        
        <!-- İnce Yazılı İletişim Bilgileri -->
        @if($settings && ($settings->telefon || $settings->whatsapp_number || $settings->instagram_url || $settings->adres || $settings->wifi_ssid))
            <div class="footer-contacts">
                @if($settings->telefon)
                    <a href="tel:{{ $settings->telefon }}"><i class="fa-solid fa-phone"></i> {{ $settings->telefon }}</a>
                @endif
                @if($settings->whatsapp_number)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number) }}" target="_blank"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                @endif
                @if($settings->instagram_url)
                    <a href="{{ $settings->instagram_url }}" target="_blank"><i class="fa-brands fa-instagram"></i> Instagram</a>
                @endif
                @if($settings->adres)
                    <span><i class="fa-solid fa-map-pin"></i> {{ $settings->adres }}</span>
                @endif
                @if($settings->google_map_url)
                    <a href="{{ $settings->google_map_url }}" target="_blank"><i class="fa-solid fa-location-dot"></i> Harita</a>
                @endif
                @if($settings->google_review_url)
                    <a href="{{ $settings->google_review_url }}" target="_blank"><i class="fa-solid fa-star" style="color: #fbbf24;"></i> Yorum Yap</a>
                @endif
                @if($settings->wifi_ssid)
                    <span><i class="fa-solid fa-wifi"></i> Wi-Fi: {{ $settings->wifi_ssid }} @if($settings->wifi_password) / Şifre: {{ $settings->wifi_password }} @endif</span>
                @endif
            </div>
            <div class="footer-divider"></div>
        @endif

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
        <a href="#" onclick="callWaiterNew(event)" style="color: #ea580c;">
            <i class="fa-solid fa-bell"></i>
            <span>Garson Çağır</span>
        </a>
        <a href="{{ route('admin.dashboard') }}">
            <i class="fa-solid fa-user-lock"></i>
            <span>Admin</span>
        </a>
    </div>

    <!-- Garson Çağır Modal / Bildirim -->
    <div id="waiter-notification" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#22c55e; color:white; padding:12px 24px; border-radius:30px; font-weight:600; font-size:0.95rem; z-index:9999; box-shadow:0 10px 25px rgba(34,197,94,0.3); align-items:center; gap:8px;">
        <i class="fa-solid fa-check-circle"></i> Garson çağrıldı!
    </div>
    
    <div id="waiter-error" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#ef4444; color:white; padding:12px 24px; border-radius:30px; font-weight:600; font-size:0.95rem; z-index:9999; box-shadow:0 10px 25px rgba(239,68,68,0.3); align-items:center; gap:8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> Lütfen masanızdaki QR kodu okutun!
    </div>

    <input type="hidden" id="session-qrcode" value="{{ $qrcode ?? (session('current_qrcode') ?? '') }}">

    <script>
        function callWaiterNew(e) {
            e.preventDefault();
            let qr = document.getElementById('session-qrcode').value;
            
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
