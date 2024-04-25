<nav class="nav nav-tabs mb-2" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($active_tab == 'general') active @endif" href="{{ route('admin.config', ['module' => 'general']) }}"><i class="bi bi-gear"></i> {{ __('Website') }}</a>
    <a class="nav-item nav-link @if ($active_tab == 'registration') active @endif" href="{{ route('admin.config', ['module' => 'registration']) }}"><i class="bi bi-person-lock"></i> {{ __('Registration') }}</a>
    <a class="nav-item nav-link @if ($active_tab == 'integration') active @endif" href="{{ route('admin.config', ['module' => 'integration']) }}"><i class="bi bi-arrow-right-square"></i> {{ __('Integration') }}</a>
    <a class="nav-item nav-link @if ($active_tab == 'seo') active @endif" href="{{ route('admin.config', ['module' => 'seo']) }}"><i class="bi bi-speedometer"></i> {{ __('SEO') }}</a>

    <a class="nav-item nav-link @if ($active_tab == 'icons') active @endif" href="{{ route('admin.config', ['module' => 'icons']) }}"><i class="bi bi-star"></i> {{ __('Icons') }}</a>
    <a class="nav-item nav-link @if ($active_tab == 'whitelabel') active @endif" href="{{ route('admin.config', ['module' => 'whitelabel']) }}"><i class="bi bi-check-square"></i> {{ __('Whitelabel') }}</a>

    {{--<a class="nav-item nav-link @if ($active_tab == 'email') active @endif" href="{{ route('admin.config', ['module' => 'email']) }}"><i class="bi bi-envelope"></i> {{ __('Mail') }}</a>
    <a class="nav-item nav-link @if ($active_tab == 'security') active @endif" href="{{ route('admin.config', ['module' => 'security']) }}"><i class="bi bi-shield-check"></i> {{ __('Security') }}</a>--}}
</nav>
