<!doctype html>
<html lang="{{ $locale ?? 'en' }}">

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Favicon -->
    @if ($config->favicon ?? null)
        <link rel="shortcut icon" href="{{ image($config->favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ config('app.cdn') }}/img/favicon.png">
    @endif

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


</head>

<body class="bg-light">

    <div class="container">

        <div class="row py-5 mt-4 align-items-center">

            <div class="col-md-6 offset-md-3">

                <div class="text-center mb-4">

                    <img src="{{ config('app.cdn') }}/img/logo-auth.png" class="img-fluid" alt="Clevada Suite" title="Nura Software">

                    @if (!tenant())
                        <div class="mt-4">
                            <a class="fw-bold" target="_blank" href="https://clevada.com">NuraPress Home</a> |
                            <a class="fw-bold" target="_blank" href="https://clevada.com/features">Features</a> |
                            <a class="fw-bold" target="_blank" href="https://clevada.com/docs">Documentation</a> |
                            <a class="fw-bold" target="_blank" href="https://clevada.com/contact">Contact</a>
                        </div>
                    @endif

                    <hr>
                </div>

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        @if ($message == 'registration_disabled')
                            {{ __('Registration is disabled') }}
                        @endif
                        @if ($message == 'duplicate_subdomain')
                            {{ __('This subdomain exists') }}
                        @endif
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


                @if (!tenant())
                    <div class='fs-5 mb-3'>{{ __('Register an account to create your website') }}</div>
                @else
                    <div class='fs-5 mb-3'>{{ __('Create an account') }}</div>
                @endif
                
                @if ($config->registration_disabled ?? null)
                    <div class="alert alert-danger">
                        {{ __('Registration is disabled') }}.
                    </div>
                    <div class="text-center w-100">
                        <p class="text-muted fw-bold">{{ __('Already registered?') }} <a href="{{ route('login') }}" class="text-primary ml-2">{{ __('Login') }}</a></p>
                    </div>
                @else
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">

                            <!-- Name -->
                            <div class="col-12 mb-4">
                                <div class="input-group mb-1">
                                    <span class="input-group-text" id="addonName"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control form-control-lg" placeholder="{{ __('Full name') }}" aria-label="{{ __('Full name') }}" aria-describedby="addonName"
                                        @error('name') is-invalid @enderror value="{{ old('name') }}" required>
                                </div>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-12 mb-4">
                                <div class="input-group mb-1">
                                    <span class="input-group-text" id="addonEmail"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ __('Valid email address') }}" aria-label="{{ __('Valid email address') }}"
                                        aria-describedby="addonEmail" @error('email') is-invalid @enderror value="{{ old('email') }}" required>
                                </div>
                                <div class="form-text text-muted small">{{ __('Input a valid email address') }}</div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            @if (tenant())
                                <div class="col-12 mb-4">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text" id="addonUsername"><i class="bi bi-person-gear"></i></span>
                                        <input type="text" name="username" class="form-control form-control-lg" placeholder="{{ __('Username') }}" aria-label="{{ __('Username') }}" aria-describedby="addonUsername"
                                            @error('username') is-invalid @enderror value="{{ old('username') }}" required>
                                    </div>
                                    <div class="text-muted small">
                                        {{ __('Only letters, numbers, "-", "_" and "." signs. Username can not be changed.') }}
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif

                            <!-- Password -->
                            <div class="col-md-6 col-12 mb-4">
                                <div class="input-group mb-1">
                                    <span class="input-group-text" id="addonPw"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="{{ __('Password') }}" aria-label="{{ __('Password') }}" aria-describedby="addonPw"
                                        @error('password') is-invalid @enderror required minlength="8" id="pwInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                        title="{{ __('Minimum 8 characters. At least one number and one uppercase and lowercase letter') }}">
                                </div>
                                <div class="small text-muted">{{ __('Minimum 8 characters. At least one number and one uppercase and lowercase letter') }}</div>

                                <div class="form-check mt-2">
                                    <input type="checkbox" onclick="showPassword()" class="form-check-input mt-1" id="showPw"> <label for="showPw" class="fw-normal">{{ __('Show Password') }}</label>
                                </div>

                                <script>
                                    function showPassword() {
                                        var x = document.getElementById("pwInput");
                                        var x2 = document.getElementById("pw2Input");
                                        if (x.type === "password") {
                                            x.type = "text";
                                            x2.type = "text";
                                        } else {
                                            x.type = "password";
                                            x2.type = "password";
                                        }
                                    }
                                </script>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password 2 -->
                            <div class="col-md-6 col-12 mb-4">
                                <div class="input-group mb-1">
                                    <span class="input-group-text" id="addonPw2"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password_confirmation" id="pw2Input" class="form-control form-control-lg" placeholder="{{ __('Confirm password') }}"
                                        aria-label="{{ __('Confirm password') }}" aria-describedby="addonPw2" @error('email') is-invalid @enderror required minlength="6">
                                </div>
                                <div class="form-text text-muted small">{{ __('Confirm password') }}</div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            @if (!tenant())
                                <div class="col-12 mb-4">
                                    <div class="form-check fw-bold">
                                        <input class="form-check-input" type="checkbox" value="" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            {{ __('I agree with') }}
                                            <a target="_blank" href="https://nurapress.com/terms-and-conditions">{{ __('Terms and Conditions') }}</a>,
                                            <a target="_blank" href="https://nurapress.com/privacy-policy">{{ __('Privacy Policy') }}</a> {{ __('and') }}
                                            <a target="_blank" href="https://nurapress.com/refund-policy">{{ __('Refund Policy') }}</a>
                                        </label>
                                    </div>
                                </div>
                            @endif


                            <!-- Submit Button -->
                            <div class="form-group col-lg-12 mx-auto mb-0">

                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-block py-2">
                                        <span class="font-weight-bold">{{ __('Create your account') }}</span>
                                    </button>
                                </div>
                            </div>

                            <div class="w-100">
                                <!-- Divider Text -->
                                <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                                    <div class="border-bottom w-100 ml-5"></div>
                                    <span class="px-2 small text-muted font-weight-bold text-muted">{{ __('OR') }}</span>
                                    <div class="border-bottom w-100 mr-5"></div>
                                </div>


                                <!-- Already Registered -->
                                <div class="text-center w-100">
                                    <p class="text-muted fw-bold">{{ __('Already registered?') }} <a href="{{ route('login') }}" class="text-primary ml-2">{{ __('Login') }}</a></p>
                                </div>

                            </div>
                        </div>
                    </form>
                @endif

            </div>

        </div>

    </div>

</body>

</html>
