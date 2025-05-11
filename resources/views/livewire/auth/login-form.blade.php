<div>
    <form wire:submit.prevent="login">
        @csrf

        <!-- Email -->
        <div>
            <label for="email">Email</label>
            <input 
                id="email" 
                type="email" 
                wire:model="email" 
                required 
                autofocus 
                autocomplete="username"
            >
            @error('email') <span>{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password">Password</label>
            <input 
                id="password" 
                type="password" 
                wire:model="password" 
                required 
                autocomplete="current-password"
            >
            @error('password') <span>{{ $message }}</span> @enderror
        </div>

        <!-- Remember Me -->
        <div>
            <label for="remember_me">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    wire:model="remember"
                >
                <span>Remember me</span>
            </label>
        </div>

        <div>
            <button type="submit">
                Log in
            </button>
        </div>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                Forgot your password?
            </a>
        @endif
    </form>
</div>