<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($menu_section == 'config.general') active @endif" href="{{ route('admin.posts.config') }}"><i class="bi bi-gear" aria-hidden="true"></i> {{ __('Posts config') }}</a>
    <a class="nav-item nav-link @if ($menu_section == 'config.seo') active @endif" href="{{ route('admin.posts.config.seo') }}"><i class="bi bi-globe" aria-hidden="true"></i> {{ __('SEO') }}</a>    
</nav>