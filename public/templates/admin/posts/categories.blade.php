<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts') }}">{{ __('Blog & Articles') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts.categ') }}">{{ __('Categories') }}</a></li>
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

                            <span class="float-end"><button data-bs-toggle="modal" data-bs-target="#create-categ" class="btn btn-primary"><i class="fas fa-plus-square"></i> {{ __('Create category') }}</button></span>
                            @include('admin.posts.modals.create-categ')

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
                    @if ($message == 'duplicate') {{ __('Error. This category with this URL structure exist') }} @endif
                    @if ($message == 'length') {{ __('Error. Slug length must be minimum 3 characters') }} @endif
                    @if ($message == 'create_post_no_categ') {{ __('Error. You don\'t have any category created. To create a new post, you must assign to a category. Please create a category.') }} @endif
                </div>
            @endif

			@if(count(sys_langs())>1)
			<section class="mb-3">
				<form action="{{ route('admin.posts.categ') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">
		
					<div class="col-12">
					<select name="search_lang_id" class="form-select @if($search_lang_id) is-valid @endif me-2">
						<option selected="selected" value="">- {{ __('Any language') }} -</option>
						@foreach (sys_langs() as $lang)
						<option @if($search_lang_id==$lang->id) selected @endif value="{{ $lang->id }}"> {{ $lang->name }}</option>
						@endforeach
					</select>
					</div>
		
					<div class="col-12">
					<button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
					<a class="btn btn-light" href="{{ route('admin.posts.categ') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
					</div>
				</form>
			</section>
			@endif

            <div class="table-responsive-md">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            <th width="150">{{ __('Statistics') }}</th>
                            @if (count(sys_langs()) > 1)
                                <th width="160">{{ __('Language') }}</th>
                            @endif
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

</section>
