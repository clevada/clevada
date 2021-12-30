<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.tools.sitemap') }}">{{ __('Sitemap') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 mb-3">
                    @include('admin.core.layouts.menu-tools')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Sitemap') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Sitemap updated') }} @endif
                </div>
            @endif

            {{ __('Sitemap URL') }}: <b>{{ config('app.url') }}/sitemap.xml</b>

            <div class="mb-2"></div>
            <small>{{ __('Sitemap server location') }}: {{ public_path('sitemap.xml') }}</small>

            <hr>

            <form method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary">{{ __('Regenerate XML sitemap file') }}</button>
            </form>
        </div>
        <!-- end card-body -->

    </div>

</section>
