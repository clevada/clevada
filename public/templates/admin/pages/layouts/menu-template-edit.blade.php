<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($module=='global') active @endif" href="{{ route('admin.config.template.module', ['module' => 'global']) }}">{{ __('Global') }}</a>
    <a class="nav-item nav-link @if ($module=='navbar') active @endif" href="{{ route('admin.config.template.module', ['module' => 'navbar']) }}">{{ __('Navigation') }}</a>
    <a class="nav-item nav-link @if ($module=='footer') active @endif" href="{{ route('admin.config.template.module', ['module' => 'footer']) }}">{{ __('Footer') }}</a>
    <a class="nav-item nav-link @if ($module=='homepage') active @endif" href="{{ route('admin.config.template.module', ['module' => 'homepage']) }}">{{ __('Homepage') }}</a>
    @if(check_admin_module('posts'))<a class="nav-item nav-link @if ($module=='blog') active @endif" href="{{ route('admin.config.template.module', ['module' => 'blog']) }}">{{ __('Blog / Articles') }}</a>@endif
    @if(check_admin_module('cart'))<a class="nav-item nav-link @if ($module=='cart') active @endif" href="{{ route('admin.config.template.module', ['module' => 'cart']) }}">{{ __('eCommerce') }}</a>@endif
    @if(check_admin_module('forum'))<a class="nav-item nav-link @if ($module=='forum') active @endif" href="{{ route('admin.config.template.module', ['module' => 'forum']) }}">{{ __('Community') }}</a>@endif
    @if(check_admin_module('docs'))<a class="nav-item nav-link @if ($module=='docs') active @endif" href="{{ route('admin.config.template.module', ['module' => 'docs']) }}">{{ __('Knowledge Base') }}</a>@endif
    @if(check_admin_module('faq'))<a class="nav-item nav-link @if ($module=='faq') active @endif" href="{{ route('admin.config.template.module', ['module' => 'faq']) }}">{{ __('FAQ') }}</a>@endif
</nav>