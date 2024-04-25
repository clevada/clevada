<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Configuration') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-2">
                @include('admin.core.layouts.menu-config')
            </div>

        </div>

    </div>


    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        <div class="fw-bold fs-5">{{ __('Sitemap') }}</div>

        {{ __('Sitemap last update') }}:
        @if ($config->sitemap_last_update_at ?? null)
            {{ date_locale($config->sitemap_last_update_at, 'datetime') }}
            <br>
            {{ __('Sitemap location') }}: <b>{{ route('home') }}/sitemap.xml</b>
        @else
            <span class="text-danger">{{ __('never') }}</span>
        @endif

        <div class="mt-2">
            <a class="btn btn-gear" href="{{ route('admin.sitemap.generate') }}">{{ __('Manually update sitemap') }}</a>
        </div>

        <div class="form-text">{{ __('Note: Sitemap is automatically regenerated when you create or delete content (pages, posts and categories).') }}</div>

        <div class="fw-bold fs-5 mt-3">{{ __('Permalinks') }}</div>

        @php
            $permalinks = unserialize($config->permalinks ?? null);
        @endphp


        {{ __('Contact page permalink') }}: <b>{{ $permalinks['contact'] ?? 'contact' }}</b>
        <div class="text-muted small"><a target="_blank" href="{{ route('home') }}/{{ $permalinks['contact'] ?? 'contact' }}">{{ route('home') }}/<b>{{ $permalinks['contact'] ?? 'contact' }}</b></a></div>
        <div class="mb-3"></div>

        {{ __('Posts tags permalink') }}: <b>{{ $permalinks['tag'] ?? 'tag' }}</b>
        <div class="text-muted small"><a target="_blank" href="{{ route('home') }}/{{ $permalinks['tag'] ?? 'tag' }}">{{ route('home') }}/<b>{{ $permalinks['tag'] ?? 'tag' }}</b>/example-tag</a></div>
        <div class="mb-3"></div>

        {{ __('Search permalink') }}: <b>{{ $permalinks['search'] ?? 'search' }}</b>
        <div class="text-muted small"><a target="_blank" href="{{ route('home') }}/{{ $permalinks['search'] ?? 'search' }}">{{ route('home') }}/<b>{{ $permalinks['search'] ?? 'search' }}</b>/example-search</a></div>
        <div class="mb-3"></div>

        {{ __('Author profile page permalink') }}: <b>{{ $permalinks['profile'] ?? 'profile' }}</b>
        <div class="text-muted small"><a href="#">{{ route('home') }}/<b>{{ $permalinks['profile'] ?? 'profile' }}</b>/username</a></div>
        <div class="mb-3"></div>

        <button data-bs-toggle="modal" data-bs-target="#update-permalinks" class="btn btn-primary btn-sm">{{ __('Update permalinks') }}</button>
        @include('admin.core.modals.update-permalinks')


    </div>
    <!-- end card-body -->

</div>
