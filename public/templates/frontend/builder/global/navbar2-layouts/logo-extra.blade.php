    <nav class="navbar navbar-expand navbar2">
        <div class="container-xxl">

            <div class="me-auto">
                <a class="navbar-brand" title="{{ site()->title }}" href="{{ site()->url }}">@if ($config->logo)<img class="img-fluid align-items-center" src="{{ image($config->logo ?? null) }}" alt="{{ site()->title }}">@endif</a>
            </div>

            <ul class="navbar-nav">
                @if (Route::has('login') && template('navbar2_show_auth'))
                    @auth
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="navbarDropdownAuth" role="button">
                                @if (Auth::user()->avatar)
                                <img class="avatar rounded-circle" alt="{{ Auth::user()->name }}" src="{{ thumb(Auth::user()->avatar) }}">@else<i class="bi bi-person-circle"></i>
                                @endif
                                {{ strtok(Auth::user()->name, ' ') }} <i class="bi bi-chevron-down"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownAuth">

                                @if (logged_user()->role == 'admin' || logged_user()->role == 'internal')
                                    <li><a href="{{ route('admin') }}" class="dropdown-item"><i class="bi bi-gear"></i> {{ __('Admin area') }}</a></li>
                                @endif

                                @if (logged_user()->role == 'user')
                                    <li><a href="{{ route('user') }}" class="dropdown-item"><i class="bi bi-person-circle"></i> {{ __('My account') }}</a></li>
                                @endif

                                <li><a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i>
                                        {{ __('Sign out') }}</a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    @else
                        @if ($config->registration_enabled == 1)
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link"><i class="bi bi-person-circler"></i> <span class="d-none d-md-inline-block"> {{ __('My account') }}</span></a>
                            </li>
                        @endif
                    @endauth
                @endif


                @if (count(languages()) > 1 && template('navbar2_show_langs'))
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLangs" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe"></i> <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLangs">
                            @foreach (languages() as $nav_lang)
                                <li><a class="dropdown-item" @if ($nav_lang->is_default == 1) href="{{ config('app.url') }}" @else href="{{ config('app.url') }}/{{ $nav_lang->code }}" @endif>{{ $nav_lang->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>

        </div>
    </nav>
