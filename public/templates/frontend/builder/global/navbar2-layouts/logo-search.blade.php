    <nav class="navbar navbar-expand navbar2">
        <div class="container-xxl">

            <div class="me-auto">
                <a class="navbar-brand" title="{{ site()->title }}" href="{{ site()->url }}">@if ($config->logo)<img class="img-fluid align-items-center" src="{{ image($config->logo ?? null) }}" alt="{{ site()->title }}">@endif</a>
            </div>

            <ul class="navbar-nav">
                
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
            </ul>

        </div>
    </nav>
