@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages') }}">{{ __('Pages') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.show', ['id' => $page->id]) }}">{{ $page->label }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Page content') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">
    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <div class="float-end ms-2">
                        @if (count(sys_langs()) > 1)
                            <div class="dropdown">
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-plus-circle"></i> {{ __('Preview page') }}
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @foreach (sys_langs() as $preview_lang)
                                        <li><a class="dropdown-item" target="_blank" href="{{ page($page->id, $preview_lang->id)->url }}">{{ $preview_lang->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <a target="_blank" href="{{ page($page->id)->url }}" class="btn btn-secondary"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview page') }}</a>
                        @endif
                    </div>
                    <h4 class="card-title">{{ __('Update page content') }} - {{ $page->label }}</h4>
                    <div class="form-text">{{ __('Click on "Add blocs" to add content blocks (text, images, columns, ...)') }}</div>
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
                    @if ($message == 'duplicate'){{ __('Error. Page with this slug already exists') }} @endif
                    @if ($message == 'length2'){{ __('Error. Page slug must be minimum 3 characters') }} @endif
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'created'){{ __('Created') }} @endif
                    @if ($message == 'deleted'){{ __('Deleted') }} @endif
                    @if ($message == 'updated'){{ __('Updated') }} @endif
                </div>
            @endif

            <div class="row gy-5">

                <div class="col-12">
                    <div class="builder-col sortable" id="sortable_top">
                        <div class="mb-4 text-center">
                            <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock"><i class="bi bi-plus-circle"></i>
                                {{ __('Add content block') }}</a>
                        </div>

                        @foreach (content_blocks('pages', $page->id, $show_hidden = 1) as $block)
                            <div class="builder-block movable" id="item-{{ $block->id }}">
                                <div class="float-end ms-2">

                                    @if ($block->hide == 1)<div class="badge bg-danger fs-6 me-2">{{ __('Hidden') }}</div>@endif

                                    <a href="{{ route('admin.blocks.show', ['id' => $block->id]) }}" class="btn btn-primary">{{ __('Manage content') }}</a>                                   
                                    
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
                                </div>

                                @if ($block->label)<div class="listing">{{ $block->label }}</div>@endif

                                <b>{{ __($block->type_label) }}</b>
                                
                                <div class="small text-muted">
                                    ID: {{ $block->id }}. @if ($block->updated_at) {{ __('Updated at') }}: {{ date_locale($block->updated_at, 'datetime') }}@endif
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

</section>
