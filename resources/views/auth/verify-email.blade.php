<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Verify email') }} - {{ config('app.name') }}</title>

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


</head>


<body class="bg-light">

    <div class="container">

        <div class="row py-5 mt-4 align-items-center">

            <div class="col-md-6 offset-md-3">

                <div class="text-center mb-4">

                    <img src="{{ config('app.cdn') }}/img/logo-auth.png" class="img-fluid" alt="Clevada Business Suite">
                    <div class="mt-1">{{ __('Powered by') }} <a class="fw-bold" target="_blank" href="https://clevada.com">Clevada</a></div>

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



                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class='fs-5 mb-3'>{{ __('Verify Your Email Address') }}</div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('A new email verification link has been emailed to you!') }}
                    </div>
                @endif

                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
                <form class="d-inline" method="POST" action="/email/verification-notification">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                </form>

            </div>

        </div>

    </div>

</body>



</html>
