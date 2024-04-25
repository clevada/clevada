<!-- Left Sidebar -->
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo mt-1">
                    @if (($config->clevada_pro_active ?? null) == 0)
                        <a href="{{ route('admin') }}"><img src="{{ config('app.cdn') }}/img/logo.png" class="img-fluid" alt="{{ 'Nura Software' }}"></a>
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

                <li class="sidebar-item @if (($active_menu ?? null) == 'users') active @endif">
                    <a href="{{ route('admin.accounts.index') }}" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>{{ __('Accounts') }}</span>
                    </a>
                </li>

                <li class="sidebar-item @if (($active_menu ?? null) == 'post') active @endif">
                    <a href="{{ route('admin.posts.index', ['post_type' => 'post']) }}" class='sidebar-link'>
                        <i class="bi bi-file-text"></i>
                        <span>{{ __('Posts') }}</span>                       
                    </a>
                </li>

                @foreach($custom_posts_types as $custom_post_type)
                <li class="sidebar-item @if (($active_menu ?? null) == $custom_post_type->type) active @endif">
                    <a href="{{ route('admin.posts.index',['post_type' => $custom_post_type->type]) }}" class='sidebar-link'>
                        <i class="bi bi-file-text"></i>
                        <span>{{ $custom_post_type->name }}</span>                       
                    </a>
                </li>
                @endforeach

                <li class="sidebar-item has-sub @if (($active_menu ?? null) == 'interaction') active @endif">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-chat-square-heart"></i>
                        <span>{{ __('Interaction') }}</span>
                    </a>
                    <ul class="submenu @if (($active_menu ?? null) == 'interaction') active @endif">

                        <li class="submenu-item @if (($active_submenu ?? null) == 'comments') active @endif">
                            <a href="{{ route('admin.posts.comments') }}">{{ __('Comments') }}</a>
                        </li>

                        <li class="submenu-item @if (($active_submenu ?? null) == 'likes') active @endif">
                            <a href="{{ route('admin.posts.likes') }}">{{ __('Likes') }}</a>
                        </li>                       
                    </ul>
                </li>


                <li class="sidebar-item @if (($active_menu ?? null) == 'polls') active @endif">
                    <a href="{{ route('admin.polls.index') }}" class='sidebar-link'>
                        <i class="bi bi-bar-chart"></i>
                        <span>{{ __('Polls') }}</span>
                    </a>
                </li>

                <li class="sidebar-item @if (($active_menu ?? null) == 'ads') active @endif">
                    <a href="{{ route('admin.ads.index') }}" class='sidebar-link'>
                        <i class="bi bi-badge-ad"></i>
                        <span>{{ __('Ads Management') }}</span>
                    </a>
                </li>

                <li class="sidebar-item @if (($active_menu ?? null) == 'contact') active @endif">
                    <a href="{{ route('admin.contact') }}" class='sidebar-link'>
                        <i class="bi bi-textarea-resize"></i>
                        <span>{{ __('Contact messages') }}</span>
                        @if ($count_unread_contact_messages ?? null > 0)
                            <span class="badge bg-danger">{{ $count_unread_contact_messages }}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-item @if (($active_menu ?? null) == 'pages') active @endif">
                    <a href="{{ route('admin.pages.index') }}" class='sidebar-link'>
                        <i class="bi bi-files"></i>
                        <span>{{ __('Pages') }}</span>
                    </a>
                </li>

                <li class="sidebar-item @if (($active_menu ?? null) == 'template') active @endif">
                    <a href="{{ route('admin.templates.index') }}" class='sidebar-link'>
                        <i class="bi bi-laptop"></i>
                        <span>{{ __('Website Template') }}</span>
                    </a>
                </li>

                <li class="sidebar-item @if (($active_menu ?? null) == 'config') active @endif">
                    <a href="{{ route('admin.config', ['module' => 'general']) }}" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>{{ __('Configuration') }}</span>
                    </a>
                </li>
                
                <li class="sidebar-item @if (($active_menu ?? null) == 'recycle_bin') active @endif">
                    <a href="{{ route('admin.recycle_bin') }}" class='sidebar-link'>
                        <i class="bi bi-trash"></i>
                        <span>{{ __('Recycle Bin') }}</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
<!-- End Sidebar -->
