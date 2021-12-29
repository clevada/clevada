<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Verify user') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('/templates/auth/assets/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('/templates/auth/assets/css/app.css') }}" rel="stylesheet">

    <!-- Favicon -->
    @if($config->favicon)<link rel="shortcut icon" href="{{ asset("/uploads/$config->favicon") }}">@endif

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

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

    <!-- Navbar-->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <!-- Navbar Brand -->
                <a href="{{ route('homepage', ['lang' => $lang]) }}" class="navbar-brand">
                    <img src="{{ image('default/logo.png') }}" alt="{{ $config->site_meta_author ?? 'Clevada' }}">
                </a>

                @if(count(languages())>1)
                <div class="dropdown">
                    <a class="btn btn-lg" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-globe"></i> {{ $locale }} <i class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @foreach (languages() as $nav_lang)
                        <li class="dropdown-item">
                            <a href="{{ route('login', ['lang' => $nav_lang->code]) }}" class="nav-link">
                                <span @if($locale==$nav_lang->code) class="font-weight-bold" @endif> {{ $nav_lang->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </nav>
    </header>


    <div class="container">

        @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            @if ($message=='login_required') {{ __('Login is required') }}. {{ __('Please login') }} {{ __('or') }} <a href="{{ route('register', ['lang' => $lang]) }}"><b>{{ __('register new account') }}</b></a>. @endif            
        </div>
        @endif

        <div class="row py-5 mt-4 align-items-center">
          
            <!-- Registeration Form -->
            <div class="col-12 ml-auto">

                <h3 class='mb-4'>{{ __('Verify Your Email Address') }}</h3>

                @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
                @endif

                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend', ['lang' => $lang]) }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                </form>

            </div>

        </div>
    </div>

</body>

</html>