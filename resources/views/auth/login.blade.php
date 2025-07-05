<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title>Login</title>
        {{-- Copy a part of the style from register view --}}
        <style>
            body {
                font-family: sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f0f2f5;
            }
            form {
                background: #fff;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
            }
            div {
                margin-bottom: 1rem;
            }
            label {
                display: block;
                margin-bottom: 0.5rem;
            }
            input[type='email'],
            input[type='password'] {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            button {
                width: 100%;
                padding: 0.75rem;
                border: none;
                border-radius: 4px;
                background-color: #28a745;
                color: white;
                font-size: 1rem;
                cursor: pointer;
            }
            button:hover {
                background-color: #218838;
            }
            .error {
                color: red;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
            .remember {
                display: flex;
                align-items: center;
            }
            .remember label {
                margin-bottom: 0;
                margin-left: 0.5rem;
            }
        </style>
    </head>
    <body>
        <form
            method="POST"
            action="{{ route('login') }}"
        >
            @csrf

            <h2>Login</h2>

            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="email">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                />
            </div>

            <div>
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                />
            </div>

            <div class="remember">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                />
                <label for="remember">Remember Me</label>
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>
    </body>
</html>
