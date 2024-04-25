<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if ($menu_section=='tools.update') active @endif" href="{{ route('admin.tools.update') }}"><i class="bi bi-download"></i> {{ __('Software update') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='tools.backup') active @endif" href="{{ route('admin.tools.backup') }}"><i class="bi bi-cloud-download"></i> {{ __('Backup') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='tools.system') active @endif" href="{{ route('admin.tools.system') }}"><i class="bi bi-gear"></i> {{ __('System') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='tools.sitemap') active @endif" href="{{ route('admin.tools.sitemap') }}"><i class="bi bi-diagram-3"></i> {{ __('Sitemap') }}</a>
    <a class="nav-item nav-link @if ($menu_section=='tools.log.email') active @endif" href="{{ route('admin.log.email') }}"><i class="bi bi-send"></i> {{ __('Email log') }}</a>
</nav>