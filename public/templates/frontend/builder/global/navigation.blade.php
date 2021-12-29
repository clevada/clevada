@if ($alert_preview_template)
    <div class="navbar_alert_preview_template py-2">
        <div class="container-xxl text-center">
            <i class="bi bi-exclamation-triangle"></i> {{ __('Warning! You preview a template that is different than default template') }}. <a href="{{ route('homepage', ['preview_template_id' => get_default_template_id()]) }}">{{ __('Reset to default template') }}</a> 
        </div>
    </div>
@endif


@if (template('navbar3_show'))
    <div class="navbar3 py-2 @if (template('navbar3_sticky')) sticky-top @endif">
        <div class="container-xxl">
            <div class="{{ template('navbar3_content_align') }}">{!! template('navbar3_content') !!}</div>
        </div>
    </div>
@endif

@if (template('navbar2_show'))
    @if (template('navbar2_layout') == 'logo_left' || template('navbar2_layout') == 'logo_right' || template('navbar2_layout') == 'logo_center')
        @include("{$template_view}.global.navbar2-layouts.logo-only")
    @endif

    @if (template('navbar2_layout') == 'logo_extra')
        @include("{$template_view}.global.navbar2-layouts.logo-extra")
    @endif

    @if (template('navbar2_layout') == 'extra_logo')
        @include("{$template_view}.global.navbar2-layouts.extra-logo")
    @endif

    @if (template('navbar2_layout') == 'logo_search')
        @include("{$template_view}.global.navbar2-layouts.logo-search")
    @endif

    @if (template('navbar2_layout') == 'search_extra')
        @include("{$template_view}.global.navbar2-layouts.search-extra")
    @endif

    @if (template('navbar2_layout') == 'extra_left' || template('navbar2_layout') == 'extra_right')
        @include("{$template_view}.global.navbar2-layouts.extra-only")
    @endif

    @if (template('navbar2_layout') == 'logo_search_extra')
        @include("{$template_view}.global.navbar2-layouts.logo-search-extra")
    @endif
@endif

<nav class="navbar navbar-expand-lg @if (template('navbar_sticky')) sticky-top @endif">
    <div class="container-xxl">

        @if (!template('navbar_hide_logo'))
            <a class="navbar-brand" title="{{ site()->title }}" href="{{ site()->url }}">@if ($config->logo)<img src="{{ image($config->logo ?? null) }}" alt="{{ site()->title }}">@endif</a>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar1" aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="bi bi-list" style="font-size: 2rem; color: {{ template('navbar', 'font_color') ?? config('defaults.nav_font_color') }}"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav {{ template('menu_links_align') ?? 'ms-auto' }}">

                @if ($menu_links)
                    @foreach ($menu_links as $navbar_link)
                        @if (!empty($navbar_link->dropdown))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_{{ $navbar_link->label }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $navbar_link->label }} <i class="bi bi-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown_{{ $navbar_link->label }}">
                                    @foreach ($navbar_link->dropdown as $navbar_dropdown_link)
                                        <li><a class="dropdown-item" href="{{ $navbar_dropdown_link->url }}">{{ $navbar_dropdown_link->label }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $navbar_link->url }}" title="{{ site()->title }}">{{ $navbar_link->label }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif

                @if (! template('navbar_hide_auth'))                            
                    @include("{$template_view}.global.nav-auth")                    
                @endif

                @if (count(languages()) > 1 && !template('navbar_hide_langs'))
                    @include("{$template_view}.global.nav-langs")
                @endif

            </ul>

            @if (template('navbar', 'navbar_searchform') == 'form')
                <form class="d-flex float-right">
                    <input class="form-control me-2" type="search" placeholder="{{ __('Search') }}" aria-label="Search">
                </form>
            @endif

        </div>
    </div>
</nav>

@include("{$template_view}.layouts.top")
