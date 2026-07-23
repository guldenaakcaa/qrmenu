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
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; width: 260px; }
            .sidebar.active { transform: translateX(0); box-shadow: 4px 0 15px rgba(0,0,0,0.2); }
            .main-content { margin-left: 0; padding: 1rem; gap: 1rem; }
            .mobile-toggle { display: block !important; }
            .stats-grid { grid-template-columns: 1fr; }
            .top-header { padding: 1rem; flex-wrap: wrap; }
            .top-header h1 { font-size: 1.1rem; }
            .card { padding: 1rem; }
            .table-container { padding: 0.75rem; }
            th, td { padding: 0.75rem 0.5rem; font-size: 0.8rem; white-space: nowrap; }
            th { font-size: 0.75rem; }
            .action-btns { gap: 4px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-qrcode"></i>
            <h2>QR Menü</h2>
            <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.remove('active')" style="display: none; margin-left: auto; background: none; border: none; font-size: 1.25rem; color: var(--white); cursor: pointer;">
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
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-burger"></i>
                <span>Ürünler</span>
            </a>
            <a href="{{ route('admin.masalar') }}" class="nav-item {{ request()->routeIs('admin.masalar') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i>
                <span>Masalar & Kasa</span>
            </a>
            <a href="{{ route('admin.qr') }}" class="nav-item {{ request()->routeIs('admin.qr') ? 'active' : '' }}">
                <i class="fa-solid fa-expand"></i>
                <span>QR Kodlar</span>
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

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.add('active')" style="display: none; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-main);">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1>@yield('header_title', 'Genel Bakış')</h1>
            </div>
            <div class="user-profile">
                <span style="font-weight: 500;">Admin</span>
                <div class="user-avatar">A</div>
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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
        });
    </script>
</body>
</html>
