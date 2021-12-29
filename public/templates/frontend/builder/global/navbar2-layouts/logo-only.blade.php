    <nav class="navbar navbar2">
        <div class="container-xxl ">

            @if(template('navbar2_layout') == 'logo_left')
            <div class="me-auto">
                <a title="{{ site()->title }}" href="{{ site()->url }}">@if ($config->logo)<img class="img-fluid align-items-center" src="{{ image($config->logo ?? null) }}" alt="{{ site()->title }}">@endif</a>
            </div>

            @elseif(template('navbar2_layout') == 'logo_right')
            <div class="ms-auto">                
                <a title="{{ site()->title }}" href="{{ site()->url }}">@if ($config->logo)<img class="img-fluid align-items-center" src="{{ image($config->logo ?? null) }}" alt="{{ site()->title }}">@endif</a>
            </div>

            @else
            <div class="mx-auto float-end">                
                <a title="{{ site()->title }}" href="{{ site()->url }}">@if ($config->logo)<img class="img-fluid align-items-center" src="{{ image($config->logo ?? null) }}" alt="{{ site()->title }}">@endif</a>
            </div>
            @endif          

        </div>
    </nav>
