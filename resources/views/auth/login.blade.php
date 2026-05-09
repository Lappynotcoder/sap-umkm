<x-guest-layout>
    <div class="auth-title">Login</div>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div>
            <input id="email" class="form-control-custom" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="email">
            @error('email')
                <span class="text-danger-small">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="pass-wrapper mb-3">
            <input id="password" class="form-control-custom m-0" type="password" name="password" required autocomplete="current-password" placeholder="password">
            <button type="button" class="btn-show-pass" onclick="togglePassword('password', this)">
                <i class="bi bi-eye"></i>
            </button>
            @error('password')
                <span class="text-danger-small mt-2">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-auth">
            Login
        </button>
        
        <a class="auth-link" href="{{ route('register') }}">
            Belum punya akun? <span>Daftar Sekarang</span>
        </a>
    </form>
</x-guest-layout>
