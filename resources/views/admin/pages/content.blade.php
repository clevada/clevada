@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">{{ __('Pages') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.show', ['id' => $page->id]) }}">{{ $page->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Page content') }}</li>
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
                @if ($page->active == 1)
                    <div class="float-end ms-2">
                        @if ($page->is_homepage)
                            <a target="_blank" href="{{ route('home') }}" class="btn btn-light"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview page') }}</a>
                        @else
                            <a target="_blank" href="{{ route('page', ['slug' => $page->slug]) }}" class="btn btn-light"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview page') }}</a>
                        @endif
                    </div>
                @endif

                @if ($page->deleted_at)
                    <div class="text-danger fw-bold mb-2">
                        {{ __('This item is in the Trash.') }}
                    </div>
                @endif

                @if ($page->active == 0)
                    <div class="fw-bold text-danger mt-1 mb-2">
                        <i class="bi bi-exclamation-circle"></i> {{ __('Page is not published.') }}
                    </div>

                    @can('update', $page)
                        <div class="form-check form-switch mt-2" id="publishSwitchDiv">
                            <input class="form-check-input" type="checkbox" role="switch" id="publishSwitch">
                            <label class="form-check-label" for="publishSwitch">{{ __('Publish page') }}</label>
                        </div>

                        <div class="fw-bold text-success" id="updatedResult"></div>

                        <script>
                            function onToggle() {
                                $('#publishSwitchDiv :checkbox').change(function() {
                                    if (this.checked) {
                                        updateStatus(1);
                                    } else {
                                        updateStatus(0);
                                    }
                                });
                            }

                            function updateStatus(status_val) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('admin.pages.ajaxPublishSwitch', ['id' => $page->id]) }}",
                                    data: {
                                        toggle_update: true,
                                        status: status_val
                                    },
                                    success: function(result) {
                                        console.log(result);
                                        document.getElementById("updatedResult").innerText = "{{ __('Page published') }}" + result;
                                    }
                                });
                            }

                            $(document).ready(function() {
                                onToggle(); //Update when toggled
                            });
                        </script>
                    @endcan
                @endif


                <div class="card-title">{{ __('Update content') }} - @if ($page->is_homepage)
                        {{ __('Home page') }}
                    @else
                        {{ $page->title ?? null }}
                    @endif
                </div>

                @if (($config->tpl_home_content_source ?? null) == 'last_posts')
                    <div class="text-danger fw-bold"><i class="bi bi-info-circle"></i> {{ 'Warning. Home page content is set to "List of the last posts". You must set to "Manually build home page with blocks".' }} <a
                            href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Change') }}</a></div>
                @endif


                <div class="form-text">{{ __('Click on "Add blocs" to add content blocks (text, images, columns, ...)') }}</div>

                <div class="clearfix"></div>
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
                @if ($message == 'created')
                    {{ __('Created') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif

        <div class="row">

            <div class="col-12">
                <div class="builder-col sortable" id="sortable_top">
                    @if (!$page->deleted_at)
                        @can('update', $page)
                            <div class="mb-4 text-center">
                                <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock"><i class="bi bi-plus-circle"></i>
                                    {{ __('Add content block') }}</a>
                            </div>
                        @endcan
                    @endif

                    @foreach (content_blocks('pages', $page->id, $show_hidden = 1) as $block)
                        <div class="builder-block movable" id="item-{{ $block->id }}">
                            <div class="float-end ms-2">

                                @if ($block->hide == 1)
                                    <div class="badge bg-danger fs-6 me-2">{{ __('Hidden') }}</div>
                                @endif

                                <a href="{{ route('admin.blocks.show', ['id' => $block->id]) }}" class="btn btn-primary">{{ __('Manage content') }}</a>

                                @if (!$page->deleted_at)
                                    @can('update', $page)
                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $block->id }}" class="btn btn-outline-danger ms-2"><i class="bi bi-trash"></i></a>
                                        <div class="modal fade confirm-{{ $block->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to remove this block from this page? Block content will be deleted also.') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.pages.content.delete', ['id' => $page->id, 'block_id' => $block->id]) }}">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                            <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                @endif
                            </div>

                            @if ($block->label)
                                <div class="listing">{{ $block->label }}</div>
                            @endif

                            <b>
                                @include('admin.includes.block_type_label', ['type' => $block->type])
                            </b>

                            <div class="small text-muted">
                                ID: {{ $block->id }}. @if ($block->updated_at)
                                    {{ __('Updated at') }}: {{ date_locale($block->updated_at, 'datetime') }}
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        @include('admin.includes.modal-add-content-block', ['content_id' => $page->id])

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#sortable_top").sortable({
                    axis: 'y',
                    opacity: 0.8,
                    revert: true,

                    update: function(event, ui) {
                        var data = $(this).sortable('serialize');
                        $.ajax({
                            data: data,
                            type: 'POST',
                            url: "{{ route('admin.pages.sortable', ['id' => $page->id]) }}",
                        });
                    }
                });
                $("#sortable_top").disableSelection();

            });
        </script>


    </div>
    <!-- end card-body -->

</div>
