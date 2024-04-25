<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Login') }}</title>

    @include('auth.includes.global-head')

    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
</head>

<body class="bg-light">

    <div class="container mt-5">

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'login_required')
                    {{ __('Login is required') }}. {{ __('Please login') }} {{ __('or') }} <a href="{{ route('register') }}"><b>{{ __('register new account') }}</b></a>.
                @endif
            </div>
        @endif

        <div class="row py-5 mt-5 align-items-center">

            <div class="col-md-6 offset-md-3">

                <div class="text-center mb-4">

                    <img src="{{ asset('assets/img/logo-auth.png') }}" class="img-fluid" alt="{{ config('app.name') }}">

                    <hr>

                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="row">

                        <!-- Email Address -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonEmail"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}" aria-describedby="addonEmail"
                                    @error('email') is-invalid @enderror required autocomplete="email" value="{{ old('email') }}">
                            </div>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonPw"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="{{ __('Password') }}" aria-label="{{ __('Password') }}" aria-describedby="addonPw"
                                    @error('password') is-invalid @enderror required>
                            </div>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group col-12 mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} checked>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-lg-12 mx-auto mb-0">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-block py-2">
                                    <span class="font-weight-bold">{{ __('Login') }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Divider Text -->
                        <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                            <div class="border-bottom w-100 ml-5"></div>
                            <span class="px-2 small text-muted font-weight-bold text-muted">{{ __('OR') }}</span>
                            <div class="border-bottom w-100 mr-5"></div>
                        </div>


                        <!-- Already Registered -->
                        <div class="text-center w-100">
                            @if (Route::has('register'))
                                @if (!$registration_disabled ?? null)
                                    <p class="text-muted font-weight-bold">{{ __('New on website?') }} <a href="{{ route('register') }}" class="text-primary fw-bold ms-2">{{ __('Register an account') }}</a></p>
                                @endif
                            @endif

                            @if (Route::has('password.request'))
                                <p class="text-muted font-weight-bold"><a href="{{ route('password.request') }}" class="text-primary fw-bold ms-2">{{ __('Forgot password') }}</a></p>
                            @endif

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>
