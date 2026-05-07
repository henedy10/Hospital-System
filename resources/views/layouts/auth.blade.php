<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Hospital System</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0056b3;
            --secondary: #00a859;
            --bg-slate: #f8fafc;
            --text-dark: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg-slate);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 10;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 32px;
            padding: 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-logo {
            width: 56px;
            height: 56px;
            background: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: white;
            font-size: 28px;
            font-weight: 700;
            box-shadow: 0 10px 20px -5px rgba(0, 86, 179, 0.3);
        }

        .auth-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .auth-header p {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 14px 20px;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1rem;
            color: var(--text-dark);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 86, 179, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
        }

        .btn-primary:hover {
            background: #004494;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 86, 179, 0.4);
        }

        .auth-footer {
            text-align: center;
            margin-top: 32px;
            font-size: 0.95rem;
            color: #64748b;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            margin-left: 4px;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 1;
            opacity: 0.4;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: rgba(0, 86, 179, 0.15);
            top: -100px;
            right: -100px;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: rgba(0, 168, 89, 0.1);
            bottom: -50px;
            left: -50px;
        }
    </style>
</head>

<body>
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>

    <div class="auth-container">
        <div class="glass-card">
            @yield('content')
        </div>
    </div>
</body>

</html>
