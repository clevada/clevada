<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if (($nav_menu ?? null) == 'details') active @endif" href="{{ route('admin.pages.show', ['id' => $page->id]) }}"><i class="bi bi-gear"></i> {{ __('Settings') }}</a>    
    <a class="nav-item nav-link @if (($nav_menu ?? null) == 'content') active @endif" href="{{ route('admin.pages.content', ['id' => $page->id]) }}"><i class="bi bi-card-text"></i> {{ __('Page content') }}</a>    
</nav>
