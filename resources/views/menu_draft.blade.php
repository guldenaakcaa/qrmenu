<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $settings && $settings->baslik ? $settings->baslik : 'Menü' }}</title>
    @if($settings && $settings->favicon)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF6B6B; /* İştah açıcı sıcak ton */
            --primary-dark: #ee5253;
            --bg: #F9FAFB;
            --surface: #FFFFFF;
            --text: #2d3436;
            --text-light: #4b5563; /* Daha koyu, daha okunaklı açıklama metni rengi */
            --border: #f1f2f6;
            --shadow: 0 4px 20px rgba(0,0,0,0.05);
            --radius: 16px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        body { 
            font-family: 'Outfit', sans-serif; 
            color: var(--text); 
            padding-bottom: 70px; /* Space for bottom nav */
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
                background: linear-gradient(135deg, #f0f8ff 0%, #f0fff4 100%);
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
                background: transparent;
            @endif
        }

        /* Header & Sticky Categories */
        header { 
            position: relative;
            background: rgba(244, 249, 249, 0.75); 
            z-index: 99; 
            padding-bottom: 0.5rem; 
        }
        .sticky-top-container {
            position: sticky; top: 0;
            background: rgba(244, 249, 249, 0.95); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
        }
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; }
        .logo { font-size: 1.25rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
        .logo i { color: var(--primary); }
        
        /* Cart Icon */
        .cart-icon-container { position: relative; display: none !important; }
        .cart-icon { font-size: 1.35rem; color: var(--text); transition: 0.2s; }
        .cart-badge { position: absolute; top: -6px; right: -8px; background: var(--primary); color: white; font-size: 0.65rem; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transform: scale(0); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .cart-badge.active { transform: scale(1); }

        /* Top Nav Bar (Masaüstü Sol Üst) */
        .top-nav-bar {
            display: flex;
            justify-content: flex-end;
            gap: 1.5rem;
            padding: 1rem 1.25rem 0;
            background: transparent;
        }
        .top-nav-bar a {
            color: #4b5563;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .top-nav-bar a:hover { color: var(--primary); }

        @media (max-width: 768px) {
            .top-nav-bar { display: none; } /* Mobilde gizle */
        }

        .main-categories-slider {
            display: flex; gap: 10px; overflow-x: auto; padding: 1rem 1.25rem;
            margin-bottom: 1rem; scrollbar-width: none; scroll-behavior: smooth;
            width: 100%; justify-content: center; justify-content: safe center;
        }
        .main-categories-slider::-webkit-scrollbar { display: none; }
        .mc-card {
            position: relative; width: 120px; height: 60px; flex-shrink: 0; border-radius: 12px; overflow: hidden; text-decoration: none; border: 2px solid transparent; transition: 0.2s;
        }
        .mc-card.active { border-color: #8B5A2B; }
        .mc-bg {
            position: absolute; inset: 0; background-size: cover; background-position: center; z-index: 0;
        }
        .mc-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,0.5); z-index: 1; transition: 0.3s;
        }
        .mc-card.active .mc-overlay { background: rgba(0,0,0,0.2); }
        .mc-title {
            position: relative; z-index: 2; color: #fff; display: flex; align-items: center; justify-content: center; height: 100%; font-size: 0.85rem; font-weight: 800; text-align: center; text-shadow: 0 2px 4px rgba(0,0,0,0.8); letter-spacing: 0.5px;
        }

        /* Subcategories (Alt Gruplar) Pills */
        .subcat-slider {
            display: flex; gap: 10px; overflow-x: auto; padding: 0.5rem 1.25rem 1.5rem;
            scrollbar-width: none; scroll-behavior: smooth;
            width: 100%; justify-content: center; justify-content: safe center;
        }
        .subcat-slider::-webkit-scrollbar { display: none; }

        /* Slider Okları */
        .slider-wrapper { position: relative; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid rgba(0,0,0,0.05); width: 100%; }
        .slider-btn { position: absolute; z-index: 10; background: rgba(255,255,255,0.9); border: 1px solid #e2e8f0; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.1); color: #64748b; font-size: 0.9rem; transition: 0.2s; }
        .slider-btn:hover { background: #fff; color: var(--primary); transform: scale(1.05); }
        .slider-btn:active { transform: scale(0.95); }
        .slider-btn.left { left: 5px; }
        .slider-btn.right { right: 5px; }
        .slider-wrapper-subcat { border-bottom: none; }
        .subcat-pill {
            padding: 0.75rem 1.5rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.85rem; font-weight: 700; color: #64748b; white-space: nowrap; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); text-transform: uppercase;
        }
        .subcat-pill.active {
            border-color: #8B5A2B; color: #1e293b; box-shadow: 0 4px 10px rgba(139,90,43,0.1);
        }

        /* Search Bar & Filters */
        .search-container { padding: 0.5rem 1.25rem 0.5rem; background: transparent; }
        .search-box { display: flex; align-items: center; background: var(--bg); border: 1px solid var(--border); border-radius: 20px; padding: 0.65rem 1rem; }
        .search-box i { color: #94a3b8; font-size: 1.1rem; margin-right: 0.75rem; }
        .search-box input { border: none; background: transparent; flex: 1; font-family: inherit; font-size: 0.95rem; color: var(--text); outline: none; }
        .search-box input::placeholder { color: #94a3b8; }

        .filter-pills { display: flex; overflow-x: auto; padding: 0.5rem 1.25rem 0.5rem; gap: 0.75rem; scrollbar-width: none; background: transparent; }
        .filter-pills::-webkit-scrollbar { display: none; }
        .filter-pill { display: flex; align-items: center; gap: 6px; padding: 0.4rem 0.85rem; background: var(--bg); border: 1px solid var(--border); border-radius: 20px; font-size: 0.8rem; font-weight: 500; white-space: nowrap; cursor: pointer; transition: 0.2s; color: #64748b; }
        .filter-pill.active { background: #e2e8f0; color: var(--text); border-color: #cbd5e1; }
        
        .text-yellow-400 { color: #facc15; }
        .text-green-500 { color: #22c55e; }
        .text-amber-500 { color: #f59e0b; }
        .text-red-500 { color: #ef4444; }

        /* Main Content */
        main { padding: 0.5rem 1.25rem 2rem; }
        
        /* Category Section & Title spacing */
        .category-section { scroll-margin-top: 140px; }
        .section-title { font-size: 1.35rem; font-weight: 700; margin-top: 1rem; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.5px; color: #1e293b; }

        /* Product Cards */
        .product-list { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem; }
        .product-modal-content {
            background: var(--surface); width: 100%; border-radius: 24px 24px 0 0; padding: 2rem 1.5rem; position: relative; max-height: 90vh; overflow-y: auto;
        }

        /* Mobil Alt Sabit Menü (Süt Evi Tarzı) */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; width: 100%;
            background: #ffffff;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.08);
            z-index: 1000;
            border-radius: 24px 24px 0 0;
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
        .mobile-bottom-nav a.active { color: #8B5A2B; }

        @media (max-width: 768px) {
            .mobile-bottom-nav { display: flex; }
            body { padding-bottom: 90px; } /* Menü alanı için boşluk */
        }
        
        .product-card { background: var(--surface); border-radius: var(--radius); padding: 1rem; display: flex; gap: 1rem; box-shadow: var(--shadow); position: relative; }
        
        .product-img-wrapper { width: 130px; height: 130px; border-radius: 20px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .product-img { width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05)); }
        
        .product-info { flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .product-name { font-size: 1rem; font-weight: 700; color: var(--text); line-height: 1.3; margin-bottom: 0.3rem; padding-right: 65px; } /* Space for absolute price badge */
        
        /* Badges (Etiketler) */
        .badge-container { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 0.4rem; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 9999px; font-size: 0.7rem; font-weight: 500; background-color: #f1f5f9; color: #475569; }
        
        /* Açıklama Metni */
        .product-desc { font-size: 0.85rem; color: var(--text-light); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        
        .product-footer { display: flex; justify-content: flex-end; align-items: center; margin-top: 0.5rem; }
        .product-price { display: none; } /* Hidden here, moved to absolute badge */
        .price-badge { position: absolute; top: 1rem; right: 1rem; background: var(--primary); color: white; font-weight: 700; font-size: 0.95rem; padding: 4px 10px; border-radius: 12px; box-shadow: 0 4px 10px rgba(255,107,107,0.3); }

        /* Hamburger Sidebar */
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1002; opacity: 0; pointer-events: none; transition: 0.3s; backdrop-filter: blur(2px); }
        .sidebar-overlay.active { opacity: 1; pointer-events: auto; }
        .sidebar { position: fixed; top: 0; left: -300px; width: 280px; height: 100%; background: var(--surface); z-index: 1003; transition: left 0.3s cubic-bezier(0.2, 0.9, 0.3, 1); display: flex; flex-direction: column; box-shadow: 4px 0 15px rgba(0,0,0,0.05); }
        .sidebar.active { left: 0; }
        .sidebar-header { padding: 1.5rem 1.25rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .sidebar-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text); }
        .sidebar-content { padding: 1rem 0; overflow-y: auto; }
        .sidebar-item { padding: 1rem 1.25rem; font-size: 1.05rem; font-weight: 500; color: var(--text); border-bottom: 1px solid var(--border); cursor: pointer; transition: 0.2s; }
        .sidebar-item:active { background: var(--bg); }

        /* Flying dot animation */
        .flying-dot { position: fixed; width: 14px; height: 14px; background: var(--primary); border-radius: 50%; z-index: 1000; pointer-events: none; transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1); opacity: 0; }
        
        /* Floating Action Button */
        .fab { position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%); background: var(--text); color: white; padding: 0.85rem 1.5rem; border-radius: 30px; font-weight: 500; font-size: 0.95rem; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: none !important; align-items: center; gap: 8px; z-index: 99; transition: 0.3s; width: max-content; }
        .fab span { font-weight: 700; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; }

        /* Bottom Sheet (Modal) */
        .bottom-sheet-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; backdrop-filter: blur(2px); }
        .bottom-sheet-overlay.active { opacity: 1; pointer-events: auto; }
        
        .bottom-sheet { position: fixed; bottom: 1rem; left: 50%; width: calc(100% - 2rem); max-width: 500px; background: var(--surface); border-radius: 28px; z-index: 1001; transform: translate(-50%, 150%); transition: transform 0.4s cubic-bezier(0.2, 0.9, 0.3, 1); max-height: calc(100vh - 2rem); height: auto; display: flex; flex-direction: column; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        .bottom-sheet.active { transform: translate(-50%, 0); }
        
        .bs-header { padding: 1.25rem 1.25rem 0.5rem; text-align: center; position: relative; flex-shrink: 0; }
        .drag-handle { width: 44px; height: 5px; background: #e2e8f0; border-radius: 3px; margin: 0 auto 1rem; }
        .bs-title { font-size: 1.35rem; font-weight: 700; color: var(--text); text-align: left; margin-bottom: 0.3rem;}
        .bs-desc { font-size: 0.9rem; color: var(--text-light); text-align: left; line-height: 1.4;}
        
        .bs-content { padding: 1rem 1.25rem; overflow-y: auto; flex: 1; padding-bottom: 20px; } /* Footer boşluğu iptal edildi */
        
        .options-group { margin-bottom: 1.75rem; }
        .options-group-title { font-size: 1.05rem; font-weight: 700; color: var(--text); margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; }
        .options-group-title span.required { font-size: 0.75rem; background: #f1f5f9; color: #64748b; padding: 3px 8px; border-radius: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;}
        .options-group-title span.optional { font-size: 0.75rem; color: #94a3b8; font-weight: 500;}
        
        .option-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border); cursor: pointer; transition: 0.2s; }
        .option-item:active { background: #f8fafc; }
        label:last-child .option-item { border-bottom: none; }
        .option-label { font-size: 0.95rem; font-weight: 500; color: var(--text); display: flex; align-items: center; gap: 12px; }
        .option-price { font-size: 0.95rem; color: var(--primary); font-weight: 600; }

        /* Featured Slider Redesign */
        .featured-section { padding: 0 1.25rem; margin-top: 0.75rem; margin-bottom: 1rem; }
        .featured-title { font-size: 1.1rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-dark); display: flex; align-items: center; gap: 6px; letter-spacing: -0.3px; }
        .featured-title i { color: #ff4757; }
        .featured-slider { display: flex; gap: 0.75rem; overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: none; padding-bottom: 8px; margin: 0 -1.25rem; padding-left: 1.25rem; padding-right: 1.25rem; }
        .featured-slider::-webkit-scrollbar { display: none; }
        .featured-card { flex: 0 0 140px; scroll-snap-align: start; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05), 0 1px 4px rgba(0,0,0,0.03); display: flex; flex-direction: column; cursor: pointer; border: 1px solid rgba(0,0,0,0.04); transition: transform 0.2s ease; }
        .featured-card:active { transform: scale(0.98); }
        .featured-img-wrapper { width: 100%; height: 100px; background: transparent; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-top: 6px; }
        .featured-img { width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.08)); }
        .featured-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #cbd5e1; }
        .featured-info { padding: 0.5rem 0.75rem; display: flex; flex-direction: column; gap: 3px; }
        .featured-name { font-size: 0.9rem; font-weight: 700; color: var(--text-dark); line-height: 1.25; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; white-space: normal; }
        .featured-price { font-size: 0.95rem; font-weight: 800; color: var(--primary); margin: 0; }
        
        /* Custom Checkbox & Radio */
        .custom-radio, .custom-checkbox { width: 22px; height: 22px; border: 2px solid #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.2s; flex-shrink: 0; }
        .custom-checkbox { border-radius: 6px; }
        
        input[type="radio"]:checked + .option-item .custom-radio { border-color: var(--primary); }
        input[type="radio"]:checked + .option-item .custom-radio::after { content: ''; width: 12px; height: 12px; background: var(--primary); border-radius: 50%; }
        
        input[type="checkbox"]:checked + .option-item .custom-checkbox { border-color: var(--primary); background: var(--primary); }
        input[type="checkbox"]:checked + .option-item .custom-checkbox::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; color: white; font-size: 0.7rem; }

        .bs-footer { position: absolute; bottom: 0; left: 0; width: 100%; padding: 1rem 1.25rem; background: var(--surface); border-top: 1px solid var(--border); box-shadow: 0 -4px 20px rgba(0,0,0,0.04); border-radius: 0 0 28px 28px; }
        .btn-submit { width: 100%; background: var(--primary); color: white; border: none; padding: 1.1rem; border-radius: 16px; font-size: 1.1rem; font-weight: 600; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3); }
        .btn-submit:active { transform: scale(0.98); box-shadow: 0 2px 8px rgba(255, 107, 107, 0.2); }

        .bs-product-img { width: 100%; max-width: 250px; aspect-ratio: 1/1; object-fit: contain; margin-bottom: 16px; display: none; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15)); }

        @media (max-width: 480px) {
            .product-card { padding: 0.75rem; gap: 0.75rem; }
            .product-img-wrapper { width: 80px; height: 80px; border-radius: 12px; }
            .product-name { font-size: 0.95rem; }
            .badge { padding: 2px 6px; font-size: 0.65rem; }
            .product-desc { font-size: 0.8rem; }
            .price-badge { font-size: 0.85rem; padding: 3px 8px; top: 0.75rem; right: 0.75rem; }
            
            .featured-card { flex: 0 0 115px; }
            .featured-img-wrapper { height: 75px; }
            
            .bs-product-img { max-width: 180px; margin-bottom: 8px; }
            .bs-title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

    <!-- Intro Screen Removed -->

    <div class="sticky-top-container">
        <div class="top-bar" style="position: relative;">
            <!-- Geri Butonu -->
            <a href="{{ route('home') }}" style="color: var(--text); font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; gap: 6px; text-decoration: none;">
                <i class="fa-solid fa-chevron-left"></i> Geri
            </a>

            <!-- Masa Badge -->
            <div id="header-table-badge" style="display: {{ (isset($qrCodeCart) && $qrCodeCart) || session('current_masaismi') ? 'flex' : 'none' }}; position: absolute; left: 50%; transform: translateX(-50%); z-index: 20; background: linear-gradient(135deg, #1e293b, #0f172a); color: #f8fafc; padding: 6px 16px; border-radius: 20px; font-size: 0.88rem; font-weight: 700; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.1); white-space: nowrap;">
                <i class="fa-solid fa-chair" style="color: #fcd34d; font-size: 0.95rem;"></i>
                <span id="header-table-name">{{ $qrCodeCart ? $qrCodeCart->Masaismi : (session('current_masaismi') ?? '') }}</span>
            </div>
            
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <!-- Top Nav Bar (Masaüstü için Ana Sayfa ve Admin) -->
                <div class="top-nav-bar" style="padding: 0; gap: 1.5rem;">
                    <a href="{{ route('home') }}">Ana Sayfa</a>
                    <a href="javascript:void(0)" onclick="callWaiter()" style="color: #d97706;"><i class="fa-solid fa-bell"></i> Garson Çağır</a>
                    <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-lock"></i> Admin</a>
                </div>
                
                <div class="cart-icon-container" id="cart-icon">
                    <i class="fa-solid fa-basket-shopping cart-icon"></i>
                    <div class="cart-badge" id="cart-badge">0</div>
                </div>
            </div>
        </div>
    </div>

    <header>
        <!-- Ana Kategoriler Slider -->
        @if(isset($mainCategories) && $mainCategories->count() > 0)
        <div class="slider-wrapper">
            <button class="slider-btn left" onclick="scrollSlider('.main-categories-slider', -150)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="main-categories-slider" id="main-slider">
                @foreach($mainCategories as $mg)
                    <a href="{{ route('menu.show', urlencode($mg->anaGrup)) }}" class="mc-card {{ isset($mainCategory) && $mg->anaGrup == $mainCategory ? 'active' : '' }}">
                        @if($mg->anaGrupResimPath && file_exists(storage_path('app/public/' . $mg->anaGrupResimPath)))
                            <div class="mc-bg" style="background-image: url('{{ asset('storage/' . $mg->anaGrupResimPath) }}');"></div>
                        @else
                            <div class="mc-bg" style="background: linear-gradient(45deg, #a1c4fd, #c2e9fb);"></div>
                        @endif
                        <div class="mc-overlay"></div>
                        <span class="mc-title">{{ mb_strtoupper($mg->anaGrup, 'UTF-8') }}</span>
                    </a>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('.main-categories-slider', 150)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        @endif

        <!-- Arama Çubuğu -->
        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Arama...." onkeyup="filterProducts()">
            </div>
        </div>
        
        <div class="slider-wrapper slider-wrapper-subcat">
            <button class="slider-btn left" onclick="scrollSlider('#category-tabs', -150)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="subcat-slider" id="category-tabs">
                @foreach($categories as $index => $category)
                    <div class="subcat-pill {{ $index == 0 ? 'active' : '' }} category-item" data-target="cat-{{ $index }}" onclick="scrollToCategory('cat-{{ $index }}', this)">
                        {{ mb_strtoupper($category, 'UTF-8') }}
                    </div>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('#category-tabs', 150)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </header>

    <main>
        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
        <!-- Öne Çıkanlar (Featured Slider) -->
        <div class="featured-section">
            <h2 class="featured-title"><i class="fa-solid fa-fire text-orange-500"></i> Şefin Tavsiyesi</h2>
            <div class="slider-wrapper slider-wrapper-subcat" style="margin: 0 -1.25rem; padding: 0 1.25rem;">
                <button class="slider-btn left" style="left: 10px;" onclick="scrollSlider('.featured-slider', -150)"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="featured-slider" style="margin:0; padding-left:0; padding-right:0;">
                    @foreach($featuredProducts as $urun)
                        @php
                            $featCatName = mb_strtolower($urun->UrunGrubu ?? '', 'UTF-8');
                            $isFeatDrink = preg_match('/(drink|beer|şarap|wine|su|kahve|coffee|coffe|çay|tea|beverage|içecek|meşrubat|import|local|vodka|cin|rakı|bira|kokteyl|cocktail|cooktail|moctail|mocktail|ayran|kola|fanta|sprite|soda|milkshake|daiquiri|mojito|shot|frozen)/i', $featCatName);
                        @endphp
                        <div class="featured-card" onclick="openBottomSheet(this, event)" 
                             data-urun="{{ json_encode([
                                 'ad' => $urun->UrunAd,
                                 'aciklama' => $urun->UrunAciklama,
                                 'fiyat' => (float)$urun->FixFiyat,
                                 'kategori' => $urun->UrunGrubu ?? '',
                                 'is_drink' => $isFeatDrink ? 1 : 0,
                                 'has_lactose' => $urun->has_lactose ?? 0,
                                 'has_gluten' => $urun->has_gluten ?? 0,
                                 'malzemeler' => $urun->malzeme_listesi ?? [],
                                 'kalori' => $urun->kalori ?? '',
                                 'hazirlanma_suresi' => $urun->hazirlanma_suresi ?? '',
                                 'resim' => $urun->UrunResimPath ? asset('storage/' . $urun->UrunResimPath) : null
                             ]) }}"
                             data-vegan="{{ $urun->is_vegan }}" 
                             data-gluten="{{ $urun->has_gluten }}" 
                             data-lactose="{{ $urun->has_lactose }}" 
                             data-aci="{{ $urun->is_aci }}">
                            <div class="featured-img-wrapper">
                                @if($urun->UrunResimPath && $urun->UrunResimPath !== '0')
                                    <img src="{{ asset('storage/' . $urun->UrunResimPath) }}" alt="{{ $urun->UrunAdKisa ?? $urun->UrunAd }}" class="featured-img">
                                @else
                                    <div class="featured-img-placeholder"><i class="fa-solid fa-image"></i></div>
                                @endif
                            </div>
                            <div class="featured-info">
                                <h3 class="featured-name">{{ mb_strtoupper($urun->UrunAdKisa ?? $urun->UrunAd, 'UTF-8') }}</h3>
                                <div class="featured-price">₺{{ number_format((float)$urun->FixFiyat, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="slider-btn right" style="right: 10px;" onclick="scrollSlider('.featured-slider', 150)"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
        @endif
        @foreach($categories as $index => $category)
            @if(isset($productsByCategory[$category]) && $productsByCategory[$category]->count() > 0)
                <div class="category-section" id="cat-{{ $index }}" style="{{ $index != 0 ? 'display: none;' : '' }}">
                    <h2 class="section-title">{{ $category }}</h2>
                    
                    <div class="product-list">
                        @foreach($productsByCategory[$category] as $urun)
                            @php
                                $catName = mb_strtolower($category, 'UTF-8');
                                $isDrink = preg_match('/(drink|beer|şarap|wine|su|kahve|coffee|coffe|çay|tea|beverage|içecek|meşrubat|import|local|vodka|cin|rakı|bira|kokteyl|cocktail|cooktail|moctail|mocktail|ayran|kola|fanta|sprite|soda|milkshake|daiquiri|mojito|shot|frozen)/i', $catName);
                            @endphp
                            <div class="product-card" 
                                 data-vegan="{{ $urun->is_vegan }}" 
                                 data-gluten="{{ $urun->has_gluten }}" 
                                 data-aci="{{ $urun->is_aci }}" 
                                 data-lactose="{{ $urun->has_lactose }}"
                                 style="cursor: pointer;"
                                 data-urun="{{ json_encode([
                                     'ad' => $urun->UrunAd,
                                     'aciklama' => $urun->UrunAciklama,
                                     'fiyat' => (float)$urun->FixFiyat,
                                     'kategori' => $category,
                                     'is_drink' => $isDrink ? 1 : 0,
                                     'has_lactose' => $urun->has_lactose ?? 0,
                                     'has_gluten' => $urun->has_gluten ?? 0,
                                     'malzemeler' => $urun->malzeme_listesi ?? [],
                                     'kalori' => $urun->kalori ?? '',
                                     'hazirlanma_suresi' => $urun->hazirlanma_suresi ?? '',
                                     'resim' => ($urun->UrunResimPath && $urun->UrunResimPath !== '0') ? asset('storage/' . $urun->UrunResimPath) : ''
                                 ], JSON_UNESCAPED_UNICODE) }}"
                                 onclick="openBottomSheet(this, event)">
                                <div class="price-badge">₺{{ number_format((float)$urun->FixFiyat, 2) }}</div>
                                <div class="product-img-wrapper">
                                    @if($urun->UrunResimPath && $urun->UrunResimPath !== '0')
                                        <img src="{{ asset('storage/' . $urun->UrunResimPath) }}" class="product-img" alt="{{ $urun->UrunAd }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $urun->UrunResimPath) }}';">
                                    @else
                                        <div style="width:100%; height:100%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size: 0.75rem; font-weight: 500; text-align: center; padding: 10px;">
                                            <div style="display:flex; flex-direction:column; gap:6px; align-items:center;">
                                                <i class="fa-solid fa-image fa-2x"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <div>
                                        <h3 class="product-name">{{ $urun->UrunAd }}</h3>
                                        <!-- Hap (Badge) Etiketler -->
                                        <div class="badge-container">
                                            @if($urun->has_gluten == 1)
                                                <span class="badge"><i class="fa-solid fa-wheat-awn" style="color: #f59e0b;"></i> Gluten</span>
                                            @endif
                                            @if($urun->has_lactose == 1)
                                                <span class="badge"><i class="fa-solid fa-cow" style="color: #60a5fa;"></i> Laktoz</span>
                                            @endif
                                            @if($urun->is_aci == 1)
                                                <span class="badge"><i class="fa-solid fa-pepper-hot text-red-500"></i> Acı</span>
                                            @endif
                                        </div>
                                        @if($urun->UrunAciklama && $urun->UrunAciklama !== '0')
                                            <p class="product-desc">{{ $urun->UrunAciklama }}</p>
                                        @endif
                                    </div>
                                    <div class="product-footer">
                                        <div class="product-price">₺{{ number_format((float)$urun->FixFiyat, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </main>

    <!-- Subtle Branding -->
    <div style="text-align: center; padding: 1rem; margin-bottom: 2rem; color: #cbd5e1; font-size: 0.7rem; letter-spacing: 0.5px; opacity: 0.7;">
        <i class="fa-solid fa-bolt" style="color: #fcd34d; margin-right: 3px;"></i> Powered by <a href="#" style="font-weight: 600; color: #94a3b8; text-decoration: none;">Mikale Yazılım</a>
    </div>

    <div class="fab" id="view-cart-btn" style="display: none;">
        <i class="fa-solid fa-basket-shopping"></i>
        Sepeti Gör <span id="fab-total">₺0</span>
    </div>

    <!-- Hamburger Sidebar -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>{{ $settings && $settings->baslik ? $settings->baslik : 'Menü' }}</h3>
            <i class="fa-solid fa-xmark" onclick="toggleSidebar()" style="font-size: 1.5rem; cursor: pointer; color: var(--text);"></i>
        </div>
        <div class="sidebar-content">
            
            <!-- Kategoriler Accordion Header -->
            <div onclick="toggleSidebarSection('cats-section')" style="padding: 0.75rem 1.25rem; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                Kategoriler <i class="fa-solid fa-chevron-down" id="cats-section-icon" style="transition: transform 0.3s; transform: rotate(180deg);"></i>
            </div>
            <!-- Kategoriler Accordion Content -->
            <div id="cats-section" style="margin-bottom: 1rem; overflow: hidden; transition: max-height 0.3s ease-out; max-height: 1000px;">
                @foreach($categories as $index => $category)
                    <div class="sidebar-item" onclick="scrollToCategory('cat-{{ $index }}', document.querySelector('[data-target=\'cat-{{ $index }}\']')); toggleSidebar()">
                        <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem; color: #cbd5e1; margin-right: 8px;"></i> {{ $category }}
                    </div>
                @endforeach
            </div>

            <!-- İletişim Bilgileri Accordion Header -->
            <div onclick="toggleSidebarSection('contact-section')" style="padding: 0.75rem 1.25rem; margin-bottom: 1rem; font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                İletişim Bilgileri <i class="fa-solid fa-chevron-down" id="contact-section-icon" style="transition: transform 0.3s; transform: rotate(180deg);"></i>
            </div>
            <!-- İletişim Bilgileri Accordion Content -->
            <div id="contact-section" style="overflow: hidden; transition: max-height 0.3s ease-out; max-height: 1000px;">
                <div style="padding: 0 1.25rem 2rem; display: flex; flex-direction: column; gap: 1.25rem;">
                    @if($settings && $settings->telefon)
                        <a href="tel:{{ $settings->telefon }}" style="display: flex; align-items: center; gap: 12px; color: var(--text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-phone" style="color: var(--primary);"></i></div>
                            {{ $settings->telefon }}
                        </a>
                    @endif
                    
                    @if($settings && $settings->whatsapp_number)
                        <a href="https://wa.me/{{ $settings->whatsapp_number }}" target="_blank" style="display: flex; align-items: center; gap: 12px; color: var(--text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center;"><i class="fa-brands fa-whatsapp" style="font-size: 1.1rem; color: #25D366;"></i></div>
                            WhatsApp Hattı
                        </a>
                    @endif

                    @if($settings && $settings->instagram_url)
                        <a href="{{ $settings->instagram_url }}" target="_blank" style="display: flex; align-items: center; gap: 12px; color: var(--text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center;"><i class="fa-brands fa-instagram" style="font-size: 1.1rem; color: #E1306C;"></i></div>
                            Instagram
                        </a>
                    @endif

                    @if($settings && $settings->adres)
                        <div style="display: flex; align-items: flex-start; gap: 12px; color: var(--text); font-size: 0.9rem; line-height: 1.4;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fa-solid fa-location-dot" style="color: var(--primary);"></i></div>
                            <div style="padding-top: 4px;">
                                {{ $settings->adres }}
                                @if($settings->google_map_url)
                                    <div style="margin-top: 6px;"><a href="{{ $settings->google_map_url }}" target="_blank" style="color: var(--primary); text-decoration: none; font-size: 0.85rem; font-weight: 600;">Haritada Gör &rarr;</a></div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($settings && $settings->wifi_ssid)
                        <div style="margin-top: 0.5rem; padding-top: 1.25rem; border-top: 1px dashed var(--border); display: flex; align-items: center; gap: 12px; color: var(--text);">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fa-solid fa-wifi" style="font-size: 1.1rem; color: #3b82f6;"></i></div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.95rem;">Ağ: {{ $settings->wifi_ssid }}</div>
                                @if($settings->wifi_password)
                                    <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 2px;">Şifre: {{ $settings->wifi_password }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar Footer Branding -->
        <div style="margin-top: auto; padding: 1rem; border-top: 1px solid var(--border); text-align: center; background: #f8fafc;">
            <div style="font-size: 0.65rem; color: #94a3b8; letter-spacing: 0.5px;">
                <i class="fa-solid fa-bolt" style="color: #fcd34d;"></i>  <span style="font-weight: 600;">Mikale Yazılım</span>
            </div>
        </div>
    </div>

    <!-- Bottom Sheet Modal -->
    <div class="bottom-sheet-overlay" id="bs-overlay" onclick="closeBottomSheet()"></div>
    <div class="bottom-sheet" id="bottom-sheet">
        <div class="bs-header" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div class="drag-handle"></div>
            <img id="bs-product-img" src="" alt="" class="bs-product-img">
            <h3 class="bs-title" id="bs-product-name">Ürün Adı</h3>
            <p class="bs-desc" id="bs-product-desc" style="margin-bottom: 12px;">Ürün açıklaması</p>
            <div id="bs-info-chips" style="display: flex; gap: 8px; justify-content: flex-start; flex-wrap: wrap; margin-bottom: 8px;"></div>
        </div>
        
        <div class="bs-content">
            <!-- Dinamik Seçenekler (JS ile doldurulacak) -->
            <div id="dynamic-options-container"></div>
            
            <!-- Bottom Sheet Branding -->
            <div style="margin-top: 2rem; text-align: center; font-size: 0.65rem; color: #cbd5e1; letter-spacing: 0.5px;">
                <i class="fa-solid fa-bolt" style="color: #fcd34d; opacity: 0.8;"></i> <span style="font-weight: 500;">Mikale Yazılım</span>
            </div>
        </div>

        {{-- Sepete Ekle Butonu Geçici Olarak Kapatıldı
        <div class="bs-footer">
            <button class="btn-submit" onclick="submitBottomSheet()">
                <span>Sepete Ekle</span>
                <span id="bs-total-price">₺0</span>
            </button>
        </div>
        --}}
    </div>

    <!-- Mobil Alt Sabit Menü -->
    <div class="mobile-bottom-nav">
        <a href="{{ route('home') }}">
            <i class="fa-solid fa-house"></i>
            <span>Ana Sayfa</span>
        </a>
        <a href="javascript:void(0)" onclick="callWaiter()">
            <i class="fa-solid fa-bell" style="color: #fcd34d;"></i>
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

    <!-- Hidden QrCode -->
    <input type="hidden" id="qrcode_val" value="{{ $qrcode ?? (session('current_qrcode') ?? '') }}">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentQr = document.getElementById('qrcode_val') ? document.getElementById('qrcode_val').value : '';
            let serverTableName = document.getElementById('header-table-name') ? document.getElementById('header-table-name').textContent.trim() : '';
            
            if (currentQr && serverTableName) {
                localStorage.setItem('menu_qrcode', currentQr);
                localStorage.setItem('menu_table_name', serverTableName);
            } else if (!currentQr && localStorage.getItem('menu_qrcode')) {
                if (document.getElementById('qrcode_val')) {
                    document.getElementById('qrcode_val').value = localStorage.getItem('menu_qrcode');
                }
                if (document.getElementById('header-table-name')) {
                    let tName = localStorage.getItem('menu_table_name') || 'Masa';
                    document.getElementById('header-table-name').textContent = tName;
                    document.getElementById('header-table-badge').style.display = 'flex';
                }
            }
        });

        function scrollSlider(selector, offset) {
            const slider = document.querySelector(selector);
            if (slider) {
                slider.scrollBy({ left: offset, behavior: 'smooth' });
            }
        }

        function callWaiter() {
            let qrCode = (document.getElementById('qrcode_val') ? document.getElementById('qrcode_val').value : '') || localStorage.getItem('menu_qrcode') || '';
            if (!qrCode) {
                let err = document.getElementById('waiter-error');
                err.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Lütfen menüye masanızdaki QR kodu okutarak giriniz!';
                err.style.display = 'flex';
                setTimeout(() => { err.style.display = 'none'; }, 2500);
                return;
            }

            fetch("/api/v1/call/waiter/" + qrCode, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.text())
            .then(text => {
                let notif = document.getElementById('waiter-notification');
                if (text === "ok") {
                    notif.innerHTML = '<i class="fa-solid fa-check-circle"></i> Garson çağrıldı! Birazdan masanızla ilgilenilecek.';
                    notif.style.background = '#22c55e';
                } else {
                    notif.innerHTML = '<i class="fa-solid fa-clock"></i> ' + text;
                    notif.style.background = '#f59e0b';
                }
                notif.style.display = 'flex';
                setTimeout(() => { notif.style.display = 'none'; }, 3000);
            })
            .catch(err => {
                let errEl = document.getElementById('waiter-error');
                errEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Bir hata oluştu.';
                errEl.style.display = 'flex';
                setTimeout(() => { errEl.style.display = 'none'; }, 2500);
            });
        }


        let cartCount = 0;
        let cartTotal = 0;
        let isScrolling = false;
        let activeFilter = 'all';

        // Arama ve Filtreleme Mantığı
        function toggleFilter(element, filterType) {
            document.querySelectorAll('.filter-pill').forEach(pill => pill.classList.remove('active'));
            element.classList.add('active');
            activeFilter = filterType;
            filterProducts(); 
        }

        function filterProducts() {
            const query = document.getElementById('searchInput').value.toLocaleLowerCase('tr-TR');
            const sections = document.querySelectorAll('.category-section');

            sections.forEach(section => {
                const categoryTitle = section.querySelector('.section-title').textContent.toLocaleLowerCase('tr-TR');
                const products = section.querySelectorAll('.product-card');
                let hasVisibleProduct = false;

                products.forEach(product => {
                    const productNameEl = product.querySelector('.product-name');
                    const productDescEl = product.querySelector('.product-desc');
                    
                    const productName = productNameEl ? productNameEl.textContent.toLocaleLowerCase('tr-TR') : '';
                    const productDesc = productDescEl ? productDescEl.textContent.toLocaleLowerCase('tr-TR') : '';
                    
                    // Rozetleri (badges) topla
                    const badgesText = Array.from(product.querySelectorAll('.badge')).map(b => b.textContent.toLocaleLowerCase('tr-TR')).join(" ");

                    // 1. Arama Metni Eşleşmesi
                    const matchesSearch = query === '' || 
                                          categoryTitle.includes(query) || 
                                          productName.includes(query) || 
                                          productDesc.includes(query);

                    // 2. Hap Filtre Eşleşmesi
                    let matchesFilter = true;
                    if (activeFilter === 'laktoz') {
                        matchesFilter = product.getAttribute('data-lactose') === '1';
                    } else if (activeFilter === 'gluten') {
                        matchesFilter = product.getAttribute('data-gluten') === '1';
                    }

                    if (matchesSearch && matchesFilter) {
                        product.style.display = 'flex';
                        hasVisibleProduct = true;
                    } else {
                        product.style.display = 'none';
                    }
                });

                // Arama metni veya filtre varken eşleşen kategorileri göster, normalde sadece aktif sekme görünsün
                const isSearching = (query !== '' || activeFilter !== 'all');
                if (isSearching) {
                    section.style.display = hasVisibleProduct ? 'block' : 'none';
                } else {
                    const activeTab = document.querySelector('.category-item.active');
                    const targetId = activeTab ? activeTab.getAttribute('data-target') : 'cat-0';
                    section.style.display = (section.getAttribute('id') === targetId) ? 'block' : 'none';
                }
            });
        }

        function checkArrows() {
            document.querySelectorAll('.slider-wrapper').forEach(wrapper => {
                const slider = wrapper.querySelector('.main-categories-slider, .subcat-slider, .featured-slider');
                const leftBtn = wrapper.querySelector('.slider-btn.left');
                const rightBtn = wrapper.querySelector('.slider-btn.right');
                
                if (slider && leftBtn && rightBtn) {
                    if (slider.scrollWidth > slider.clientWidth) {
                        leftBtn.style.display = slider.scrollLeft <= 0 ? 'none' : 'flex';
                        rightBtn.style.display = Math.ceil(slider.scrollLeft + slider.clientWidth) >= slider.scrollWidth ? 'none' : 'flex';
                    } else {
                        leftBtn.style.display = 'none';
                        rightBtn.style.display = 'none';
                    }
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Okların durumunu kontrol et
            checkArrows();
            window.addEventListener('resize', checkArrows);
            document.querySelectorAll('.main-categories-slider, .subcat-slider, .featured-slider').forEach(slider => {
                slider.addEventListener('scroll', checkArrows);
            });
        });

        function scrollToCategory(id, element) {
            // Sekme değiştirirken eski arama kaldıysa temizle ki o sekmenin ürünleri görünsün
            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value !== '') {
                searchInput.value = '';
                document.querySelectorAll('.product-card').forEach(p => p.style.display = 'flex');
            }

            // Aktif sekmeyi güncelle ve slider'da merkeze kaydır
            document.querySelectorAll('.category-item').forEach(chip => chip.classList.remove('active'));
            if(element) {
                element.classList.add('active');
                const slider = document.querySelector('.subcat-slider');
                if(slider) {
                    slider.scrollTo({
                        left: element.offsetLeft - (slider.offsetWidth / 2) + (element.offsetWidth / 2),
                        behavior: 'smooth'
                    });
                }
            }
            
            // Seçilen kategoriyi göster, diğerlerini gizle (Sekme / Tab yapısı - Sayfa aşağı kaymaz!)
            document.querySelectorAll('.category-section').forEach(sec => {
                if (sec.getAttribute('id') === id) {
                    sec.style.display = 'block';
                } else {
                    sec.style.display = 'none';
                }
            });

            // Kullanıcı uzun bir listede çok aşağı indiyse sekme değiştiğinde üst kısmı kolay görsün
            if (window.pageYOffset > 250) {
                window.scrollTo({ top: 0, behavior: "smooth" });
            }
        }

        function toggleSidebarSection(id) {
            const el = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');
            
            if (el.style.maxHeight === '0px' || el.style.maxHeight === '') {
                // Expand
                el.style.maxHeight = el.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
                // Automatically set to none after transition so content can expand if window resizes
                setTimeout(() => el.style.maxHeight = '1000px', 300); 
            } else {
                // Collapse
                el.style.maxHeight = el.scrollHeight + 'px'; // Set to explicit px before collapse for animation
                // Force repaint
                void el.offsetWidth;
                el.style.maxHeight = '0px';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebar-overlay').classList.toggle('active');
            if(document.getElementById('sidebar').classList.contains('active')){
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function openBottomSheet(btn, event) {
            event.stopPropagation();
            
            let urunData;
            try {
                urunData = JSON.parse(btn.getAttribute('data-urun'));
            } catch (e) {
                console.error("Urun datasi parse edilemedi", e);
                return;
            }
            
            document.getElementById('bs-product-name').textContent = urunData.ad;
            document.getElementById('bs-product-desc').textContent = urunData.aciklama || '';
            
            // Info Chips Render
            let infoHtml = '';
            const isDrink = (urunData.is_drink == 1);
            
            if (urunData.fiyat) infoHtml += `<span style="background: #f1f5f9; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; color: var(--primary);">₺${urunData.fiyat}</span>`;
            if (urunData.kalori) infoHtml += `<span style="background: #fef2f2; color: #ef4444; padding: 4px 10px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;"><i class="fa-solid fa-fire" style="margin-right: 4px;"></i>~${urunData.kalori} kcal</span>`;
            if (urunData.hazirlanma_suresi) infoHtml += `<span style="background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;"><i class="fa-solid fa-clock" style="margin-right: 4px;"></i>~${urunData.hazirlanma_suresi}</span>`;
            
            if (isDrink && urunData.has_lactose == 1) {
                infoHtml += `<div style="width: 100%; text-align: left; margin-top: 6px; font-size: 0.85rem; color: #64748b; font-weight: 500;"><i class="fa-solid fa-circle-info" style="color: #60a5fa; margin-right: 4px;"></i>Laktozsuz seçeneği bulunmaktadır.</div>`;
            }
            
            document.getElementById('bs-info-chips').innerHTML = infoHtml;
            
            const imgEl = document.getElementById('bs-product-img');
            if (urunData.resim) {
                imgEl.src = urunData.resim;
                imgEl.style.display = 'block';
            } else {
                imgEl.style.display = 'none';
            }
            
            currentBasePrice = parseFloat(urunData.fiyat) || 0;
            clickedButtonElement = btn;
            
            document.querySelectorAll('.extra-item-cb').forEach(cb => cb.checked = false);
            
            let dynamicOptionsHtml = '';
            
            const dynamicContainer = document.getElementById('dynamic-options-container');
            if (dynamicContainer) {
                dynamicContainer.innerHTML = dynamicOptionsHtml;
            }

            calculateTotal();
            
            document.getElementById('bottom-sheet').classList.add('active');
            document.getElementById('bs-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeBottomSheet() {
            document.getElementById('bottom-sheet').classList.remove('active');
            document.getElementById('bs-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        function calculateTotal() {
            let total = currentBasePrice;
            document.querySelectorAll('.extra-item-cb:checked').forEach(cb => {
                let p = parseFloat(cb.getAttribute('data-price'));
                if (!isNaN(p)) {
                    total += p;
                } else if (!isNaN(parseFloat(cb.value))) {
                    total += parseFloat(cb.value);
                }
            });
            let priceEl = document.getElementById('bs-total-price');
            if (priceEl) {
                priceEl.textContent = `₺${total.toFixed(2)}`;
            }
        }

        function submitBottomSheet() {
            closeBottomSheet();
            if(clickedButtonElement) {
                // Eklenen toplam fiyatla animasyonu tetikle
                const calculatedPrice = parseFloat(document.getElementById('bs-total-price').textContent.replace('₺', ''));
                addToCart(clickedButtonElement, null, calculatedPrice);
            }
        }

        function addToCart(btn, event, customPrice = null) {
            if (btn.classList.contains('added')) return;

            // Change button to checkmark
            btn.innerHTML = '<i class="fa-solid fa-check"></i>';
            btn.classList.add('added');
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fa-solid fa-plus"></i>';
                btn.classList.remove('added');
            }, 1500);

            // Flying dot animation logic
            const rect = btn.getBoundingClientRect();
            const startX = rect.left + rect.width / 2;
            const startY = rect.top + rect.height / 2;
            
            const cartIcon = document.getElementById('cart-icon');
            const cartRect = cartIcon.getBoundingClientRect();
            const endX = cartRect.left + cartRect.width / 2;
            const endY = cartRect.top + cartRect.height / 2;

            const dot = document.createElement('div');
            dot.className = 'flying-dot';
            dot.style.left = startX + 'px';
            dot.style.top = startY + 'px';
            dot.style.opacity = '1';
            
            document.body.appendChild(dot);

            // Trigger reflow
            void dot.offsetWidth;

            // Move the dot
            dot.style.transform = `translate(${endX - startX}px, ${endY - startY}px) scale(0.5)`;
            dot.style.opacity = '0.5';

            // Animation completion
            setTimeout(() => {
                dot.remove();
                
                // Update Badge
                cartCount++;
                const badge = document.getElementById('cart-badge');
                badge.textContent = cartCount;
                badge.classList.add('active');

                // Bounce Cart Icon
                cartIcon.style.transform = 'scale(1.2)';
                setTimeout(() => cartIcon.style.transform = 'scale(1)', 200);

                // Update Total
                let price = customPrice;
                if (price === null) {
                    const priceText = btn.parentElement.querySelector('.product-price').textContent;
                    price = parseFloat(priceText.replace('₺', ''));
                }
                cartTotal += price;

                // Show/Update FAB
                const fab = document.getElementById('view-cart-btn');
                document.getElementById('fab-total').textContent = '₺' + cartTotal.toFixed(2);
                
                if(cartCount === 1) {
                    fab.style.display = 'flex';
                    fab.style.animation = 'slideUp 0.3s ease forwards';
                }

            }, 600); // Wait for transition
        }

        // Okları içeriğin sığıp sığmamasına göre gizle/göster
        function updateSliderArrows() {
            const wrappers = document.querySelectorAll('.slider-wrapper');
            wrappers.forEach(wrapper => {
                const slider = wrapper.querySelector('.main-categories-slider, .subcat-slider, .featured-slider');
                if (slider) {
                    const leftBtn = wrapper.querySelector('.slider-btn.left');
                    const rightBtn = wrapper.querySelector('.slider-btn.right');
                    
                    // İçerik genişliği görünür genişlikten büyükse kaydırma vardır
                    if (slider.scrollWidth > slider.clientWidth) {
                        if(leftBtn) leftBtn.style.display = 'flex';
                        if(rightBtn) rightBtn.style.display = 'flex';
                    } else {
                        if(leftBtn) leftBtn.style.display = 'none';
                        if(rightBtn) rightBtn.style.display = 'none';
                    }
                }
            });
        }

        // Sayfa yüklendiğinde ve ekran boyutu değiştiğinde çalıştır
        document.addEventListener('DOMContentLoaded', updateSliderArrows);
        window.addEventListener('resize', updateSliderArrows);
        
        // Fontlar, resimler yüklendikten sonra genişlikler değişebileceği için window.onload'a da ekleyelim
        window.addEventListener('load', updateSliderArrows);
    </script>
    <style>
        @keyframes slideUp {
            from { transform: translate(-50%, 20px); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }
    </style>

</body>
</html>
