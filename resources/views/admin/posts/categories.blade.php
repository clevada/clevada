<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">{{ __('Posts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts.categ') }}">{{ __('Categories') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 col-sm-12 mb-4">
                @include('admin.posts.includes.menu')
            </div>

            <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('All categories') }} ({{ $count_categories ?? 0 }})</h4>
            </div>

            <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    @can('create', App\Models\PostCateg::class)
                        <span class="float-end"><button data-bs-toggle="modal" data-bs-target="#create-categ" class="btn btn-primary"><i class="fas fa-plus-square"></i> {{ __('Create category') }}</button></span>
                        @include('admin.posts.modals.create-categ')
                    @endcan
                </div>
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
                @if ($message == 'created')
                    {{ __('Created') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. This category with this URL structure exist') }}
                @endif
                @if ($message == 'length')
                    {{ __('Error. Slug length must be minimum 3 characters') }}
                @endif
                @if ($message == 'create_post_no_categ')
                    {{ __('Error. To create a new article, you must assign it to a category. Please create a category first.') }}
                @endif
            </div>
        @endif

        
        <div class="table-responsive-md">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Details') }}</th>
                        <th width="150">{{ __('Statistics') }}</th>                       
                        <th width="160">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($categories as $categ)
                        @include('admin.posts.loops.categories-loop', $categ)
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <!-- end card-body -->

</div>
