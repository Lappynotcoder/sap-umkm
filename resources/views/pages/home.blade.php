<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SAP - UMKM') }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #1A374D;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier Prime', 'Courier New', Courier, monospace;
            margin: 0;
        }
        .landing-card {
            background-color: #F1F5F9;
            width: 100%;
            max-width: 900px;
            padding: 5rem 2rem;
            margin: 2rem;
            border-radius: 0;
            box-shadow: none;
            text-align: center;
        }
        .landing-title {
            font-size: 4rem;
            font-weight: 400;
            color: #1A374D;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }
        .landing-subtitle {
            font-size: 1.1rem;
            color: #1A374D;
            margin-bottom: 3.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .landing-actions {
            display: flex;
            gap: 2rem;
            justify-content: center;
        }
        .btn-landing {
            background-color: #F2AB39;
            color: #fff;
            border: none;
            border-radius: 50rem;
            padding: 0.8rem 4rem;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            display: inline-block;
        }
        .btn-landing:hover {
            background-color: #d9962a;
            color: #fff;
            text-decoration: none;
        }
        
        @media (max-width: 576px) {
            .landing-title {
                font-size: 2.5rem;
            }
            .landing-actions {
                flex-direction: column;
                gap: 1rem;
            }
            .btn-landing {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="landing-card">
        <div class="landing-title">
            SISTEM SAP<br>UMKM
        </div>
        <div class="landing-subtitle">
            Naikin level bisnis kamu mulai sekarang!
        </div>
        
        <div class="landing-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-landing">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-landing">Login</a>
                
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-landing">Register</a>
                @endif
            @endauth
        </div>
    </div>
</body>
</html>
