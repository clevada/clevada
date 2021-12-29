<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Create an account') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('templates/auth/assets/css/app.css') }}" rel="stylesheet">

    <!-- Favicon -->
    @if ($config->favicon)
        <link rel="shortcut icon" href="{{ asset("/uploads/$config->favicon") }}">
    @endif

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Google reCAPTCHA -->
    @if (($config->google_recaptcha_enabled ?? null) && ($config->registration_recaptcha ?? null) && ($config->google_recaptcha_site_key ?? null) && ($config->google_recaptcha_secret_key ?? null))
        <script src="https://www.google.com/recaptcha/api.js?render={{ $config->google_recaptcha_site_key ?? null }}"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $config->google_recaptcha_site_key ?? null }}', {
                    action: 'contact'
                }).then(function(token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        </script>
    @endif    

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

    <div class="container">

        <div class="row py-5 mt-4 align-items-center">

            <div class="col-md-6 offset-md-3">

                <div class="text-center mb-4">
                    @if (($config->whitelabel_active ?? null) == 0)
                        <a href="{{ route('homepage') }}"><img src="{{ image('default/logo.png') }}" class="img-fluid" alt="{{ 'Clevada.com' }}"></a>
                        <div class="mt-1">{{ __('Powered by') }} <a href="https://clevada.com"><b>Clevada: Freee Website Buikder</b></a></div>
                    @else
                        <a href="{{ route('homepage') }}"><img src="{{ image($config->logo_auth ?? 'default/logo.png') }}" class="img-fluid" alt="{{ $config->site_meta_author ?? 'Clevada' }}"></a>
                    @endif

                    <hr>
                </div>

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        @if ($message == 'registration_disabled')
                            {{ __('Registration is disabled') }} @endif
                    </div>
                @endif

                @error('recaptcha')
                    <div class="text-danger">
                        <strong>{{ __('Antispam error') }}</strong>
                    </div>
                @enderror

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <h3 class='mb-3'>{{ __('Create an account') }}</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row">

                        @error('subdomain')@enderror

                        <!-- Name -->
                        <div class="col-12 mb-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white px-4 border-md border-right-0">
                                        <i class="bi bi-person"></i>
                                    </span>
                                </div>

                                <input id="name" type="text" class="form-control bg-white border-left-0 border-md @error('name') is-invalid @enderror" name="name" placeholder="{{ __('Owner full name') }}"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus aria-describedby="nameHelp">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                            
                        </div>

                        <!-- Email Address -->
                        <div class="col-12 mb-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white px-4 border-md border-right-0">
                                        <i class="bi bi-envelope text-muted"></i>
                                    </span>
                                </div>
                                <input id="email" type="email" class="form-control bg-white border-left-0 border-md" name="email" placeholder="{{ __('Email') }}"
                                    value="{{ old('email') }}" required autocomplete="email" aria-describedby="emailHelp">                                
                            </div>
                            <div id="emailHelp" class="form-text text-muted">
                                {{ __('Input a valid email address') }}</div>
                        </div>

                        <!-- Password -->
                        <div class="input-group col-lg-6 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 py-2 border-md border-right-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                            </div>

                            <input id="password" type="password" class="form-control bg-white border-left-0 border-md" name="password"
                                placeholder="{{ __('Password') }}" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <!-- Password Confirmation -->
                        <div class="input-group col-lg-6 mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-4 py-2 border-md border-right-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                            </div>

                            <input id="password-confirm" type="password" class="form-control bg-white border-left-0 border-md" name="password_confirmation"
                                placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-lg-12 mx-auto mb-0">

                            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                            <button type="submit" class="btn btn-primary btn-block py-2">
                                <span class="font-weight-bold">{{ __('Create your account') }}</span>
                            </button>
                        </div>

                        <div class="text-center w-100">
                            <p class="font-italic text-muted text-center mt-3">
                                {{ __("By clicking 'Create your account', you're agreeing to our") }} <a href="{{ $config->terms_conditions_page ?? '#' }}" class="text-muted">
                                    <u>{{ __('Terms and Conditions') }}</u></a>
                            </p>


                            <!-- Divider Text -->
                            <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                                <div class="border-bottom w-100 ml-5"></div>
                                <span class="px-2 small text-muted font-weight-bold text-muted">{{ __('OR') }}</span>
                                <div class="border-bottom w-100 mr-5"></div>
                            </div>


                            <!-- Already Registered -->
                            <div class="text-center w-100">
                                <p class="text-muted font-weight-bold">{{ __('Already registered?') }} <a href="{{ route('login') }}" class="text-primary ml-2">{{ __('Login') }}</a></p>
                            </div>

                        </div>


                </form>

            </div>

        </div>

    </div>

</body>

</html>
