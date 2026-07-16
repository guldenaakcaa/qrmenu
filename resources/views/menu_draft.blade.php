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
        body { font-family: 'Outfit', sans-serif; background-color: var(--bg); color: var(--text); padding-bottom: 80px; }

        /* Header & Sticky Categories */
        header { background: var(--surface); position: sticky; top: 0; z-index: 50; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; }
        .logo { font-size: 1.25rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
        .logo i { color: var(--primary); }
        
        /* Cart Icon */
        .cart-icon-container { position: relative; }
        .cart-icon { font-size: 1.35rem; color: var(--text); transition: 0.2s; }
        .cart-badge { position: absolute; top: -6px; right: -8px; background: var(--primary); color: white; font-size: 0.65rem; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transform: scale(0); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .cart-badge.active { transform: scale(1); }

        .categories { display: flex; overflow-x: auto; padding: 1rem 1.25rem; gap: 1.25rem; scrollbar-width: none; scroll-behavior: smooth; border-top: 1px solid var(--border); }
        .categories::-webkit-scrollbar { display: none; }
        .category-item { display: flex; flex-direction: column; align-items: center; gap: 8px; cursor: pointer; min-width: max-content; }
        .category-circle { width: 56px; height: 56px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; transition: 0.3s; background: var(--surface); }
        .category-circle i { font-size: 1.4rem; color: #94a3b8; transition: 0.3s; }
        .category-name { font-size: 0.8rem; font-weight: 600; color: #64748b; transition: 0.3s; }
        .category-item.active .category-circle { border-color: var(--primary); box-shadow: 0 4px 10px rgba(255,107,107,0.2); }
        .category-item.active .category-circle i { color: var(--primary); }
        .category-item.active .category-name { color: var(--text); }

        /* Search Bar & Filters */
        .search-container { padding: 0.5rem 1.25rem 0.5rem; background: var(--surface); }
        .search-box { display: flex; align-items: center; background: var(--bg); border: 1px solid var(--border); border-radius: 20px; padding: 0.65rem 1rem; }
        .search-box i { color: #94a3b8; font-size: 1.1rem; margin-right: 0.75rem; }
        .search-box input { border: none; background: transparent; flex: 1; font-family: inherit; font-size: 0.95rem; color: var(--text); outline: none; }
        .search-box input::placeholder { color: #94a3b8; }

        .filter-pills { display: flex; overflow-x: auto; padding: 0.5rem 1.25rem 0.5rem; gap: 0.75rem; scrollbar-width: none; background: var(--surface); }
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
        .section-title { font-size: 1.35rem; font-weight: 700; margin-top: 3rem; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.5px; color: #1e293b; }
        .category-section:first-child .section-title { margin-top: 1rem; }

        /* Product Cards */
        .product-list { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem; }
        .product-card { background: var(--surface); border-radius: var(--radius); padding: 1rem; display: flex; gap: 1rem; box-shadow: var(--shadow); position: relative; }
        
        .product-img-wrapper { width: 120px; height: 120px; border-radius: 20px; overflow: hidden; flex-shrink: 0; border: 1px solid var(--border); }
        .product-img { width: 100%; height: 100%; object-fit: cover; }
        
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

        /* Add to Cart Button */
        .btn-add { width: 36px; height: 36px; border-radius: 50%; background: var(--primary); border: none; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; transition: 0.3s; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3); }
        .btn-add:active { transform: scale(0.9); }
        .btn-add.added { background: #2ecc71; box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3); }

        /* Flying dot animation */
        .flying-dot { position: fixed; width: 14px; height: 14px; background: var(--primary); border-radius: 50%; z-index: 1000; pointer-events: none; transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1); opacity: 0; }
        
        /* Floating Action Button */
        .fab { position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%); background: var(--text); color: white; padding: 0.85rem 1.5rem; border-radius: 30px; font-weight: 500; font-size: 0.95rem; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 8px; z-index: 99; transition: 0.3s; width: max-content; }
        .fab span { font-weight: 700; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; }

        /* Bottom Sheet (Modal) */
        .bottom-sheet-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; backdrop-filter: blur(2px); }
        .bottom-sheet-overlay.active { opacity: 1; pointer-events: auto; }
        
        .bottom-sheet { position: fixed; bottom: 0; left: 0; width: 100%; background: var(--surface); border-radius: 28px 28px 0 0; z-index: 1001; transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.2, 0.9, 0.3, 1); max-height: 95vh; height: auto; display: flex; flex-direction: column; box-shadow: 0 -10px 40px rgba(0,0,0,0.1); }
        .bottom-sheet.active { transform: translateY(0); }
        
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

    </style>
</head>
<body>

    <header>
        <div class="top-bar">
            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="/" style="color: var(--text); font-size: 1.2rem; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; background: var(--bg); text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <div class="hamburger-menu" onclick="toggleSidebar()" style="cursor: pointer; margin-left: 5px; display: flex; align-items: center;">
                    <i class="fa-solid fa-bars" style="font-size: 1.5rem; color: var(--text);"></i>
                </div>
                <div class="logo" style="margin-left: 5px;">
                    @if($settings && $settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}" style="height: 24px; border-radius: 4px;" alt="Logo">
                    @else
                        <i class="fa-solid fa-utensils"></i> 
                    @endif
                    {{ $settings && $settings->baslik ? $settings->baslik : 'Center Cafe' }}
                </div>
            </div>
            <div style="display: flex; gap: 1.25rem; align-items: center;">
                <div class="cart-icon-container" id="cart-icon">
                    <i class="fa-solid fa-basket-shopping cart-icon"></i>
                    <div class="cart-badge" id="cart-badge">0</div>
                </div>
            </div>
        </div>
        
        <!-- Arama Çubuğu -->
        <div class="search-container">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Yemek veya kategori ara..." onkeyup="filterProducts()">
            </div>
        </div>

        <!-- Hap Filtreler -->
        <div class="filter-pills">
            <div class="filter-pill active" onclick="toggleFilter(this, 'all')"><i class="fa-solid fa-star text-yellow-400"></i> Tümü</div>
            <div class="filter-pill" onclick="toggleFilter(this, 'laktoz')"><i class="fa-solid fa-cow" style="color: #60a5fa;"></i> Laktoz</div>
            <div class="filter-pill" onclick="toggleFilter(this, 'gluten')"><i class="fa-solid fa-wheat-awn text-amber-500"></i> Gluten</div>
        </div>
        
        <div class="categories" id="category-tabs">
            @foreach($categories as $index => $category)
                <div class="category-item {{ $index == 0 ? 'active' : '' }}" data-target="cat-{{ $index }}" onclick="scrollToCategory('cat-{{ $index }}', this)">
                    <div class="category-circle">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div class="category-name">{{ $category }}</div>
                </div>
            @endforeach
        </div>
    </header>

    <main>
        @foreach($categories as $index => $category)
            @if(isset($productsByCategory[$category]) && $productsByCategory[$category]->count() > 0)
                <div class="category-section" id="cat-{{ $index }}">
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
                                        {{-- Sepete ekle butonu şimdilik gizlendi, ürün kartına tıklandığında açılıyor --}}
                                        {{-- 
                                        <button class="btn-add">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </main>



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
    </div>

    <!-- Bottom Sheet Modal -->
    <div class="bottom-sheet-overlay" id="bs-overlay" onclick="closeBottomSheet()"></div>
    <div class="bottom-sheet" id="bottom-sheet">
        <div class="bs-header" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div class="drag-handle"></div>
            <img id="bs-product-img" src="" alt="" style="width: 240px; height: 240px; border-radius: 24px; object-fit: cover; margin-bottom: 16px; display: none; box-shadow: 0 8px 25px rgba(0,0,0,0.15);">
            <h3 class="bs-title" id="bs-product-name">Ürün Adı</h3>
            <p class="bs-desc" id="bs-product-desc" style="margin-bottom: 12px;">Ürün açıklaması</p>
            <div id="bs-info-chips" style="display: flex; gap: 8px; justify-content: flex-start; flex-wrap: wrap; margin-bottom: 8px;"></div>
        </div>
        
        <div class="bs-content">
            <!-- Dinamik Seçenekler (JS ile doldurulacak) -->
            <div id="dynamic-options-container"></div>
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

    <script>
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

                // Hiç eşleşen ürün yoksa kategoriyi tamamen gizle
                if (hasVisibleProduct) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            const sections = document.querySelectorAll(".category-section");
            const navChips = document.querySelectorAll(".category-item");

            // Intersection Observer (Scrollspy)
            const observerOptions = {
                root: null,
                rootMargin: "-150px 0px -60% 0px", // Header offset'ini hesaba katarak
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                if (isScrolling) return; // Kullanıcı butona basarak scroll yapıyorsa göz ardı et

                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute("id");
                        
                        navChips.forEach(chip => {
                            chip.classList.remove("active");
                            if (chip.getAttribute("data-target") === id) {
                                chip.classList.add("active");
                                chip.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                            }
                        });
                    }
                });
            }, observerOptions);

            sections.forEach(sec => observer.observe(sec));
        });

        function scrollToCategory(id, element) {
            isScrolling = true;

            // Aktif çipi güncelle ve merkeze kaydır
            document.querySelectorAll('.category-item').forEach(chip => chip.classList.remove('active'));
            if(element) element.classList.add('active');
            if(element) element.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            
            // Bölüme yumuşak kaydırma
            const el = document.getElementById(id);
            if(el) {
                const headerOffset = 210; // Arama barı vs. eklendiği için offseti arttırdık
                const elementPosition = el.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                     top: offsetPosition,
                     behavior: "smooth"
                });

                // Scroll bittikten sonra Observer'ı tekrar aktifleştir (yaklaşık 800ms)
                setTimeout(() => {
                    isScrolling = false;
                }, 800);
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
            const isDrink = urunData.is_drink === 1;
            
            if (urunData.fiyat) infoHtml += `<span style="background: #f1f5f9; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; color: var(--primary);">₺${urunData.fiyat}</span>`;
            if (urunData.kalori) infoHtml += `<span style="background: #fef2f2; color: #ef4444; padding: 4px 10px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;"><i class="fa-solid fa-fire" style="margin-right: 4px;"></i>~${urunData.kalori} kcal</span>`;
            if (urunData.hazirlanma_suresi) infoHtml += `<span style="background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;"><i class="fa-solid fa-clock" style="margin-right: 4px;"></i>~${urunData.hazirlanma_suresi}</span>`;
            
            if (isDrink && urunData.has_lactose === 1) {
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
    </script>
    <style>
        @keyframes slideUp {
            from { transform: translate(-50%, 20px); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }
    </style>
</body>
</html>
