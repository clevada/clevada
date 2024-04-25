<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.recycle_bin') }}">{{ __('Recycle Bin') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-body">

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif


        <div class="row">

            <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card-box noradius noborder bg-light">
                    <i class="bi bi-people float-end"></i>
                    <h6 class="text-uppercase fw-bold mb-4">{{ __('Accounts') }}</h6>
                    <div class="mb-3 fs-6 @if($rbAccountsCount > 0) fw-bold text-danger @endif">{{ $rbAccountsCount ?? 0 }} {{ __('accounts deleted') }}</div>
                    <a class="btn btn-gear" href="{{ route('admin.recycle_bin.module', ['module' => 'accounts']) }}">{{ __('View deleted accounts') }}</a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card-box noradius noborder bg-light">
                    <i class="bi bi-textarea-resize float-end"></i>
                    <h6 class="text-uppercase fw-bold mb-4">{{ __('Contact messages') }}</h6>
                    <div class="mb-3 fs-6 @if($rbFormsCount > 0) fw-bold text-danger @endif">{{ $rbFormsCount ?? 0 }} {{ __('messages deleted') }}</div>
                    <a class="btn btn-gear" href="{{ route('admin.recycle_bin.module', ['module' => 'contact']) }}">{{ __('View deleted messages') }}</a>
                </div>
            </div>          

            <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card-box noradius noborder bg-light">
                    <i class="bi bi-file-text float-end"></i>
                    <h6 class="text-uppercase fw-bold mb-4">{{ __('Posts') }}</h6>
                    <div class="mb-3 fs-6 @if($rbPostsCount > 0) fw-bold text-danger @endif">{{ $rbPostsCount ?? 0 }} {{ __('posts deleted') }}</div>
                    <a class="btn btn-gear" href="{{ route('admin.recycle_bin.module', ['module' => 'posts']) }}">{{ __('View deleted posts') }}</a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card-box noradius noborder bg-light">
                    <i class="bi bi-file-text float-end"></i>
                    <h6 class="text-uppercase fw-bold mb-4">{{ __('Pages') }}</h6>
                    <div class="mb-3 fs-6 @if($rbPagesCount > 0) fw-bold text-danger @endif">{{ $rbPagesCount ?? 0 }} {{ __('pages deleted') }}</div>
                    <a class="btn btn-gear" href="{{ route('admin.recycle_bin.module', ['module' => 'pages']) }}">{{ __('View deleted pages') }}</a>
                </div>
            </div>
        </div>
        <!-- end row -->



    </div>
    <!-- end card-body -->

</div>
