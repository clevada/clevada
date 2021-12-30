<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.template.global_sections') }}">{{ __('Top / bottom sections') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $section->label }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">
                <div class="col-12 mb-3">
                    @include('admin.template.layouts.menu-template')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ $section->label }} - {{ __('Manage content') }}</h4>
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
                    @if ($message == 'updated')
                        <h4 class="alert-heading">{{ __('Updated') }}</h4>
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Info: If you don\'t see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.') }}
                    @endif
                    @if ($message == 'deleted')
                        {{ __('Deleted') }}
                    @endif
                </div>
            @endif

            <div class="row gy-5">

                <div class="col-12">
                    <div class="builder-col sortable" id="sortable">
                        <div class="mb-4">
                            <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock"><i class="bi bi-plus-circle"></i>
                                {{ __('Add content block') }}</a>
                        </div>

                        @foreach (content_blocks('global', $section->id, $show_hidden = 1) as $block)
                            <div class="builder-block movable" id="item-{{ $block->id }}">
                                <div class="float-end ms-2">

                                    @if ($block->hide == 1)<div class="badge bg-danger fs-6 me-2">{{ __('Hidden') }}</div>@endif

                                    <a href="{{ route('admin.blocks.show', ['id' => $block->id]) }}" class="btn btn-primary">{{ __('Manage content') }}</a>

                                    <a href="#" data-bs-toggle="modal" data-bs-target=".tips-{{ $block->id }}" class="btn btn-outline-dark ms-2"><i class="bi bi-code-slash"></i> <i
                                            class="bi bi-link"></i></a>
                                    <div class="modal fade tips-{{ $block->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Tips') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <b>{{ __('Block ID') }}: {{ $block->id }}</b>
                                                    <div class="mb-2"></div>
                                                    {{ __('In frontend template this section have a div tag') }}: <code>&lt;div id="{{ $block->id }}"&gt;...&lt;/div&gt;</code>
                                                    <div class="mb-2"></div>
                                                    <i class="bi bi-info-circle"></i> {{ __(' You can create an anchor link to point this section inside your page, using anchor code an the end of URL') }}:
                                                    <b>#{{ $block->id }}</b>. <br>
                                                    {{ __('Examples') }}:<br>
                                                    <code>https://website.com/#{{ $block->id }}</code><br>
                                                    <code>https://website.com/example-page#{{ $block->id }}</code>
                                                    <div class="mb-2"></div>
                                                    <hr>
                                                    <i class="bi bi-info-circle"></i> {{ __('You can customize CSS style for this block, using a custom css code') }}.<br>
                                                    {{ __('Examples') }}: <br>
                                                    <code>&lt;style> #{{ $block->id }} { background-color: #ccc !important }}&lt;/style&gt;</code><br>
                                                    <code>&lt;style> #{{ $block->id }} a { color: red }}&lt;/style&gt;</code>
                                                    <div class="mb-2"></div>
                                                    {{ __('You can add custom css style (or upload custom css files) here') }}: <a
                                                        href="{{ route('admin.template.custom_code') }}">{{ __('custom code settings') }}</a>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $block->id }}" class="btn btn-outline-danger ms-2"><i class="bi bi-trash"></i></a>
                                    <div class="modal fade confirm-{{ $block->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to remove this block? Block content will be deleted also.') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.template.global_sections.content.delete', ['id' => $section->id, 'block_id' => $block->id]) }}">
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


            @include('admin.includes.modal-add-content-block', ['content_id' => $section->id])

            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <script>
                $(function() {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $("#sortable").sortable({
                        axis: 'y',
                        opacity: 0.8,
                        revert: true,

                        update: function(event, ui) {
                            var data = $(this).sortable('serialize');
                            $.ajax({
                                data: data,
                                type: 'POST',
                                url: "{{ route('admin.template.global_sections.sortable', ['id' => $section->id]) }}",
                            });
                        }
                    });
                    $("#sortable").disableSelection();

                });
            </script>

        </div>
        <!-- end card-body -->

    </div>

</section>
