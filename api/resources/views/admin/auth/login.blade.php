<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wizmeek Login</title>
    @vite(array_merge(['resources/scss/app.scss', 'resources/scss/popup.scss'], $data['scss']))
</head>

<body>
    <section class="login">
        <div class="login__inner">
            <form action="{{ route('admin_authenticate') }}" method="POST">
                @csrf
                @method('POST')

                {{-- Logo http://localhost:8876/img/wizmeek_logo.webp --}}
                <div class="form__logo">
                    <img src="{{ asset('img/wizmeek_logo.webp') }}" alt="Wizmeek Project" width="200">
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                {{-- Email Input --}}
                <div class="form__row">
                    <div class="form__group">
                        <input type="text" placeholder="" name="email" value="{{ old('email') ?: '' }}">
                        <label for="">
                            @error('email')
                                <span class="danger">{{ $message }}</span>
                            @else
                                <span>Email*</span>
                            @enderror
                        </label>
                    </div>
                </div>
                {{-- // Email Input --}}

                {{-- Password Input --}}
                <div class="form__row">
                    <div class="form__group">
                        <input type="password" placeholder="" name="password" value="{{ old('password') ?: '' }}">
                        <label for="">
                            @error('password')
                                <span class="danger">{{ $message }}</span>
                            @else
                                <span>Password*</span>
                            @enderror
                        </label>
                    </div>
                </div>
                {{-- // Password Input --}}

                {{-- Login Button --}}
                <div class="form__row">
                    <button class="submit">Login</button>
                </div>
                {{-- // Login Button --}}
            </form>
        </div>
    </section>
</body>

</html>
