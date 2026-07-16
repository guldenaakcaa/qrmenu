<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings && $settings->baslik ? $settings->baslik : 'Center Cafe' }} | Dijital Menü</title>
    @if($settings && $settings->favicon)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color-1: #f8f9fa;
            --bg-color-2: #e9ecef;
            --text-dark: #212529;
            --text-gray: #6c757d;
            --accent-indigo: #667eea;
            --accent-purple: #764ba2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            @if($settings && $settings->karsilama_gorsel)
                background: url('{{ asset("storage/" . $settings->karsilama_gorsel) }}') no-repeat center center fixed;
                background-size: cover;
            @else
                background: linear-gradient(135deg, var(--bg-color-1) 0%, var(--bg-color-2) 100%);
            @endif
            position: relative;
            overflow: hidden;
            color: var(--text-dark);
        }

        /* Decorative Background Blurs */
        .bg-blur {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out alternate;
        }

        .bg-blur-1 {
            width: 400px;
            height: 400px;
            background: rgba(102, 126, 234, 0.4);
            top: -100px;
            left: -100px;
        }

        .bg-blur-2 {
            width: 500px;
            height: 500px;
            background: rgba(118, 75, 162, 0.3);
            bottom: -150px;
            right: -100px;
            animation-delay: -5s;
        }
        
        .bg-blur-3 {
            width: 300px;
            height: 300px;
            background: rgba(173, 216, 230, 0.5); /* Pale blue */
            top: 40%;
            left: 60%;
            animation-duration: 15s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 30px) scale(1.1); }
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 40px;
            padding: 4rem 3.5rem;
            width: 95%;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1), 0 0 20px rgba(255,255,255,0.5) inset;
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.15), 0 0 20px rgba(255,255,255,0.6) inset;
        }

        /* 3D Logo Badge */
        .logo-badge {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--accent-indigo) 0%, var(--accent-purple) 100%);
            border-radius: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 2rem;
            box-shadow: 0 15px 30px rgba(118, 75, 162, 0.4), inset 0 2px 0 rgba(255,255,255,0.4), inset 0 -4px 0 rgba(0,0,0,0.2);
            transform: rotate(-5deg);
            transition: transform 0.4s ease;
        }
        
        .glass-card:hover .logo-badge {
            transform: rotate(0deg) scale(1.05);
        }

        .logo-badge span {
            color: #fff;
            font-size: 3.2rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* Typography */
        h1 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-indigo);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 2rem;
            display: block;
        }

        .description {
            font-size: 1.2rem;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 3rem;
            font-weight: 300;
        }

        /* Button */
        .btn-menu {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 1.4rem;
            background: #0f172a; /* Dark navy/black */
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-menu::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-menu:hover {
            background: #1e293b;
            box-shadow: 0 15px 25px rgba(15, 23, 42, 0.3);
            transform: translateY(-2px);
        }
        
        .btn-menu:hover::after {
            left: 100%;
        }

        .btn-menu svg {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .btn-menu:hover svg {
            transform: translateX(5px);
        }

        /* Bottom Link */
        .admin-link {
            position: absolute;
            bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 400;
            opacity: 0.7;
            transition: opacity 0.3s ease, color 0.3s ease;
            z-index: 1;
        }

        .admin-link:hover {
            opacity: 1;
            color: var(--text-dark);
        }

        .admin-link svg {
            width: 12px;
            height: 12px;
        }
    </style>
</head>
<body>

    <!-- Decorative Blurs -->
    <div class="bg-blur bg-blur-1"></div>
    <div class="bg-blur bg-blur-2"></div>
    <div class="bg-blur bg-blur-3"></div>

    <!-- Main Card -->
    <div class="glass-card">
        @if($settings && $settings->logo)
            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" style="max-height: 100px; margin-bottom: 1.5rem; border-radius: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
        @else
            <div class="logo-badge">
                <span>{{ substr($settings->baslik ?? 'C', 0, 1) }}</span>
            </div>
        @endif
        
        <h1>{{ $settings && $settings->baslik ? $settings->baslik : 'Center Cafe' }}</h1>
        <span class="subtitle">{{ $settings && $settings->slogan ? $settings->slogan : 'DİJİTAL MENÜ' }}</span>
        
        <p class="description">
            Özenle hazırladığımız lezzetleri keşfetmek için menümüzü inceleyin.
        </p>

        <a href="{{ route('menu') }}" class="btn-menu">
            Menüyü İncele
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>

    <!-- Admin Link -->
    <a href="{{ route('admin.dashboard') }}" class="admin-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
        Yönetim Paneli
    </a>

</body>
</html>
