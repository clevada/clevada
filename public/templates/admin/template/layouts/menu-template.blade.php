<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($menu_section=='template') active @endif" href="{{ route('admin.templates') }}"><i class="bi bi-laptop"></i> {{ __('Templates') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='menus') active @endif" href="{{ route('admin.template.menu') }}"><i class="bi bi-menu-down"></i> {{ __('Menu links') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='logo') active @endif" href="{{ route('admin.template.logo') }}"><i class="bi bi-image"></i> {{ __('Logo and icons') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='sidebars') active @endif" href="{{ route('admin.template.sidebars') }}"><i class="bi bi-layout-sidebar-inset"></i> {{ __('Sidebars') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='global_sections') active @endif" href="{{ route('admin.template.global_sections') }}"><i class="bi bi-distribute-vertical"></i> {{ __('Top / bottom sections') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='custom_code') active @endif" href="{{ route('admin.template.custom_code') }}"><i class="bi bi-code-square"></i> {{ __('Custom code') }}</a>
</nav>