<x-guest-layout>

    <h2>Welcome Back</h2>
    <p class="subtitle">Sign in to access your UPF - Digi Link Dashboard</p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email"
                name="email"
                :value="old('email')"
                required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password"
                name="password"
                required />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="options-row">

    <label class="remember-wrapper">
        <input type="checkbox" name="remember">
        <span>Remember me</span>
    </label>

    @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="forgot-link">
            Forgot password?
        </a>
    @endif

</div>

<button type="submit" class="login-btn">
    Log in
</button>
    </form>

</x-guest-layout>