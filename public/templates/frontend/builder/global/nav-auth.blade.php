@if (Auth::user())
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="navbarDropdownAuth" role="button">
            @if (Auth::user()->avatar)
            <img class="avatar rounded-circle" alt="{{ Auth::user()->name }}" src="{{ thumb(Auth::user()->avatar) }}">@else<i class="bi bi-person-circle"></i>
            @endif
            {{ strtok(Auth::user()->name, ' ') }} <i class="bi bi-chevron-down"></i>
        </a>

        <ul class="dropdown-menu" aria-labelledby="navbarDropdownAuth">

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
    @if (!($config->registration_disables ?? null))
        <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link"><i class="bi bi-person"></i> <span class="d-none d-md-inline-block"> {{ __('My account') }}</span></a>
        </li>
    @endif
@endif
