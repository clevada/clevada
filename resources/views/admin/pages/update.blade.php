<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">{{ __('Pages') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Update') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12 mb-3">
                @include('admin.pages.includes.menu-page')
            </div>

            <div class="col-12">
                <div class="card-title">{{ __('Page details') }}</div>
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

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif

        @if ($page->deleted_at)
            <div class="text-danger fw-bold mb-2">
                {{ __('This item is in the Trash.') }}
            </div>
        @endif

        @if ($page->active == 0)
            <div class="fw-bold text-danger mb-2">
                <i class="bi bi-exclamation-circle"></i> {{ __('Page is not published.') }}
            </div>
        @endif

        <form method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card p-3 bg-light mb-4 pb-1">

                @if ($page->is_homepage == 1)
                    <div class="fw-bold text-success"><i class="bi bi-info-circle"></i> {{ __('This page is main website homepage.') }}</div>
                    <hr>
                @endif

                @if (!($page->is_homepage == 1))
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="form-group mb-3">
                                <label>{{ __('Parent page') }}</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">- {{ __('No parent') }} -</option>
                                    @foreach ($root_pages as $root_page)
                                        @if (!$root_page->is_homepage)
                                            @if ($page->id != $root_page->id)
                                                <option @if ($page->parent_id == $root_page->id) selected @endif value="{{ $root_page->page_id }}">
                                                    {{ $root_page->title }}
                                                </option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif                
                
                @if ($page->is_homepage == 1)
                    <input type="hidden" name="active" checked>
                @else
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch" name="active" @if ($page->active == 1) checked @endif>
                                    <label class="form-check-label" for="customSwitch">{{ __('Published') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>                   
                @endif
            </div>


            @if ($page->is_homepage == 1)
                <div class="form-group">
                    <label>{{ __('Website meta title') }}</label>
                    <input type="text" class="form-control" name="meta_title" value="{{ $page->meta_title ?? 'NuraPress site' }}" maxlength="200">
                    <div class="text-muted small">{{ __('This is used as site meta title. Recomended: maximum 60 characters') }}</div>
                </div>

                <div class="form-group">
                    <label>{{ __('Website meta description') }}</label>
                    <input type="text" class="form-control" name="meta_description" value="{{ $page->meta_description ?? 'NuraPress site' }}">
                    <div class="text-muted small">{{ __('Include relevant information about the website content in the description.') }}</div>
                </div>
            @else
                <div class="form-group">
                    <label>{{ __('Page title') }}</label>
                    <input class="form-control" name="title" type="text" value="{{ $page->title ?? null }}" required>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Custom Meta title') }} ({{ __('optional') }})</label>
                            <input type="text" class="form-control" name="meta_title" value="{{ $page->meta_title ?? null }}">
                            <div class="form-text text-muted small">{{ __('If not set, page title will be used as meta title') }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Custom Meta description') }} ({{ __('optional') }})</label>
                            <input type="text" class="form-control" name="meta_description" value="{{ $page->meta_description ?? null }}">
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Custom permalink') }} ({{ __('optional') }})</label>
                            <input type="text" class="form-control customSlug" name="slug" value="{{ $page->slug ?? null }}">
                            <div class="form-text text-muted small">{{ __('Leave empty to generate automatically from page title') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$page->deleted_at)
                @can('update', $page)
                    <div class="form-group">
                        <button type="submit" name="redirect" value="pages" class="btn btn-primary">{{ __('Update') }}</button>

                        <button type="submit" name="redirect" value="return" class="btn btn-primary">{{ __('Update and return here') }}</button>

                        <button type="submit" name="redirect" value="content" class="btn btn-primary">{{ __('Update and go to content') }}</button>
                    </div>
                @endcan
            @endif

        </form>

    </div>
    <!-- end card-body -->

</div>
