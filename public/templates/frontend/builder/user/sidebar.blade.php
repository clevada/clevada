<nav class='user-nav animated bounceInDown'>
    <ul>
        @if (file_exists('templates/frontend/builder/user/custom-nav.blade.php'))
        @include("frontend.builder.user.custom-nav")
        @endif

        <li @if(($nav_menu ?? null ) == 'profile') class="active" @endif><a @if(($nav_menu ?? null ) == 'profile') class="active" @endif href='{{ route('user.profile', ['lang' => $lang]) }}'><i class="bi bi-person-bounding-box"></i> {{ __('Profile') }}</a></li>

        @if (check_module('posts') && Auth::user()->posts_contributor == 1)
        <li @if(($nav_menu ?? null ) == 'posts') class="active" @endif><a @if(($nav_menu ?? null ) == 'posts') class="active" @endif href='{{ route('user.posts', ['lang' => $lang]) }}'><i class="bi bi-card-text"></i> {{ __('Articles') }}</a></li>
        @endif

        @if (check_module('forum'))
            <li class='sub-menu forum @if(($nav_menu ?? null ) == 'forum') active @endif'><a href='#'><i class="bi bi-chat-square-quote"></i> {{ __('Forum') }}<div class='bi bi-chevron-down right float-end'></div></a>
                <ul>
                    <li><a href="{{ route('user.forum.topics') }}">{{ __('My topics') }}</a></li>
                    <li><a href="{{ route('user.forum.posts') }}">{{ __('My posts') }}</a></li>
                    <li><a href="{{ route('user.forum.warnings') }}">{{ __('Warnings') }}</a></li>
                    <li><a href="{{ route('user.forum.restrictions') }}">{{ __('Restrictions') }}</a></li>
                    <li><a href="{{ route('user.forum.config') }}">{{ __('Settings') }}</a></li>
                </ul>
            </li>
        @endif       

        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();"><i class="bi bi-box-arrow-right
"></i> {{ __('Logout') }}</a></li>
        <form id="user-logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
            @csrf
        </form>
    </ul>
</nav>

