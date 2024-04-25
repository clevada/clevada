<header class='mb-0'>
    <nav class="navbar navbar-expand navbar-light ">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-arrow-bar-left fs-4"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            @if ($config->website_maintenance_enabled ?? null)
                <a class="badge bg-warning text-danger text-uppercase ms-3" href="#">
                    {{ __('maintenance mode') }}
                </a>
            @endif

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-1 mb-lg-0">

                    @if ($count_pending_posts ?? null > 0)
                        @can('viewAny', App\Models\Post::class)
                            <li class="nav-item me-2">
                                <a class="nav-link" href="{{ route('admin.posts.index') }}" title="{{ __('Posts') }}">
                                    <i class='bi bi-file-text bi-sub fs-5 text-gray-600'></i>
                                    <span class="badge bg-danger">{{ $count_pending_posts }}</span>
                                </a>
                            </li>
                        @endcan
                    @endif

                    @if ($count_unread_contact_messages ?? null > 0)
                        @can('viewAny', App\Models\Contact::class)
                            <li class="nav-item me-2">
                                <a class="nav-link" href="{{ route('admin.contact') }}" title="{{ __('Contact messages') }}">
                                    <i class='bi bi-textarea-resize bi-sub fs-5 text-gray-600'></i>
                                    <span class="badge bg-danger">{{ $count_unread_contact_messages }}</span>
                                </a>
                            </li>
                        @endcan
                    @endif

                    <li class="nav-item me-3">
                        <a class="nav-link" target="_blank" href="{{ route('home') }}" title="{{ __('View website') }}">
                            <i class='bi bi-globe bi-sub fs-5 text-gray-600'></i>
                        </a>
                    </li>
                </ul>

                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ avatar(Auth::user()->id) }}" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li>
                            <h6 class="dropdown-header">{{ Auth::user()->name }}</h6>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="icon-mid bi bi-person fs-6 me-2"></i> {{ __('Profile') }}</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> {{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
