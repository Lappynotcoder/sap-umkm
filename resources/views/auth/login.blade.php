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
        <div>
            <input id="password" class="form-control-custom" type="password" name="password" required autocomplete="current-password" placeholder="password">
            @error('password')
                <span class="text-danger-small">{{ $message }}</span>
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
