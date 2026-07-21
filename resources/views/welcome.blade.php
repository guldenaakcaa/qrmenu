<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

        html, body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #1a1a2e;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: -1;
            @if($settings && $settings->karsilama_gorsel)
                background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.25)), url('{{ asset("storage/" . $settings->karsilama_gorsel) }}') no-repeat center center;
                background-size: cover;
            @else
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            @endif
        }
        
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
            background: transparent;
            position: relative;
            overflow: hidden; /* Prevent all scrolling */
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

        /* Main Content Container */
        .glass-card {
            background: transparent;
            border: none;
            padding: 2rem 1.5rem;
            width: 95%;
            max-width: 900px;
            min-height: 75vh;
            display: flex;
            flex-direction: column;
            text-align: center;
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: transform 0.4s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .glass-card {
                padding: 2.5rem 1.5rem;
                border-radius: 30px;
                width: 90%;
            }
            h1 {
                font-size: 2.2rem !important;
            }
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
            color: #ffffff;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
            text-shadow: 0 10px 25px rgba(0,0,0,0.8), 0 5px 15px rgba(0,0,0,0.6);
        }

        .subtitle {
            font-size: 1rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: auto;
            display: block;
            text-shadow: 0 8px 20px rgba(0,0,0,0.8);
        }

        .description {
            font-size: 1.25rem;
            color: #ffffff;
            line-height: 1.6;
            margin-bottom: 1rem;
            font-weight: 400;
            text-shadow: 0 10px 20px rgba(0,0,0,0.8), 0 4px 8px rgba(0,0,0,0.6);
        }

        /* Button */
        .btn-menu {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: auto;
            margin: 0 auto;
            padding: 1rem 3rem;
            background: transparent;
            color: #ffffff;
            border: none;
            text-decoration: none;
            border-radius: 20px;
            font-size: 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
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
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-menu:hover {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.3);
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
            position: relative;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 6px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 400;
            transition: color 0.3s ease, text-shadow 0.3s ease;
            z-index: 1;
        }

        .admin-link:hover {
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5), 0 0 20px rgba(255, 255, 255, 0.3);
        }

        .admin-link svg {
            width: 12px;
            height: 12px;
        }

        .powered-by {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 400;
            letter-spacing: 0.5px;
            z-index: 10;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            pointer-events: none;
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

    <!-- Powered By -->
    <div class="powered-by">Powered by Mıkale Yazılım</div>

</body>
</html>
