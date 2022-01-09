<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.docs') }}">{{ __('Knowledge Base') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.docs.categ') }}">{{ __('Categories') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('All categories') }} ({{ $count_categories ?? 0 }})</h4>
                </div>

                <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                    @if (check_access('posts'))
                        <div class="float-end">

                            <span class="float-end"><a class="btn btn-primary" href="{{ route('admin.docs.categ.create') }}"><i class="bi bi-plus-circle"></i>
                                    {{ __('Create category') }}</a></span>                            

                        </div>
                    @endif
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
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. There is another category with this URL structure') }} @endif
                </div>
            @endif

            <div class="table-responsive-md">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="300">{{ __('Details') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th width="160">{{ __('Statistics') }}</th>
                            <th width="160">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($categories as $categ)

                            @include('admin.docs.loops.categories-loop', $categ)

                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $categories->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
