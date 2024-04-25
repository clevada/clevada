<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.recycle_bin') }}">{{ __('Recycle Bin') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Posts') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Deleted posts') }} ({{ $items->total() ?? 0 }} {{ __('items') }})</h4>
            </div>

            <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    <a href="#" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#empty-trash"><i class="bi bi-trash"></i> {{ __('Empty this trash') }}</a>
                    @include('admin.recycle-bin.modals.empty-trash')
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
                @if ($message == 'restore')
                    {{ __('Item restored') }}
                @endif

                @if ($message == 'multiple_restore')
                    {{ __('Items restored') }}
                @endif

                @if ($message == 'delete')
                    {{ __('Item permanently deleted') }}
                @endif

                @if ($message == 'multiple_delete')
                    {{ __('Items permanently deleted') }}
                @endif
            </div>
        @endif

        <section>
            <form action="{{ route('admin.recycle_bin.module', ['module' => 'posts']) }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                <div class="col-12">
                    <input type="text" name="search_terms" placeholder="{{ __('Search post title') }}" class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif" value="<?= $search_terms ?>" />
                </div>

                <div class="col-12">
                    <select class="form-select me-2 mb-2 @if ($search_categ_id) is-valid @endif" name="search_categ_id">
                        <option selected="selected" value="">- {{ __('All categories') }} -</option>
                        @foreach ($posts_categories as $categ)
                            @include('admin.posts.loops.posts-filter-categories-loop', $categ)
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <button class="btn btn-secondary me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                    <a class="btn btn-light  mb-2" href="{{ route('admin.recycle_bin.module', ['module' => 'posts']) }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

            </form>
        </section>

        <div class="mb-2"></div>

        @if ($items->total() == 0)
            {{ __('No item') }}
        @else
            <form method="POST" action="{{ route('admin.recycle_bin.multiple_action', ['module' => 'posts']) }}">
                @csrf

                <div class="table-responsive-md">
                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th width="20">
                                    <input type="checkbox" name="select-all" id="select-all" />
                                </th>
                                <th>{{ __('Details') }}</th>
                                <th width="320">{{ __('Author') }}</th>
                                <th width="200">{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <input name='items_checkbox[]' type='checkbox' id='items_checkbox_{{ $item->id }}[]' value='{{ $item->id }}'>
                                    </td>
                                    <td>

                                        <div class="fs-6 fw-bold">
                                            <a href="{{ route('admin.posts.show', ['id' => $item->id]) }}">{{ $item->title }}</a>
                                        </div>

                                        <div class="text-muted small">
                                            {{ date_locale($item->created_at, 'datetime') }}
                                        </div>

                                        @if ($item->categ_id)
                                            <div class="mt-1"></div>
                                            {{ __('Category') }}:
                                            @foreach (breadcrumb($item->categ_id, 'posts') as $b_item)
                                                <a @if ($b_item->active != 1) class="text-danger" @endif target="_blank" href="{{ $b_item->url }}">{{ $b_item->title }}</a>
                                                @if (!$loop->last)
                                                    /
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>

                                    <td>
                                        <span class="float-start me-2"><img style="max-width:50px; height:auto;" class="rounded-circle" src="{{ avatar($item->user_id) }}" /></span>
                                        <b><a target="_blank" href="{{ route('admin.accounts.show', ['id' => $item->user_id]) }}">{{ $item->author->name }}</a></b>
                                        <br>{{ $item->author->email }}
                                    </td>


                                    <td>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'posts', 'id' => $item->id, 'return_to' => 'recycle_bin', 'action' => 'delete']) }}"
                                                class="btn btn-danger btn-sm mb-2">{{ __('Permanently delete') }}</a>

                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'posts', 'id' => $item->id, 'return_to' => 'recycle_bin', 'action' => 'restore']) }}"
                                                class="btn btn-success btn-sm">{{ __('Restore') }}</a>
                                        </div>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


                <div class="row row-cols-lg-auto g-3">
                    <div class="col-12">
                        <div class="form-group">
                            <select name="action" class="form-select" required>
                                <option value="">- {{ __('With selected:') }} -</option>
                                <option value="multiple_delete">{{ __('Permanently delete') }}</option>
                                <option value="multiple_restore">{{ __('Restore') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="hidden" name="return_to" value="recycle_bin">
                        <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    </div>
                </div>
            </form>

            {{ $items->appends(['search_terms' => $search_terms, 'search_categ_id' => $search_categ_id])->links() }}

        @endif

    </div>
    <!-- end card-body -->

</div>

<script language="JavaScript">
    $('#select-all').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });
</script>
