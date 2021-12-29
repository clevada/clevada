<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($menu_section=='config.general') active @endif" href="{{ route('admin.forum.config') }}"><i class="bi bi-gear" aria-hidden="true"></i> {{ __('Forum config') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='config.seo') active @endif" href="{{ route('admin.forum.config.seo') }}"><i class="bi bi-globe" aria-hidden="true"></i> {{ __('SEO') }}</a>
</nav>