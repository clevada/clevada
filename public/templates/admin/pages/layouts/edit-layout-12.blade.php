    <div class="row gy-5">

        <div class="col-12">
            <div class="builder-col sortable" id="sortable_top">
                <div class="mb-4 text-center">
                    <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock" onclick="populate_hidden_val('top')"><i class="bi bi-plus-circle"></i> {{ __('Add content block') }}</a>
                    <a class="btn btn-danger ms-2" href="#" data-bs-toggle="modal" data-bs-target="#addWidget" onclick="populate_hidden_val('top')"><i class="bi bi-plus-circle"></i> {{ __('Add widget') }}</a>
                </div>
                @foreach (layout_blocks($module, $page->id, $page->layout, $column = 'top') as $section)
                    <div class="builder-block movable" id="item-{{ $section->id }}">
                        <div class="float-end ms-2">
                            <a href="{{ route('admin.blocks.show', ['id' => $section->block_id]) }}" class="btn btn-primary btn-sm">{{ __('Manage content') }}</a>
                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $section->id }}" class="btn btn-outline-danger btn-sm ms-2"><i class="bi bi-trash"></i></a>
                            <div class="modal fade confirm-{{ $section->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
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
                                            <form method="POST" action="{{ route('admin.pages.content.delete', ['id' => $page->id, 'template_block_id' => $section->id]) }}">
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
                        <b>{{ __($section->type_label) }}</b>
                        @if ($section->block_updated_at) <div class="small text-muted">{{ __('Updated at') }}: {{ date_locale($section->block_updated_at, 'datetime') }}</div>@endif
                       
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function populate_hidden_val(element) {
            document.getElementById("hidden_area_block").value = element;
            document.getElementById("hidden_area_widget").value = element;
        }
    </script>

    @include('admin.pages.modals.layout-add-block')
    @include('admin.pages.modals.layout-add-widget')

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
                        url: "{{ route('admin.pages.sortable', ['id' => $page->id, 'layout' => $page->layout, 'column' => 'top']) }}",
                    });
                }
            });
            $("#sortable_top").disableSelection();

        });
    </script>
