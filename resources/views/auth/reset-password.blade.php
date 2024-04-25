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


                <div class='fs-5 mb-3'>{{ __('Create a new password for your account') }}</div>

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

                <form method="POST" action="/reset-password">
                    @csrf

                    <input type="hidden" name="token" value="{{ request()->route('token') }}">
                    
                    <div class="form-group row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-8">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New password') }}</label>

                        <div class="col-md-8">
                            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm new password') }}</label>

                        <div class="col-md-8">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group col-lg-12 mx-auto mb-0">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-block py-2">
                                <span class="font-weight-bold">{{ __('Reset password') }}</span>
                            </button>
                        </div>
                    </div>

                    <hr>
                    <!-- Already Registered -->
                    <div class="text-center w-100">
                        {{ __('Already registered?') }} <a href="{{ route('login', ['locale' => $locale]) }}" class="text-primary ml-2">{{ __('Login') }}</a>
                    </div>

                    <input type="hidden" name="locale" value="{{ $locale }}">
                </form>

            </div>
        </div>
    </div>

</body>

</html>
