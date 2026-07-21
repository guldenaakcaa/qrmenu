<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Center Cafe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg-color-1: #f8f9fa;
            --bg-color-2: #e9ecef;
            --text-dark: #212529;
            --text-gray: #6c757d;
            --accent-indigo: #667eea;
            --accent-purple: #764ba2;
            --accent-blue-bg: #eff6ff;
            --accent-blue-text: #1e40af;
            --input-border: #cbd5e1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        html, body {
            min-height: 100vh;
            overflow-x: hidden;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, var(--bg-color-1) 0%, var(--bg-color-2) 100%);
            position: relative;
            overflow-y: auto;
            color: var(--text-dark);
            padding: 1rem;
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

        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 500px;
            margin: 2rem;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1), 0 0 20px rgba(255,255,255,0.5) inset;
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Left Side */
        .login-left {
            flex: 1.2;
            background: rgba(255, 255, 255, 0.4);
            border-right: 1px solid rgba(255, 255, 255, 0.6);
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        /* 3D Logo Badge */
        .logo-badge {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-indigo) 0%, var(--accent-purple) 100%);
            border-radius: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 15px 30px rgba(118, 75, 162, 0.4), inset 0 2px 0 rgba(255,255,255,0.4), inset 0 -4px 0 rgba(0,0,0,0.2);
            transform: rotate(-5deg);
        }

        .logo-badge span {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #334155;
            letter-spacing: 1px;
            margin-bottom: 2rem;
        }

        .warning-box {
            background-color: var(--accent-blue-bg);
            border-radius: 12px;
            padding: 1.5rem;
            width: 100%;
            margin-bottom: 2.5rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .warning-title {
            color: var(--accent-blue-text);
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 0.5rem;
        }

        .warning-text {
            color: var(--accent-blue-text);
            font-size: 0.9rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .info-text {
            color: var(--text-gray);
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Right Side */
        .login-right {
            flex: 1;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.95rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--text-dark);
            background-color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-indigo);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, var(--accent-indigo) 0%, var(--accent-purple) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(118, 75, 162, 0.4);
        }

        .error-message {
            color: #e11d48;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }
        
        .alert-error {
            background: #fff0f0;
            color: #e11d48;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: center;
            border: 1px solid #ffe4e6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .bg-blur {
                display: none;
            }
            body {
                padding: 1rem;
            }
            .login-wrapper {
                flex-direction: column;
                margin: 0 auto;
                width: 100%;
                max-width: 400px;
                border-radius: 20px;
            }
            .login-left {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.6);
                padding: 2rem 1.5rem;
            }
            .logo-badge {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }
            .logo-badge span {
                font-size: 1.8rem;
            }
            .login-title {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }
            .warning-box {
                margin-bottom: 1.5rem;
                padding: 1rem;
            }
            .login-right {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Decorative Blurs -->
    <div class="bg-blur bg-blur-1"></div>
    <div class="bg-blur bg-blur-2"></div>
    <div class="bg-blur bg-blur-3"></div>

    <div class="login-wrapper">
        <!-- Left Side: Info & Warning -->
        <div class="login-left">
            <div class="logo-badge">
                <span>C</span>
            </div>
            <h1 class="login-title">YÖNETİM PANELİ</h1>
            
            <div class="warning-box">
                <div class="warning-title">Bu sayfa sadece adminler içindir!</div>
                <div class="warning-text">Yetkisiz kişilerin bu panele girişi yasaktır.</div>
            </div>

            <div class="info-text">Lütfen yönetici bilgilerinizi girin</div>
            
            <div style="margin-top: 2rem;">
                <a href="{{ route('home') }}" style="color: var(--text-gray); text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 5px; transition: color 0.3s ease;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-right">
            @if($errors->has('email') && !old('email'))
                <div class="alert-error">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <!-- Form Başlangıcı -->
            <form method="POST" action="{{ route('admin.authenticate') }}" class="w-full flex flex-col gap-4 mt-6">
                @csrf <!-- Laravel'in zorunlu güvenlik önlemi -->

                <!-- E-posta Kutucuğu -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">E-posta Adresi</label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        class="w-full bg-white/50 border border-gray-200 rounded-xl px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                        placeholder="admin@centercafe.com"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Şifre Kutucuğu -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Şifre</label>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        class="w-full bg-white/50 border border-gray-200 rounded-xl px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Giriş Yap Butonu -->
                <button type="submit" class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                    Giriş Yap
                </button>
            </form>
        </div>
    </div>

    <!-- Subtle Branding -->
    <div style="position: absolute; bottom: 15px; width: 100%; text-align: center; color: rgba(33, 37, 41, 0.4); font-size: 0.75rem; font-weight: 500; letter-spacing: 0.5px;">
        Sistem Altyapısı: <span style="font-weight: 700; opacity: 0.8;">Mikale Yazılım</span>
    </div>

</body>
</html>
