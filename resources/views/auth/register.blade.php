<x-guest-layout>
    <div class="auth-title">Register</div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <!-- Name -->
        <div>
            <input id="name" class="form-control-custom" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="nama pendaftar">
            @error('name')
                <span class="text-danger-small">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nama UMKM -->
        <div>
            <input id="nama_umkm" class="form-control-custom" type="text" name="nama_umkm" value="{{ old('nama_umkm') }}" required autocomplete="organization" placeholder="nama umkm">
            @error('nama_umkm')
                <span class="text-danger-small">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <input id="email" class="form-control-custom" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="email">
            @error('email')
                <span class="text-danger-small">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <input id="password" class="form-control-custom" type="password" name="password" required autocomplete="new-password" placeholder="password">
            @error('password')
                <span class="text-danger-small">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <input id="password_confirmation" class="form-control-custom" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="konfirmasi password">
            @error('password_confirmation')
                <span class="text-danger-small">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-auth">
            Daftar
        </button>
        
        <a class="auth-link" href="{{ route('login') }}">
            Sudah punya akun? <span>Login Sekarang</span>
        </a>
    </form>
</x-guest-layout>
