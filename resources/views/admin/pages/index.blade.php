<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.pages.index') }}">{{ __('Pages') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Pages') }} ({{ $pages->total() ?? 0 }})</h4>
            </div>

            <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                @can('create', App\Models\Page::class)
                    <div class="float-end">
                        <a class="btn btn-primary" href="{{ route('admin.pages.create') }}"><i class="bi bi-plus-circle"></i> {{ __('New page') }}</a>
                    </div>
                @endcan
            </div>

        </div>

    </div>


    <div class="card-body">      

        @if ($config->website_maintenance_enabled ?? null)
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __('Website is in maintenance mode.') }} <a href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Change') }}</a>
            </div>
        @endif

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
                    {{ __('Moved to trash') }}
                @endif
            </div>
        @endif

        <section class="mb-3">
            <form action="{{ route('admin.pages.index') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                <div class="col-12">
                    <input type="text" name="search_terms" placeholder="{{ __('Search page') }}" class="form-control @if ($search_terms) is-valid @endif me-2" value="{{ $search_terms ?? null }}" />
                </div>

                <div class="col-12">
                    <button class="btn btn-gear me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                    <a class="btn btn-light" href="{{ route('admin.pages.index') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

            </form>
        </section>

        <div class="table-responsive-md">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Page details') }}</th>
                        <th width="180">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pages as $page)
                        <tr @if ($page->is_homepage == 1 || $page->is_contactpage == 1) class="table-light" @endif @if ($page->active == 0) class="table-warning" @endif>

                            <td>

                                @if ($page->active == 0)
                                    <div class="float-end ms-1 badge bg-warning fw-normal">{{ __('Inactive') }}</div>
                                @endif

                                @if ($page->is_homepage == 1)
                                    <div class="badge bg-success fs-6 fw-normal">{{ __('Website homepage') }} <i class="bi bi-lock"></i></div>
                                @endif

                                @if ($page->is_contactpage == 1)
                                    <div class="badge bg-success fs-6 fw-normal">{{ __('Contact page') }} <i class="bi bi-lock"></i></div>
                                @endif


                                @if (!($page->is_homepage == 1 || $page->is_contactpage == 1))
                                    @if ($page->active == 1)
                                        <a target="_blank" href="{{ $page->url }}">
                                            <span>
                                                @if ($page->parent_id)
                                                    <i class="bi bi-arrow-return-right"></i>
                                                @endif
                                                <b>{{ $page->title }}</b>
                                            </span>
                                        </a>
                                    @else
                                        <span>
                                            @if ($page->parent_id)
                                                <i class="bi bi-arrow-return-right"></i>
                                            @endif <b>{{ $page->title }}</b>
                                        </span>
                                    @endif
                                @endif


                                <div class="mb-1"></div>
                                <small class='text-muted'>
                                    @if ($page->is_homepage == 0)
                                        <b>{{ __('ID') }}</b>: {{ $page->id }} |

                                        <b>{{ __('Created') }}</b>: {{ date_locale($page->created_at, 'datetime') }} |
                                    @endif

                                    <b>{{ __('Hits') }}</b>: {{ $page->hits }}

                                    @if ($page->updated_at)
                                        | <b>{{ __('Updated') }}</b>: {{ date_locale($page->updated_at, 'datetime') }}
                                    @endif

                                    <div class="clearfix"></div>

                                    @if ($page->layout_id)
                                        <b>{{ __('Layout') }}:</b> <a target="_blank" href="{{ route('admin.template.layouts.show', ['id' => $page->layout->id]) }}">{{ $page->layout->label }}</a>
                                        <div class="clearfix"></div>
                                    @endif

                                    @if ($page->user_id && !($page->is_homepage == 1))
                                        <b>{{ __('Author') }}:</b> <a target="_blank" href="{{ route('admin.accounts.show', ['id' => $page->user_id]) }}">{{ $page->author->name }}</a>
                                        <div class="clearfix"></div>
                                    @endif

                                    @if ($page->parent_id)
                                        <b>{{ __('Parent page') }}:</b> <a target="_blank" href="{{ page($page->parent->content->page_id)->url ?? null }}">{{ $page->parent->content->title }}</a>
                                        <div class="clearfix"></div>
                                    @endif

                                </small>
                            </td>

                            <td>
                                <div class="d-grid gap-2">
                                    @can('view', $page)
                                        <a href="{{ route('admin.pages.content', ['id' => $page->id]) }}" class="btn btn-gear btn-sm mb-2">{{ __('Page content') }}</a>
                                        <a href="{{ route('admin.pages.show', ['id' => $page->id]) }}" class="btn btn-primary btn-sm mb-2">
                                            @if ($page->is_homepage == 1)
                                                {{ __('Homepage config') }}
                                            @else
                                                {{ __('Settings') }}
                                            @endif
                                        </a>
                                    @endcan

                                    @if (!($page->is_homepage == 1))
                                        @can('delete', $page)
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $page->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                            <div class="modal fade confirm-{{ $page->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel-{{ $page->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel-{{ $page->id }}">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to move this item to trash?') }}

                                                            <div class="mt-2 fw-bold">
                                                                <i class="bi bi-info-circle"></i> {{ __('This item will be moved to trash. You can recover it or permanently delete from recycle bin.') }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.pages.show', ['id' => $page->id]) }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                                <button type="submit" class="btn btn-danger">{{ __('Yes. Move to trash') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{ $pages->appends(['search_terms' => $search_terms])->links() }}

    </div>
    <!-- end card-body -->

</div>
