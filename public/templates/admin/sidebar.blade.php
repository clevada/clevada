<!-- Left Sidebar -->
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    @if (($config->whitelabel_active ?? null) == 0)
                        <a href="{{ route('admin') }}"><img src="{{ image('default/logo.png') }}" class="img-fluid" alt="{{ 'Clevada.com' }}"></a>
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

                @if(check_access('accounts'))
                <li class="sidebar-item @if (($active_menu ?? null) == 'accounts') active @endif">
                    <a href="{{ route('admin.accounts') }}" class='sidebar-link'>
                        <i class="bi-person-bounding-box"></i>
                        <span>{{ __('Accounts') }}</span>
                    </a>
                </li>
                @endif

                @if(check_access('pages') || check_access('posts') || check_access('developer'))
                <li class="sidebar-item has-sub @if (($active_menu ?? null) == 'website') active @endif">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-card-text"></i>
                        <span>{{ __('Content Management') }}</span>
                    </a>
                    <ul class="submenu @if (($active_menu ?? null) == 'website') active @endif">

                        @if (check_access('posts'))
                            <li class="submenu-item @if (($active_submenu ?? null) == 'posts') active @endif">
                                <a href="{{ route('admin.posts') }}">{{ __('Posts') }}</a>
                            </li>
                        @endif 

                        @if(check_access('pages'))
                            <li class="submenu-item @if (($active_submenu ?? null) == 'pages') active @endif">
                                <a href="{{ route('admin.pages') }}">{{ __('Pages') }}</a>
                            </li>
                        @endif  

                        @if (check_access('posts') || check_access('pages') || check_access('developer'))
                            <li class="submenu-item @if (($active_submenu ?? null) == 'media') active @endif">
                                <a href="{{ route('admin.media') }}">{{ __('Media') }}</a>
                            </li>
                        @endif                      
                    </ul>
                </li>
                @endif
                
                @if(check_access('forum'))
                    <li class="sidebar-item has-sub @if (($active_menu ?? null) == 'forum') active @endif">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-chat-square-quote"></i>
                            <span>{{ __('Community') }}</span>
                        </a>
                        <ul class="submenu @if (($active_menu ?? null) == 'forum') active @endif">
                            <li class="submenu-item @if (($active_submenu ?? null) == 'forum.topics') active @endif">
                                <a href="{{ route('admin.forum.topics') }}">{{ __('Topics (subjects)') }}</a>
                            </li>
                            <li class="submenu-item @if (($active_submenu ?? null) == 'forum.posts') active @endif">
                                <a href="{{ route('admin.forum.posts') }}">{{ __('Posts (responses)') }}</a>
                            </li>
                            <li class="submenu-item @if (($active_submenu ?? null) == 'forum.reports') active @endif">
                                <a href="{{ route('admin.forum.reports') }}">{{ __('Reports') }}</a>
                            </li>

                            @if(logged_user()->role == 'admin')
                            <li class="submenu-item @if (($active_submenu ?? null) == 'forum.categ') active @endif">
                                <a href="{{ route('admin.forum.categ') }}">{{ __('Forum structure') }}</a>
                            </li>
                            <li class="submenu-item @if (($active_submenu ?? null) == 'forum.config') active @endif">
                                <a href="{{ route('admin.forum.config') }}">{{ __('Forum config') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(check_access('forms'))
                <li class="sidebar-item @if (($active_menu ?? null) == 'forms') active @endif">
                    <a href="{{ route('admin.forms') }}" class='sidebar-link'>
                        <i class="bi bi-file-text"></i>
                        <span>{{ __('Forms') }}</span>
                    </a>
                </li>
                @endif           

                @if(check_access('tasks'))
                <li class="sidebar-item has-sub @if (($active_menu ?? null) == 'productivity') active @endif">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-clipboard-check"></i>
                        <span>{{ __('Productivity') }}</span>
                    </a>
                    <ul class="submenu @if (($active_menu ?? null) == 'productivity') active @endif">
                        <li class="submenu-item @if (($active_submenu ?? null) == 'tasks') active @endif">
                            <a href="{{ route('admin.tasks') }}">{{ __('Tasks') }}</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(check_access('developer'))
                <li class="sidebar-item @if (($active_menu ?? null) == 'template') active @endif">
                    <a href="{{ route('admin.templates') }}" class='sidebar-link'>
                        <i class="bi bi-laptop"></i>
                        <span>{{ __('Template') }}</span>
                    </a>
                </li>
                @endif

                @if(check_access('translates'))
                <li class="sidebar-item has-sub @if (($active_menu ?? null) == 'config') active @endif">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>{{ __('Configuration') }}</span>
                    </a>                    
                    <ul class="submenu @if (($active_menu ?? null) == 'config') active @endif">

                        @if(logged_user()->role == 'admin')       
                        <li class="submenu-item @if (($active_submenu ?? null) == 'config.general') active @endif">
                            <a href="{{ route('admin.config.general') }}">{{ __('General') }}</a>
                        </li>
                        
                        <li class="submenu-item @if (($active_submenu ?? null) == 'config.permissions') active @endif">
                            <a href="{{ route('admin.accounts.permissions') }}">{{ __('Internal permissions') }}</a>
                        </li>
                        @endif

                        @if(check_access('translates'))
                        <li class="submenu-item @if (($active_submenu ?? null) == 'config.langs') active @endif">
                            <a href="{{ route('admin.config.langs') }}">{{ __('Languages & Locale') }}</a>
                        </li>
                        @endif

                        @if(logged_user()->role == 'admin')       
                        <li class="submenu-item @if (($active_submenu ?? null) == 'config.tools') active @endif">
                            <a href="{{ route('admin.tools.update') }}">{{ __('Tools') }}</a>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif

                @include('admin.custom-sidebar')

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
<!-- End Sidebar -->
