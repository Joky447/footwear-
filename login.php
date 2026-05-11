<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footwear Ordering System</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cream:   #F5F0E8;
            --dark:    #1A1410;
            --brown:   #6B3F1F;
            --gold:    #C8963E;
            --tan:     #D4A96A;
            --light:   #FAF7F2;
            --muted:   #9C8B78;
        }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            flex: 1;
            position: relative;
            background: var(--brown);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 60px;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 20%, rgba(200,150,62,0.25) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 80%, rgba(26,20,16,0.6) 0%, transparent 60%);
        }

        /* decorative circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(200,150,62,0.2);
        }
        .circle-1 { width: 500px; height: 500px; top: -100px; left: -150px; }
        .circle-2 { width: 300px; height: 300px; top: 80px;  left: -50px;  border-color: rgba(200,150,62,0.12); }
        .circle-3 { width: 200px; height: 200px; bottom: 120px; right: -60px; }

        /* shoe silhouette decorative */
        .shoe-deco {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -55%);
            font-size: 220px;
            opacity: 0.06;
            filter: blur(2px);
            user-select: none;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(-50%, -55%) rotate(-5deg); }
            50%       { transform: translate(-50%, -58%) rotate(-3deg); }
        }

        .left-content { position: relative; z-index: 2; }

        .brand-tag {
            display: inline-block;
            background: rgba(200,150,62,0.2);
            border: 1px solid rgba(200,150,62,0.4);
            color: var(--tan);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 2px;
            margin-bottom: 28px;
        }

        .left-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 4vw, 56px);
            font-weight: 900;
            color: var(--cream);
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .left-title span { color: var(--gold); }

        .left-desc {
            color: rgba(245,240,232,0.55);
            font-size: 14px;
            font-weight: 300;
            line-height: 1.7;
            max-width: 320px;
            margin-bottom: 48px;
        }

        .stats {
            display: flex;
            gap: 40px;
        }

        .stat-item { }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--gold);
        }
        .stat-label {
            font-size: 11px;
            color: rgba(245,240,232,0.45);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 480px;
            background: var(--light);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 64px 56px;
            position: relative;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brown), var(--gold), var(--tan));
        }

        .login-header { margin-bottom: 40px; }

        .login-eyebrow {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 10px;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.2;
        }

        .login-subtitle {
            margin-top: 8px;
            font-size: 13px;
            color: var(--muted);
        }

        /* form */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--brown);
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #E8DFD3;
            border-radius: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--dark);
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(200,150,62,0.12);
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #C5B9AD;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-pass {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            font-size: 16px;
            padding: 0;
            line-height: 1;
        }

        .toggle-pass:hover { color: var(--brown); }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 28px;
        }

        .form-footer a {
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .form-footer a:hover { color: var(--brown); }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--dark);
            color: var(--cream);
            border: none;
            border-radius: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.25s, transform 0.1s;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 40%, rgba(200,150,62,0.15));
        }

        .btn-login:hover { background: var(--brown); }
        .btn-login:active { transform: scale(0.99); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0;
            color: #D5CAC0;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E8DFD3;
        }

        .login-footer {
            margin-top: 36px;
            padding-top: 24px;
            border-top: 1px solid #EDE6DC;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
        }

        /* animations */
        .right-panel > * {
            animation: fadeUp 0.5s ease both;
        }

        .login-header       { animation-delay: 0.1s; }
        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.25s; }
        .form-footer        { animation-delay: 0.3s; }
        .btn-login          { animation-delay: 0.35s; }
        .login-footer       { animation-delay: 0.4s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 48px 32px; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>
    <div class="shoe-deco">👟</div>

    <div class="left-content">
        <div class="brand-tag">Est. 2024</div>
        <h1 class="left-title">Footwear<br><span>Ordering</span><br>System</h1>
        <p class="left-desc">
            A complete inventory and order management platform built for modern footwear businesses.
        </p>
        <div class="stats">
            <div class="stat-item">
                <div class="stat-num">750+</div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">11</div>
                <div class="stat-label">Tables</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">BCNF</div>
                <div class="stat-label">Normalized</div>
            </div>
        </div>
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
    <div class="login-header">
        <div class="login-eyebrow">Welcome back</div>
        <h2 class="login-title">Sign in to<br>your account</h2>
        <p class="login-subtitle">Enter your credentials to access the system</p>
    </div>

    <form action="checkuser.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   placeholder="Enter your username" required autofocus />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password"
                       placeholder="Enter your password" required />
                <button type="button" class="toggle-pass" onclick="togglePassword()">👁</button>
            </div>
        </div>

        <div class="form-footer">
            <a href="#">Forgot password?</a>
        </div>

        <button type="submit" class="btn-login">Sign In →</button>
    </form>

    <div class="login-footer">
        Footwear Ordering System &nbsp;·&nbsp; Admin Portal &nbsp;·&nbsp; v1.0
    </div>
</div>

<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const btn = document.querySelector('.toggle-pass');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        btn.textContent = '🙈';
    } else {
        pwd.type = 'password';
        btn.textContent = '👁';
    }
}
</script>

</body>
</html>
