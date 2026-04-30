@extends('layouts.app')
@section('title', 'Beranda')

@push('styles')
<style>
    .hero-landing {
        min-height: 80vh;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f0 100%);
    }
    .hero-card {
        background: #fff;
        border-radius: 20px;
        padding: 3.5rem 3rem;
        text-align: center;
        max-width: 520px;
        box-shadow: 0 20px 60px rgba(0,0,0,.08);
    }
    .hero-card h1 {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 2.4rem;
        color: #111827;
        line-height: 1.2;
        margin-bottom: 0.75rem;
    }
    .hero-card .subtitle {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }
    .hero-card .subtitle strong {
        color: #1a6b3a;
    }
    .btn-hero-login {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: #fff; border: none;
        padding: 0.7rem 2.5rem; border-radius: 50px;
        font-weight: 600; font-size: 0.95rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(245,158,11,0.3);
    }
    .btn-hero-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245,158,11,0.4);
        color: #fff;
    }
    .btn-hero-register {
        background: linear-gradient(135deg, #f97316, #ef4444);
        color: #fff; border: none;
        padding: 0.7rem 2.5rem; border-radius: 50px;
        font-weight: 600; font-size: 0.95rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(249,115,22,0.3);
    }
    .btn-hero-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(249,115,22,0.4);
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="hero-landing">
    <div class="hero-card">
        <h1>ANALISIS<br>BISNIS UMKM</h1>
        <p class="subtitle">
            Kelola & analisis keuangan <strong>bisnis UMKM</strong> Anda dengan mudah dan akurat.
        </p>
        <div class="d-flex justify-content-center gap-3">
            @auth
                <a href="{{ route('riwayat') }}" class="btn btn-hero-login">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-hero-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-hero-register">
                    <i class="bi bi-person-plus me-2"></i>Register
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
