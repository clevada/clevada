<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($module=='global') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id]) }}">{{ __('Global') }}</a>
    <a class="nav-item nav-link @if ($module=='navbar') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'navbar']) }}">{{ __('Navigation') }}</a>
    <a class="nav-item nav-link @if ($module=='footer') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'footer']) }}">{{ __('Footer') }}</a>
    <a class="nav-item nav-link @if ($module=='homepage') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'homepage']) }}">{{ __('Homepage') }}</a>
    <a class="nav-item nav-link @if ($module=='usersarea') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'usersarea']) }}">{{ __('Users area') }}</a>
    @if(check_admin_module('posts'))<a class="nav-item nav-link @if ($module=='posts') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'posts']) }}">{{ __('Posts') }}</a>@endif
    @if(check_admin_module('cart'))<a class="nav-item nav-link @if ($module=='cart') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'cart']) }}">{{ __('eCommerce') }}</a>@endif
    @if(check_admin_module('forum'))<a class="nav-item nav-link @if ($module=='forum') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'forum']) }}">{{ __('Community') }}</a>@endif
    @if(check_admin_module('docs'))<a class="nav-item nav-link @if ($module=='docs') active @endif" href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'docs']) }}">{{ __('Knowledge Base') }}</a>@endif   
</nav>