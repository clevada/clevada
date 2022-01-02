<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.tools.backup') }}">{{ __('Backup') }}</a></li>
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
                    <h4 class="card-title">{{ __('Backup') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

			@if ($config->last_backup ?? null)
			<div class="small mb-3">{{ __('Latest backup') }}: {{ date_locale($config->last_backup, 'datetime') }}</div>
			@endif
			
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Done') }} @endif
                </div>
            @endif
			
            <form method="POST">
                {{ csrf_field() }}
                <input type='hidden' name='option' value='db'>
                <button type="submit" class="btn btn-primary">{{ __('Backup database only') }}</button>
            </form>

            <div class="mb-3"></div>

            <form method="POST">
                {{ csrf_field() }}
                <input type='hidden' name='option' value='full'>
                <button type="submit" class="btn btn-primary">{{ __('Full backup (database and files)') }}</button>
            </form>


            <hr>

            @php
                $path = storage_path() . '/backups/clevada';
                $files = glob($path . '/*.zip');
            @endphp

            <h5>{{ __('Backup files are located in') }}: {{ $path }}</h5>
            <div class="mb-2">{{ __('Over time the number of backups and the storage required to store them will grow. At some point you will want to clean up old backups') }}.</div>
            <div class="mb-2 font-weight-bold">{{ __('You have') }} {{ count($files) }} {{ __('backup files') }}. </div>
            @if (count($files) > 50)<div class='text-danger font-weight-bold'>{{ __('Please delete old files to save disk space') }}</div>@endif
            <div class="mb-3"></div>

            @php
                foreach ($files as $file) {
                    echo $file;
                    echo '<div class="mb-2"></div>';
                }
            @endphp

        </div>
        <!-- end card-body -->

    </div>

</section>
