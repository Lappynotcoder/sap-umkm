<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
        .auth-card {
            background-color: #F1F5F9;
            width: 100%;
            max-width: 800px;
            padding: 4rem 2rem;
            margin: 2rem;
            border-radius: 0;
            box-shadow: none;
            text-align: center;
        }
        .auth-title {
            font-size: 4rem;
            font-weight: 400;
            color: #1A374D;
            margin-bottom: 2.5rem;
        }
        .auth-form {
            max-width: 400px;
            margin: 0 auto;
        }
        .form-control-custom {
            background: transparent;
            border: 1.5px solid #1A374D;
            border-radius: 50rem;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            color: #1A374D;
            margin-bottom: 1.5rem;
            font-style: italic;
            width: 100%;
            box-sizing: border-box;
        }
        .form-control-custom::placeholder {
            color: #A0B0C0;
            font-style: italic;
        }
        .form-control-custom:focus {
            outline: none;
            box-shadow: none;
            border-color: #F2AB39;
        }
        .btn-auth {
            background-color: #F2AB39;
            color: #fff;
            border: none;
            border-radius: 50rem;
            padding: 0.8rem 4rem;
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 1rem;
            transition: all 0.3s;
            font-style: normal;
            font-family: 'Inter', sans-serif;
        }
        .btn-auth:hover {
            background-color: #d9962a;
            color: #fff;
        }
        .auth-link {
            display: block;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #1A374D;
            text-decoration: none;
            font-style: normal;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
        }
        .auth-link span {
            color: #F2AB39;
        }
        .auth-link:hover span {
            color: #d9962a;
        }
        .text-danger-small {
            color: #EF4444;
            font-size: 0.8rem;
            text-align: left;
            margin-top: -1.2rem;
            margin-bottom: 1rem;
            display: block;
            margin-left: 1.5rem;
            font-style: normal;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        {{ $slot }}
    </div>
</body>
</html>
