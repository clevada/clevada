<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.tools.update') }}">{{ __('Software update') }}</a></li>
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
                    <h4 class="card-title">{{ __('Software update') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'update_available')
                        <h4>{{ __('A new version is available') }}</h4>
                        <div class='fw-bold mb-3'>{{ __('You MUST make a backup before upgrading. Go to backup tab to generate a backup of your database and files') }}</div>
                        <form method="POST" action="{{ route('admin.tools.update.process') }}">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> {{ __('Update to latest version') }}</button>
                        </form>
                    @endif
                    @if ($message == 'update_not_available') {{ __('Your software already use latest version') }} @endif
                    @if ($message == 'updated') {{ __('Your software was updated to latest version') }} @endif
                </div>
            @endif

            <div class="mb-3">{{ __('Your Clevada software version') }}: <b>{{ $config->clevada_version ?? null }}</b></div>

            <form method="POST" action="{{ route('admin.tools.update.check') }}">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> {{ __('Check for update') }}</button>
            </form>

            @if ($config->last_update_check ?? null)
                <div class="small mt-3">{{ __('Latest update check') }}: {{ date_locale($config->last_update_check, 'datetime') }}</div>
            @endif

        </div>
        <!-- end card-body -->

    </div>

</section>
