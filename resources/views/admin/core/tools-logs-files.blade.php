<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.tools.logs-files') }}">{{ __('Logs files') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12">
                    @include('admin.core.layouts.menu-tools')
                </div>

            </div>

        </div>


        <div class="card-body">         

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif

            @foreach (array_reverse($files) as $file)
            <a href="{{ route('admin.tools.logs-file', ['file' => $file]) }}">{{ $file }}</a>
            <div class="mb-1"></div>
            @endforeach


        </div>
        <!-- end card-body -->

    </div>

</section>
