<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.docs') }}">{{ __('Knowledge Base') }}</a></li>
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
                    <h4 class="card-title">{{ __('Knowledge Base') }} ({{ $docs->total() ?? 0 }} {{ __('articles') }})</h4>
                </div>

                <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                    @if (check_access('posts'))
                        <div class="float-end">
                            @if (check_access('docs'))
                                <a href="{{ route('admin.docs.create') }}" class="btn btn-primary me-1 mb-1"><i class="bi bi-plus-circle"></i> {{ __('New article') }}</a>
                            @endif

                            @if (logged_user()->role == 'admin')
                                <a href="{{ route('admin.docs.categ') }}" class="btn btn-secondary me-1 mb-1"><i class="bi bi-diagram-3"></i> {{ __('Categories') }}</a>
                                <a href="{{ route('admin.docs.seo') }}" class="btn btn-secondary me-1 mb-1"><i class="bi bi-gear"></i> {{ __('Settings') }}</a>
                                <a href="{{ route('admin.templates.show', ['id' => $template->id, 'module' => 'docs']) }}" class="btn btn-secondary me-1 mb-1"><i class="bi bi-laptop"></i>
                                    {{ __('Template') }}</a>
                            @endif
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

            <section>
                <form action="{{ route('admin.docs') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif" placeholder="{{ __('Search in articles') }}" name="search_terms" value="{{ $search_terms }}">
                    </div>

                    <div class="col-12">
                        <select class="form-select me-2 mb-2 @if ($search_categ_id) is-valid @endif" name="search_categ_id">
                            <option selected="selected" value="">- {{ __('All categories') }} -</option>
                            @foreach ($categories as $categ)
                                @include('admin.docs.loops.posts-filter-categories-loop', $categ)
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light mb-2" href="{{ route('admin.docs') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </section>
            <div class="mb-3"></div>


            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="320">{{ __('Details') }}</th>
                            <th>{{ __('Content details') }}</th>
                            <th width="180">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($docs as $doc)
                            <tr @if ($doc->active == 0) class="table-warning" @endif>
                                <td>
                                    <b>{{ $doc->label }}</b>
                                    <div class="mb-1"></div>

                                    <small class='text-muted'>
                                        <b>{{ __('ID') }}</b>: {{ $doc->id }}

                                        <div class="clearfix"></div>

                                        {{ __('Position') }}: {{ $doc->position }}

                                        <div class="clearfix"></div>

                                        <b>{{ __('Created') }}</b>: {{ date_locale($doc->created_at, 'datetime') }}

                                        <div class="clearfix"></div>

                                        @if ($doc->user_id)
                                            <b>{{ __('Author') }}</b> <a target="_blank" href="{{ route('admin.accounts.show', ['id' => $doc->user_id]) }}">{{ $doc->author_name }}</a>
                                            <div class="clearfix"></div>
                                        @endif
                                    </small>
                                </td>

                                <td>
                                    @if ($doc->featured == 1)
                                        <span class="float-end"><button type="button" class="btn btn-success btn-sm disabled">{{ __('Featured') }}</button></span>
                                    @endif

                                    @if ($doc->active == 0)
                                        <span class="float-end"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Draft') }}</button></span>
                                    @endif

                                    @if ($doc->visibility == 'private')
                                        <span class="float-end"><button type="button" class="btn btn-info btn-sm disabled">{{ __('Private') }}</button></span>
                                    @endif

                                    @foreach (doc_contents($doc->id) as $content)

                                        @if (count(sys_langs()) > 1)
                                            {!! flag($content->lang_code) !!}
                                        @endif

                                        @if ($doc->active == 1)<a target="_blank" href="{{ doc($doc->id, $content->lang_id)->url }}"><b>{{ $content->title }}</b></a>
                                        @else <b>{{ $content->title }}</b>@endif
                                        <div class="mb-2"></div>

                                    @endforeach
                                </td>

                                <td>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.docs.content', ['id' => $doc->id]) }}" class="btn btn-primary btn-sm mb-2">{{ __('Article content') }}</a>

                                        <a href="{{ route('admin.docs.show', ['id' => $doc->id]) }}" class="btn btn-secondary btn-block btn-sm mb-2">{{ __('Update article') }}</a>

                                        @if (check_access('docs', 'manager'))
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $doc->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                            <div class="modal fade confirm-{{ $doc->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this article?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.docs.show', ['id' => $doc->id]) }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                                <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $docs->appends(['search_categ_id' => $search_categ_id, 'search_terms' => $search_terms])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
