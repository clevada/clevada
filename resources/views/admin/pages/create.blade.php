<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">{{ __('Pages') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('New page') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <h4 class="card-title">{{ __('New page') }}</h4>
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. Page with this slug already exists') }}
                @endif
                @if ($message == 'length2')
                    {{ __('Error. Page slug must be minimum 3 characters') }}
                @endif
            </div>
        @endif

        <form method="post" action="{{ route('admin.pages.index') }}" enctype="multipart/form-data">
            @csrf

            <div class="card p-3 bg-light mb-4">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="form-group">
                            <label>{{ __('Parent page') }}</label>
                            <select name="parent_id" class="form-select">
                                <option value="">- {{ __('No parent') }} -</option>
                                @foreach ($root_pages as $root_page)
                                    @if (!$root_page->is_homepage)
                                        <option value="{{ $root_page->id }}">{{ $root_page->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>                   
                </div>
            </div>


            <div class="form-group">
                <label>{{ __('Page Title') }}</label>
                <input class="form-control" name="title" type="text" required>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Custom Meta Title') }} ({{ __('optional') }})</label>
                        <input type="text" class="form-control" name="meta_title">
                        <div class="form-text text-muted small">{{ __('If not set, page title will be used as meta title') }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Custom Meta Description') }} ({{ __('optional') }})</label>
                        <input type="text" class="form-control" name="meta_description">
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Custom Permalink') }} ({{ __('optional') }})</label>
                        <input type="text" class="form-control customSlug" name="slug">
                        <div class="form-text text-muted small">{{ __('Leave empty to generate automatically from page title') }}</div>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">{{ __('Create page') }}</button>

            <p class="form-text mt-3"><i class="bi bi-info-circle"></i> {{ __('You can add content blocks for this page at the next step') }}</p>

        </form>

    </div>
    <!-- end card-body -->

</div>