<!-- Left Sidebar -->
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    @if (($config->clevada_pro_active ?? null) == 0)
                        <a href="{{ route('admin') }}"><img src="{{ config('app.cdn') }}/img/logo.png" class="img-fluid" alt="{{ 'Clevada.com' }}"></a>
                    @else
                        <a href="{{ route('admin') }}"><img src="{{ image($config->logo_backend ?? 'default/logo.png') }}" class="img-fluid" alt="{{ $config->site_meta_author ?? 'Admin' }}"></a>
                    @endif
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">

                <li class="sidebar-item @if (($active_menu ?? null) == 'dashboard') active @endif">
                    <a href="{{ route('admin') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                {{--
                @if (check_access('accounts'))
                <li class="sidebar-item has-sub @if (($active_menu ?? null) == 'users') active @endif">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>{{ __('Accounts and Users') }}</span>
                    </a>
                    <ul class="submenu @if (($active_menu ?? null) == 'users') active @endif">
                        <li class="submenu-item @if (($active_submenu ?? null) == 'users.internal') active @endif">
                            <a href="{{ route('admin.accounts.index', ['role' => 'internal']) }}">{{ __('Internal accounts') }}</a>
                        </li>

                        <li class="submenu-item @if (($active_submenu ?? null) == 'users.user') active @endif">
                            <a href="{{ route('admin.accounts.index', ['role' => 'user']) }}">{{ __('Registered users') }}</a>
                        </li>

                        <li class="submenu-item @if (($active_submenu ?? null) == 'invitations') active @endif">
                            <a href="{{ route('admin.accounts.invitations') }}">{{ __('Invitations') }}</a>
                        </li>
                    </ul>
                </li>
                @endif
                --}}

                @php
                    $has_website_access = false;
                @endphp

                @can('view', App\Models\Page::class)
                    @php $has_website_access = true; @endphp
                @endcan

                @can('view', App\Models\Post::class)
                    @php $has_website_access = true; @endphp
                @endcan

                @can('view', App\Models\PostCateg::class)
                    @php $has_website_access = true; @endphp
                @endcan

                @can('view', App\Models\Glossary::class)
                    @php $has_website_access = true; @endphp
                @endcan

                @if ($has_website_access)
                    <li class="sidebar-item has-sub @if (($active_menu ?? null) == 'website') active @endif">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-file-text"></i>
                            <span>{{ __('Website content') }}</span>
                        </a>
                        <ul class="submenu @if (($active_menu ?? null) == 'website') active @endif">
                            @can('view', App\Models\Page::class)
                                <li class="submenu-item @if (($active_submenu ?? null) == 'pages') active @endif">
                                    <a href="{{ route('admin.pages.index') }}">{{ __('Pages') }}</a>
                                </li>
                            @endcan

                            @can('view', App\Models\Post::class)
                                <li class="submenu-item @if (($active_submenu ?? null) == 'posts') active @endif">
                                    <a href="{{ route('admin.posts.index') }}">{{ __('Posts') }}</a>
                                </li>
                            @endcan

                            @can('viewAny', App\Models\Glossary::class)
                                <li class="submenu-item @if (($active_submenu ?? null) == 'posts') active @endif">
                                    <a href="{{ route('admin.glossary.index') }}">{{ __('Glossary') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{--
                @if (check_access('tickets'))
                    <li class="sidebar-item @if (($active_menu ?? null) == 'tickets') active @endif">
                        <a href="{{ route('admin.tickets') }}" class='sidebar-link'>
                            <i class="bi bi-ticket"></i>
                            <span>{{ __('Support tickets') }}</span>
                        </a>
                    </li>
                @endif
                --}}


                @can('viewAny', App\Models\Contact::class)
                    <li class="sidebar-item @if (($active_menu ?? null) == 'contact') active @endif">
                        <a href="{{ route('admin.contact') }}" class='sidebar-link'>
                            <i class="bi bi-textarea-resize"></i>
                            <span>{{ __('Contact messages') }}</span>
                            @if ($count_unread_contact_messages ?? null > 0)
                                <span class="badge bg-danger">{{ $count_unread_contact_messages }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
<!-- End Sidebar -->
