@extends('layouts.dashboard')
@section('title', 'Pengaturan Akun')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-gear me-2" style="color:#1a6b3a"></i>Pengaturan Akun
        </h4>
        <span class="text-muted small">Kelola informasi profil dan kata sandi Anda</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-metric p-4 h-100">
            <h5 class="fw-bold mb-1">Informasi Profil</h5>
            <p class="text-muted small mb-4">Perbarui informasi profil dan nama UMKM Anda.</p>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_umkm" class="form-label fw-semibold">Nama UMKM</label>
                    <input type="text" class="form-control @error('nama_umkm') is-invalid @enderror" id="nama_umkm" name="nama_umkm" value="{{ old('nama_umkm', $user->nama_umkm) }}" required>
                    @error('nama_umkm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-success" style="background:#1a6b3a; border:none;">Simpan Perubahan</button>
                    @if (session('status') === 'profile-updated')
                        <span class="text-success small fw-semibold"><i class="bi bi-check-circle me-1"></i>Tersimpan.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-metric p-4 h-100">
            <h5 class="fw-bold mb-1">Ubah Kata Sandi</h5>
            <p class="text-muted small mb-4">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="current_password" class="form-label fw-semibold">Kata Sandi Saat Ini</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password', this)" style="border-color: #dee2e6;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Kata Sandi Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)" style="border-color: #dee2e6;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', this)" style="border-color: #dee2e6;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary" style="background:#0d6efd; border:none;">Perbarui Sandi</button>
                    @if (session('status') === 'password-updated')
                        <span class="text-success small fw-semibold"><i class="bi bi-check-circle me-1"></i>Sandi diperbarui.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
@endsection
