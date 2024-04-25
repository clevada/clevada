<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.tools.system') }}">{{ __('System') }}</a></li>
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
                    <h4 class="card-title">{{ __('System') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Done') }} @endif
                </div>
            @endif

            <h5>{{ __('Clear system cache') }}</h5>

            <a href="{{ route('admin.tools.clear_cache', ['section' => 'views']) }}" class="btn btn-danger">{{ __('Clear template files cache') }}</a>

            <a href="{{ route('admin.tools.clear_cache', ['section' => 'routes']) }}" class="btn btn-danger">{{ __('Clear routes cache') }}</a>

            <a href="{{ route('admin.tools.clear_cache', ['section' => 'config']) }}" class="btn btn-danger">{{ __('Clear config cache') }}</a>

        </div>
        <!-- end card-body -->

    </div>

</section>
