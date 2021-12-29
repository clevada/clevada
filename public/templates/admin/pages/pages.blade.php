<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.pages') }}">{{ __('Pages') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Pages') }} ({{ $pages->total() ?? 0 }})</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    @if (check_access('pages'))
                        <div class="float-end">
                            <a class="btn btn-primary" href="{{ route('admin.pages.create') }}"><i class="bi bi-plus-circle"></i> {{ __('New page') }}</a>
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

            <section class="mb-3">
                <form action="{{ route('admin.pages') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="{{ __('Search page label') }}" class="form-control @if ($search_terms) is-valid @endif me-2" value="{{ $search_terms ?? null }}" />
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light" href="{{ route('admin.pages') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>

                </form>
            </section>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="320">{{ __('Page details') }}</th>
                            <th>{{ __('Content details') }}</th>
                            <th width="160">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pages as $page)

                            <tr @if ($page->active == 0) class="table-warning" @endif>

                                <td>

                                    <b>{{ $page->label }}</b>
                                    <div class="mb-1"></div>

                                    <small class='text-muted'>
                                        <b>{{ __('ID') }}</b>: {{ $page->id }}

                                        <div class="clearfix"></div>

                                        <b>{{ __('Created') }}</b>: {{ date_locale($page->created_at, 'datetime') }}

                                        @if ($page->updated_at) <br><b>{{ __('Updated') }}</b>: {{ date_locale($page->updated_at, 'datetime') }}@endif

                                        <div class="clearfix"></div>

                                        <b>{{ __('Sidebar') }}</b>:
                                        @if (!$page->sidebar_id) {{ __('No sidebar') }} @else
                                            <a target="_blank" href="{{ route('admin.template.sidebars.show', ['id' => $page->sidebar_id]) }}">
                                                @if ($page->sidebar_position == 'right') {{ $page->sidebar_label }} ({{ __('right') }}) @endif
                                                @if ($page->sidebar_position == 'left')  {{ $page->sidebar_label }} ({{ __('left') }})@endif
                                            </a>
                                        @endif

                                        <div class="clearfix"></div>

                                        <b>{{ __('Top section') }}</b>:
                                        @if (!$page->top_section_id) {{ __('No content') }} @else
                                            <a target="_blank" href="{{ route('admin.template.global_sections.show', ['id' => $page->top_section_id]) }}">
                                                {{ $page->top_section_label }}                                            
                                            </a>
                                        @endif

                                        <div class="clearfix"></div>

                                        <b>{{ __('Bottom section') }}</b>:
                                        @if (!$page->bottom_section_id) {{ __('No content') }} @else
                                            <a target="_blank" href="{{ route('admin.template.global_sections.show', ['id' => $page->bottom_section_id]) }}">
                                                {{ $page->bottom_section_label }}                                            
                                            </a>
                                        @endif

                                        <div class="clearfix"></div>

                                        @if ($page->user_id)
                                            <b>{{ __('Author') }}</b> <a target="_blank" href="{{ route('admin.accounts.show', ['id' => $page->user_id]) }}">{{ $page->author_name }}</a>
                                            <div class="clearfix"></div>
                                        @endif

                                        @if ($page->parent_id)
                                            <b>{{ __('Parent page') }}</b> <a target="_blank" href="{{ page($page->parent_id)->url }}">{{ $page->parent_label }}</a>
                                            <div class="clearfix"></div>
                                        @endif

                                    </small>
                                </td>

                                <td>
                                    @if ($page->active == 0)
                                        <span class="float-end ms-2"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Draft') }}</button></span>
                                    @endif

                                    @foreach (page_contents($page->id) as $content)

                                        @if (count(sys_langs()) > 1)
                                            {!! flag($content->lang_code) !!}
                                        @endif

                                        @if ($page->active == 1)<a target="_blank" href="{{ page($page->id, $content->lang_id)->url }}"><b>{{ $content->title }}</b></a>
                                        @else <b>{{ $content->title }}</b>@endif
                                        <div class="mb-2"></div>

                                    @endforeach
                                </td>

                                <td>
                                    <div class="d-grid gap-2">

                                        <a href="{{ route('admin.pages.content', ['id' => $page->id]) }}" class="btn btn-primary btn-sm mb-2">{{ __('Page content') }}</a>

                                        <a href="{{ route('admin.pages.show', ['id' => $page->id]) }}" class="btn btn-secondary btn-sm mb-2">{{ __('Update page') }}</a>

                                        @if (check_access('pages', 'manager'))
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $page->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                            <div class="modal fade confirm-{{ $page->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this page?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.pages.show', ['id' => $page->id]) }}">
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

            {{ $pages->appends(['search_terms' => $search_terms, 'search_lang_id' => $search_lang_id])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
