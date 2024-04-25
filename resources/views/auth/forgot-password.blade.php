<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Forgot password') }}</title>

    @include('auth.includes.global-head')
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

                    <img src="{{ config('app.cdn') }}/img/logo-auth.png" class="img-fluid" alt="Clevada Suite" title="Clevada Suite">

                    @if (!tenant())
                        <div class="mt-4">
                            <a class="" target="_blank" href="https://clevada.com">Clevada Home</a> |
                            <a class="" target="_blank" href="https://clevada.com/features">Features</a> |
                            <a class="" target="_blank" href="https://clevada.com/docs">Documentation</a> |
                            <a class="" target="_blank" href="https://clevada.com/contact">Contact</a>
                        </div>
                    @endif

                    <hr>
                </div>


                <div class='fs-5 mb-3'>{{ __('Reset password') }}</div>

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

                <form method="POST">
                    @csrf

                    <div class="row">

                        <div class="form-group mb-3">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonEmail"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}" aria-describedby="addonEmail"
                                    @error('email') is-invalid @enderror required autocomplete="email">
                            </div>
                        </div>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                        <!-- Submit Button -->
                        <div class="form-group col-lg-12 mx-auto mb-0">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-block py-2">
                                    <span class="font-weight-bold">{{ __('Reset password') }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Divider Text -->
                        <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                            <div class="border-bottom w-100 ml-5"></div>
                            <span class="px-2 small text-muted font-weight-bold text-muted">{{ __('OR') }}</span>
                            <div class="border-bottom w-100 mr-5"></div>
                        </div>


                        <div class="text-center w-100">
                            @if (Route::has('register'))
                                <p class="text-muted font-weight-bold">{{ __('Already registered?') }} <a href="{{ route('login') }}" class="text-primary ml-2">{{ __('Login') }}</a></p>
                            @endif

                            <input type="hidden" name="locale" value="{{ $locale }}">
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>
