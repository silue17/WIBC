<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Admin WIBC</title>
    <link rel="icon" type="image/jpeg" href="/photos/logo.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #050613 0%, #0a1227 50%, #062e1c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(20px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
        }

        .login-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            justify-content: center;
        }
        .login-logo img {
            width: 44px; height: 44px;
            border-radius: 10px; object-fit: cover;
        }
        .login-logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800; font-size: 1.4rem;
            color: #fff; letter-spacing: -0.5px;
        }
        .login-logo-text span { color: #4ade80; }

        .login-title {
            text-align: center;
            margin-bottom: 8px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.3rem; font-weight: 700;
            color: #fff;
        }
        .login-subtitle {
            text-align: center;
            color: rgba(255,255,255,0.4);
            font-size: 0.83rem;
            margin-bottom: 36px;
        }

        .field-group { margin-bottom: 18px; }
        .field-label {
            display: block;
            font-size: 0.78rem; font-weight: 600;
            color: rgba(255,255,255,0.55);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 13px 16px;
            color: #fff;
            font-size: 0.92rem;
            font-family: inherit;
            transition: border-color 0.2s;
            outline: none;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.25); }
        .field-input:focus { border-color: #4ade80; }

        .field-input-wrap {
            position: relative;
        }
        .field-input-wrap .field-input { padding-right: 44px; }
        .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: rgba(255,255,255,0.35);
            cursor: pointer; font-size: 0.9rem;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: rgba(255,255,255,0.7); }

        .error-msg {
            background: rgba(243,66,68,0.12);
            border: 1px solid rgba(243,66,68,0.25);
            color: #f87171;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.82rem;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }

        .btn-login {
            width: 100%;
            background: #047847;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 8px;
        }
        .btn-login:hover { background: #05a35f; }
        .btn-login:active { transform: scale(0.98); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: rgba(255,255,255,0.35);
            font-size: 0.8rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #4ade80; }

        .dots {
            position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0;
        }
        .dot {
            position: absolute; border-radius: 50%;
            background: rgba(4,120,71,0.06);
        }
        .login-card { position: relative; z-index: 1; }
    </style>
</head>
<body>
    <div class="dots">
        <div class="dot" style="width:400px;height:400px;top:-100px;right:-100px;"></div>
        <div class="dot" style="width:300px;height:300px;bottom:-80px;left:-80px;background:rgba(5,100,183,0.05);"></div>
    </div>

    <div class="login-card">
        <div class="login-logo">
            <img src="/photos/logo.jpeg" alt="WIBC">
            <span class="login-logo-text">WI<span>BC</span></span>
        </div>
        <h1 class="login-title">Espace Administration</h1>
        <p class="login-subtitle">Connectez-vous pour gérer le site</p>

        @if($errors->any())
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf
            <div class="field-group">
                <label class="field-label"><i class="fas fa-envelope"></i> Adresse email</label>
                <input type="email" name="email" class="field-input"
                    placeholder="admin@wibc.ci"
                    value="{{ old('email') }}" required autofocus>
            </div>
            <div class="field-group">
                <label class="field-label"><i class="fas fa-lock"></i> Mot de passe</label>
                <div class="field-input-wrap">
                    <input type="password" name="password" id="passwordInput"
                        class="field-input" placeholder="••••••••" required>
                    <button type="button" class="toggle-pw" onclick="togglePw()">
                        <i class="fas fa-eye" id="pwIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <a href="/" class="back-link"><i class="fas fa-arrow-left"></i> Retour au site</a>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>
