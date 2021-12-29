<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Login') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('templates/auth/assets/css/app.css') }}" rel="stylesheet">

    <!-- Favicon -->
    @if ($config->favicon ?? null)<link rel="shortcut icon" href="{{ image($config->favicon) }}">@endif

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
        }

        .border-md {
            border-width: 2px;
        }

        .form-control:not(select) {
            padding: 1.5rem 0.5rem;
        }

        select.form-control {
            height: 52px;
            padding-left: 0.5rem;
        }

        .form-control::placeholder {
            color: #ccc;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .form-control:focus {
            box-shadow: none;
        }

    </style>

</head>

<body>

    <div class="container mt-5">

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'login_required') {{ __('Login is required') }}. {{ __('Please login') }} {{ __('or') }} <a href="{{ route('register') }}"><b>{{ __('register new account') }}</b></a>. @endif
            </div>
        @endif

        <div class="row py-5 mt-5 align-items-center">

            <div class="col-md-6 offset-md-3">

                <div class="text-center mb-4">
                    @if (($config->whitelabel_active ?? null) == 0)
                        <a href="{{ route('homepage') }}"><img src="{{ image('default/logo.png') }}" class="img-fluid" alt="{{ 'Clevada.com' }}"></a>
                        <div class="mt-1">{{ __('Powered by') }} <a href="https://clevada.com"><b>Clevada Suite</b></a></div>
                    @else
                        <a href="{{ route('homepage') }}"><img src="{{ image($config->logo_auth ?? 'default/logo.png') }}" class="img-fluid" alt="{{ $config->site_meta_author ?? 'Login' }}"></a>
                    @endif
                                   
                    <hr>
                </div>

                <h4 class='mb-4'>{{ __('Login into your account') }} {{ __('or') }} <a href="{{ route('register') }}">{{ __('Register new account') }}</a></h4>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="row">

                        <!-- Email Address -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 border-md border-right-0">
                                    <i class="bi bi-envelope-open"></i>
                                </span>
                            </div>
                            <input id="email" type="email" class="form-control bg-white border-left-0 border-md @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email') }}"
                                value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <!-- Password -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 border-md border-right-0">
                                    <i class="bi bi-lock"></i>
                                </span>
                            </div>

                            <input id="password" type="password" class="form-control bg-white border-left-0 border-md @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required
                                autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group col-12 mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-lg-12 mx-auto mb-0">
                            <button type="submit" class="btn btn-primary btn-block py-2">
                                <span class="font-weight-bold">{{ __('Login') }}</span>
                            </button>
                        </div>

                        <!-- Divider Text -->
                        <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                            <div class="border-bottom w-100 ml-5"></div>
                            <span class="px-2 small text-muted font-weight-bold text-muted">{{ __('OR') }}</span>
                            <div class="border-bottom w-100 mr-5"></div>
                        </div>


                        <!-- Already Registered -->
                        <div class="text-center w-100">
                            @if (Route::has('password.request'))
                                <p class="text-muted font-weight-bold"><a href="{{ route('password.request') }}" class="text-primary ml-2">{{ __('Forgot password') }}</a></p>
                            @endif

                            @if (Route::has('register'))
                                <p class="text-muted font-weight-bold">{{ __('New on website?') }} <a href="{{ route('register') }}" class="text-primary ml-2">{{ __('Register an account') }}</a></p>
                            @endif
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>
