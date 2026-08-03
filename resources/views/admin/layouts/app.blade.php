<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Paneli') - QR Menü</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f8fafc;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --white: #ffffff;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); color: var(--text-main); display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: var(--white); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; }
        .sidebar-header { padding: 1.5rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-header i { font-size: 1.5rem; color: var(--primary-color); }
        .sidebar-header h2 { font-size: 1.25rem; font-weight: 600; letter-spacing: 0.5px; margin: 0;}
        .nav-menu { padding: 1.5rem 0; flex-grow: 1; display: flex; flex-direction: column; gap: 5px; }
        .nav-item { padding: 0.875rem 1.5rem; display: flex; align-items: center; gap: 12px; color: #cbd5e1; text-decoration: none; transition: var(--transition); border-left: 3px solid transparent; }
        .nav-item:hover { background-color: var(--sidebar-hover); color: var(--white); }
        .nav-item.active { background-color: rgba(79, 70, 229, 0.1); color: var(--white); border-left-color: var(--primary-color); }
        .nav-item i { width: 20px; text-align: center; font-size: 1.1rem; }

        /* Main Content */
        .main-content { flex: 1; margin-left: 260px; padding: 2rem; display: flex; flex-direction: column; gap: 2rem; min-width: 0; }
        .top-header { display: flex; justify-content: space-between; align-items: center; background: var(--white); padding: 1rem 2rem; border-radius: 12px; box-shadow: var(--card-shadow); gap: 1rem; flex-wrap: wrap; }
        .top-header h1 { font-size: 1.25rem; font-weight: 600; margin: 0; }
        .user-profile { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-color); color: var(--white); display: flex; align-items: center; justify-content: center; font-weight: 600; }

        /* Common */
        .card { background: var(--white); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .btn { padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; font-size: 0.875rem; text-decoration: none; }
        .btn-primary { background-color: var(--primary-color); color: var(--white); }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-secondary { background-color: #64748b; color: var(--white); }
        .btn-secondary:hover { background-color: #475569; }

        /* Tables */
        .table-container { background: var(--white); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow); max-width: 100%; overflow: hidden; }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .table-header h3 { font-size: 1.125rem; font-weight: 600; margin: 0; }
        .table-responsive { overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { color: var(--text-muted); font-weight: 500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; }
        td { color: var(--text-main); font-size: 0.95rem; }
        tbody tr { transition: var(--transition); }
        tbody tr:hover { background-color: #f8fafc; }
        .action-btns { display: flex; gap: 8px; }
        .btn-icon { background: none; border: none; color: var(--text-muted); cursor: pointer; transition: var(--transition); font-size: 1rem; padding: 4px; }
        .btn-icon.edit:hover { color: #3b82f6; }
        .btn-icon.delete:hover { color: #ef4444; }

        /* Forms */
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-main); font-size: 0.875rem; }
        .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-main); transition: var(--transition); background-color: #fff; }
        .form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* Alerts */
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .alert-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; }
        .stat-card { background: var(--white); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow); display: flex; align-items: center; gap: 1.5rem; transition: var(--transition); cursor: pointer; border: 1px solid transparent; }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--primary-color); box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.1); }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; }
        .stat-icon.blue { background-color: #eff6ff; color: #3b82f6; }
        .stat-icon.green { background-color: #f0fdf4; color: #22c55e; }
        .stat-icon.orange { background-color: #fff7ed; color: #f97316; }
        .stat-icon.purple { background-color: #faf5ff; color: #a855f7; }
        .stat-info h3 { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 500; }
        .stat-info p { font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0; }
        /* Sidebar Overlay (Mobil) */
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99; backdrop-filter: blur(2px); }
        .sidebar-overlay.active { display: block; }

        @media (max-width: 768px) {
            body { overflow-x: hidden; }
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; width: 260px; }
            .sidebar.active { transform: translateX(0); box-shadow: 4px 0 15px rgba(0,0,0,0.2); }
            .main-content { margin-left: 0; padding: 0.75rem; gap: 1rem; width: 100%; max-width: 100vw; overflow-x: hidden; }
            .mobile-toggle { display: block !important; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .stat-card { padding: 1rem; gap: 0.75rem; }
            .stat-icon { width: 42px; height: 42px; font-size: 1.25rem; border-radius: 10px; }
            .stat-info h3 { font-size: 0.75rem; }
            .stat-info p { font-size: 1.15rem; }
            .top-header { padding: 0.75rem 1rem; flex-wrap: wrap; }
            .top-header h1 { font-size: 1rem; }
            .card { padding: 1rem; }
            .table-container { padding: 0.75rem; }
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            th, td { padding: 0.6rem 0.5rem; font-size: 0.8rem; white-space: nowrap; }
            th { font-size: 0.7rem; }
            .action-btns { gap: 4px; }
            .btn { padding: 0.4rem 0.75rem; font-size: 0.8rem; }
            .kasa-grid { grid-template-columns: 1fr 1fr !important; gap: 0.75rem !important; }
            .kasa-item { padding: 1rem !important; }
            .kasa-item .amount { font-size: 1.25rem !important; }
            .masalar-grid { grid-template-columns: 1fr 1fr !important; gap: 0.75rem !important; }
            .header-actions { flex-direction: column; gap: 8px !important; align-items: stretch !important; }
            .header-actions h3 { font-size: 1.1rem !important; }
        }
        @media (max-width: 420px) {
            .stats-grid { grid-template-columns: 1fr; }
            .kasa-grid { grid-template-columns: 1fr !important; }
            .masalar-grid { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-qrcode"></i>
            <h2>QR Menü</h2>
            <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.remove('active'); document.getElementById('sidebarOverlay').classList.remove('active');" style="display: none; margin-left: auto; background: none; border: none; font-size: 1.25rem; color: var(--white); cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <nav class="nav-menu">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i>
                <span>Kategoriler</span>
            </a>
            <a href="{{ route('main-categories.index') }}" class="nav-item {{ request()->routeIs('main-categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-folder-tree"></i>
                <span>Ana Gruplar</span>
            </a>
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-burger"></i>
                <span>Ürünler</span>
            </a>
            <a href="{{ route('admin.masalar') }}" class="nav-item {{ request()->routeIs('admin.masalar') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i>
                <span>Masalar & Kasa</span>
            </a>
            @if(session('admin_role') == '0')
            <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i>
                <span>Ayarlar</span>
            </a>
            <a href="{{ route('admin.admins') }}" class="nav-item {{ request()->routeIs('admin.admins') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Yöneticiler</span>
            </a>
            @endif
            <a href="{{ route('home') }}" class="nav-item" style="margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Ana Sayfaya Dön</span>
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" id="logout-form" style="display: none;">
                @csrf
            </form>
            <a href="#" class="nav-item" style="color: #ef4444;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Çıkış Yap</span>
            </a>
            
            <div style="padding: 1rem 1.5rem 0; text-align: center; font-size: 0.65rem; color: #475569; letter-spacing: 0.5px; opacity: 0.8;">
                <i class="fa-solid fa-code" style="font-size: 0.55rem; margin-right: 2px;"></i> Mikale Yazılım
            </div>
        </nav>
    </aside>

    <!-- Sidebar Overlay (Mobil) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="document.querySelector('.sidebar').classList.remove('active'); this.classList.remove('active');"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.add('active'); document.getElementById('sidebarOverlay').classList.add('active');" style="display: none; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-main);">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1>@yield('header_title', 'Genel Bakış')</h1>
            </div>
            <div class="user-profile">
                <span style="font-weight: 500;">Admin</span>
                <div class="user-avatar">A</div>
            </div>
        </header>

        <!-- Patron Talebi: Tüm Yönetim Paneli Sayfalarında Görünür Garson Çağrısı Merkezi -->
        @php
            $globalCagrilar = \App\Models\QrCodeCagri::where('Status', 0)->orderBy('Cagri_zamani', 'desc')->get();
        @endphp
        <div id="global-waiter-banner" style="display: {{ $globalCagrilar->count() > 0 ? 'block' : 'none' }}; margin-bottom: 1.5rem; width: 100%; box-sizing: border-box; max-width: 100%;">
            <div class="waiter-banner-box" style="background: #ffffff; border: 1px solid #e2e8f0; border-left: 6px solid #f59e0b; border-radius: 14px; padding: clamp(0.85rem, 2.5vw, 1.25rem) clamp(0.85rem, 3vw, 1.5rem); box-shadow: 0 14px 35px -5px rgba(245, 158, 11, 0.18), 0 4px 10px -3px rgba(0, 0, 0, 0.05); transition: all 0.3s ease; width: 100%; box-sizing: border-box;">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px dashed #cbd5e1; padding-bottom: 0.85rem; margin-bottom: 1rem; flex-wrap: wrap; gap: 10px; width: 100%; box-sizing: border-box;">
                    <div style="display: flex; align-items: center; gap: clamp(8px, 2.5vw, 14px); flex: 1 1 min-content;">
                        <div style="width: clamp(38px, 9vw, 44px); height: clamp(38px, 9vw, 44px); min-width: 38px; border-radius: 12px; background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: clamp(1.15rem, 3.5vw, 1.4rem); box-shadow: 0 4px 12px rgba(217, 119, 6, 0.22); border: 1px solid rgba(217, 119, 6, 0.2); flex-shrink: 0;">
                            <i class="fa-solid fa-bell-concierge" style="animation: bellShake 2s infinite cubic-bezier(.36,.07,.19,.97);"></i>
                        </div>
                        <div style="min-width: 0;">
                            <h3 style="margin: 0; font-size: clamp(0.95rem, 3vw, 1.15rem); font-weight: 800; color: #0f172a; letter-spacing: 0.2px; line-height: 1.25;">Canlı Garson Çağrı Departmanı</h3>
                            <span style="font-size: clamp(0.75rem, 2vw, 0.84rem); color: #64748b; font-weight: 500; display: block; margin-top: 2px; line-height: 1.3;">Masa sipariş veya destek için personelinizin ilgisini bekliyor</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span id="global-waiter-count" style="background: #fffdf5; color: #b45309; border: 1.5px solid #fcd34d; font-weight: 800; padding: 6px 14px; border-radius: 25px; font-size: clamp(0.78rem, 2.5vw, 0.88rem); display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.1); white-space: nowrap;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; display: inline-block; animation: ping 1.5s infinite; flex-shrink: 0;"></span>
                            Bekleyen Çağrı: {{ $globalCagrilar->count() }}
                        </span>
                    </div>
                </div>
                
                <div id="global-waiter-list" style="display: flex; flex-wrap: wrap; gap: 1rem; width: 100%; box-sizing: border-box;">
                    @foreach($globalCagrilar as $cg)
                        <div class="waiter-card-item" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1.15rem; display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 6px rgba(0,0,0,0.02); width: 310px; max-width: 100%; box-sizing: border-box; flex: 0 0 auto;">
                            <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                                <div style="width: 40px; height: 40px; min-width: 40px; border-radius: 50%; background: #ffffff; color: #334155; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 700; border: 1px solid #cbd5e1; box-shadow: 0 2px 5px rgba(0,0,0,0.04); flex-shrink: 0;">
                                    <i class="fa-solid fa-chair" style="color: #64748b;"></i>
                                </div>
                                <div style="min-width: 0;">
                                    <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $cg->Masaismi ?: 'Masa ' . $cg->Masa_id }}
                                    </div>
                                    <div style="font-size: 0.78rem; color: #e11d48; font-weight: 700; display: flex; align-items: center; gap: 4px; margin-top: 2px; white-space: nowrap;">
                                        <i class="fa-solid fa-bolt" style="font-size: 0.75rem; animation: pulse 1s infinite;"></i> Garson Bekliyor
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.masalar.completeCall', $cg->id) }}" method="POST" style="margin: 0; flex-shrink: 0;">
                                @csrf
                                <button type="submit" class="btn-complete-call" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 8px 15px; border-radius: 10px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3); transition: all 0.2s; white-space: nowrap;">
                                    <span>İlgilenildi</span>
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <style>
            @keyframes bellShake {
                0%, 100% { transform: rotate(0); }
                10%, 30%, 50%, 70%, 90% { transform: rotate(-14deg); }
                20%, 40%, 60%, 80% { transform: rotate(14deg); }
            }
            @keyframes ping {
                0% { transform: scale(1); opacity: 1; }
                75%, 100% { transform: scale(1.6); opacity: 0; }
            }
            @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
            .waiter-card-item:hover { transform: translateY(-3px); box-shadow: 0 10px 22px rgba(245, 158, 11, 0.12) !important; border-color: #fbd38d !important; background: #fff !important; }
            .btn-complete-call:hover { transform: scale(1.05); box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45) !important; background: linear-gradient(135deg, #059669, #047857) !important; }
        </style>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
        
    </main>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form onsubmit interceptor
            const forms = document.querySelectorAll('form[onsubmit*="confirm"]');
            forms.forEach(form => {
                const onsubmitStr = form.getAttribute('onsubmit');
                const match = onsubmitStr.match(/confirm\(['"](.+?)['"]\)/);
                const message = match ? match[1] : 'Bu işlemi yapmak istediğinize emin misiniz?';
                
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Evet, Sil!',
                        cancelButtonText: 'İptal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Button onclick interceptor
            const buttons = document.querySelectorAll('button[onclick*="confirm"], a[onclick*="confirm"]');
            buttons.forEach(btn => {
                const onclickStr = btn.getAttribute('onclick');
                const match = onclickStr.match(/confirm\(['"](.+?)['"]\)/);
                const message = match ? match[1] : 'Bu işlemi yapmak istediğinize emin misiniz?';
                
                btn.removeAttribute('onclick');
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Evet, Sil!',
                        cancelButtonText: 'İptal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (btn.type === 'submit' && btn.form) {
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = btn.name;
                                hiddenInput.value = btn.value;
                                btn.form.appendChild(hiddenInput);
                                btn.form.submit();
                            } else if (btn.tagName === 'A' && btn.href) {
                                window.location.href = btn.href;
                            }
                        }
                    });
                });
            });

            // 10 saniyede bir Canlı Garson Çağrılarını tüm admin sayfalarında kontrol et
            setInterval(function() {
                fetch("{{ route('admin.masalar.liveCagrilarJson') }}", {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                .then(response => response.json())
                .then(data => {
                    const banner = document.getElementById('global-waiter-banner');
                    const list = document.getElementById('global-waiter-list');
                    const countBadge = document.getElementById('global-waiter-count');
                    if (!banner || !list || !countBadge) return;

                    if (data && data.length > 0) {
                        banner.style.display = 'block';
                        countBadge.innerHTML = `<span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; display: inline-block; animation: ping 1.5s infinite; flex-shrink: 0;"></span> Bekleyen Çağrı: ${data.length}`;
                        
                        let html = '';
                        let token = "{{ csrf_token() }}";
                        data.forEach(cg => {
                            let masaAd = cg.Masaismi ? cg.Masaismi : ('Masa ' + cg.Masa_id);
                            let actionUrl = "{{ url('admin/masalar/cagri-tamamla') }}/" + cg.id;
                            html += `
                            <div class="waiter-card-item" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1.15rem; display: flex; align-items: center; justify-content: space-between; gap: 0.85rem; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 6px rgba(0,0,0,0.02); width: 310px; max-width: 100%; box-sizing: border-box; flex: 0 0 auto;">
                                <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                                    <div style="width: 40px; height: 40px; min-width: 40px; border-radius: 50%; background: #ffffff; color: #334155; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 700; border: 1px solid #cbd5e1; box-shadow: 0 2px 5px rgba(0,0,0,0.04); flex-shrink: 0;">
                                        <i class="fa-solid fa-chair" style="color: #64748b;"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            ${masaAd}
                                        </div>
                                        <div style="font-size: 0.78rem; color: #e11d48; font-weight: 700; display: flex; align-items: center; gap: 4px; margin-top: 2px; white-space: nowrap;">
                                            <i class="fa-solid fa-bolt" style="font-size: 0.75rem; animation: pulse 1s infinite;"></i> Garson Bekliyor
                                        </div>
                                    </div>
                                </div>
                                <form action="${actionUrl}" method="POST" style="margin: 0; flex-shrink: 0;">
                                    <input type="hidden" name="_token" value="${token}">
                                    <button type="submit" class="btn-complete-call" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 8px 15px; border-radius: 10px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3); transition: all 0.2s; white-space: nowrap;">
                                        <span>İlgilenildi</span>
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            </div>`;
                        });
                        list.innerHTML = html;
                    } else {
                        banner.style.display = 'none';
                        list.innerHTML = '';
                    }
                })
                .catch(err => console.error("Çağrı denetim hatası:", err));
            }, 10000);
        });
    </script>
</body>
</html>
