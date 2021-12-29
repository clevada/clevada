<header class='mb-1'>
    <nav class="navbar navbar-expand navbar-light ">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    
                    @if(check_access('forms'))
                    <li class="nav-item me-1">
                        <a class="nav-link" href="{{ route('admin.forms') }}">
                            <i class='bi bi-file-text bi-sub fs-4 text-gray-600'></i> @if($admin_count_unread_forms > 0) <span class="badge bg-danger">{{ $admin_count_unread_forms }}</span> @endif
                        </a>                        
                    </li>
                    @endif

                    <li class="nav-item me-1">
                        <a class="nav-link" target="_blank" href="{{ route('homepage') }}" title="{{ __('View website') }}">
                            <i class='bi bi-globe bi-sub fs-4 text-gray-600'></i>
                        </a>                        
                    </li>                    
                </ul>

                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    @if(Auth::user()->avatar)
                                    <img src="{{ thumb(Auth::user()->avatar) }}" class="img-fluid" />
                                    @else
                                    <img src="{{ image('default/no-avatar.png') }}" class="img-fluid">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li>
                            <h6 class="dropdown-header">{{ Auth::user()->name }}</h6>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="icon-mid bi bi-person me-2"></i> {{ __('Profile') }}</a></li>                        
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
