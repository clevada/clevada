<nav class="nav nav-tabs" id="myTab" role="tablist">
<a class="nav-item nav-link @if ($active_tab=='general') active @endif" href="{{ route('admin.config.general') }}"><i class="bi bi-gear"></i> {{ __('General') }}</a>
<a class="nav-item nav-link @if ($active_tab=='registration') active @endif" href="{{ route('admin.config.registration') }}"><i class="bi bi-person-fill"></i> {{ __('Registration') }}</a>
<a class="nav-item nav-link @if ($active_tab=='integration') active @endif" href="{{ route('admin.config.integration') }}"><i class="bi bi-gear"></i> {{ __('Integration') }}</a>
<a class="nav-item nav-link @if ($active_tab=='email') active @endif" href="{{ route('admin.config.email') }}"><i class="bi bi-envelope"></i> {{ __('Mail') }}</a>
<a class="nav-item nav-link @if ($active_tab=='icons') active @endif" href="{{ route('admin.config.icons') }}"><i class="bi bi-star"></i> {{ __('Icons') }}</a>
<a class="nav-item nav-link @if ($active_tab=='site-offline') active @endif" href="{{ route('admin.config.site_offline') }}"><i class="bi bi-stopwatch"></i> {{ __('Site offline') }}</a>
</nav>