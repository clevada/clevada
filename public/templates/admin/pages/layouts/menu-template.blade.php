<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($menu_section=='template') active @endif" href="{{ route('admin.config.template.module', ['module' => 'global']) }}"><i class="bi bi-laptop"></i> {{ __('Template') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='menus') active @endif" href="{{ route('admin.config.template.menu') }}"><i class="bi bi-menu-down"></i> {{ __('Menu links') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='logo') active @endif" href="{{ route('admin.config.template.logo') }}"><i class="bi bi-image"></i> {{ __('Logo and icons') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='custom_code') active @endif" href="{{ route('admin.config.template.custom_code') }}"><i class="bi bi-code-square"></i> {{ __('Custom code') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='sitemap') active @endif" href="{{ route('admin.config.template.sitemap') }}"><i class="bi bi-diagram-3"></i> {{ __('Sitemap') }}</a>
</nav>