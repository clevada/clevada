<div class="row gy-5">

    <div class="col-12">
        <div class="builder-col sortable" id="sortable">
            <h5>{{ __('Manage main content for this page') }}</h5>

            <div class="mb-4">
                <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock"><i class="bi bi-plus-circle"></i> {{ __('Add content block') }}</a>
            </div>

            @foreach (template_blocks($module, $template->id, $show_hidden = 1) as $block)
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
                                        <form method="POST" action="{{ route('admin.templates.content.delete', ['template_id' => $template->id, 'module' => $module, 'block_id' => $block->id]) }}">
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



@include('admin.includes.modal-add-content-block')

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
                    url: "{{ route('admin.templates.sortable', ['module' => $module, 'template_id' => $template->id]) }}",
                });
            }
        });
        $("#sortable").disableSelection();

    });
</script>
