    <div class="row gy-5">

        <div class="col-12">
            <div class="builder-col sortable" id="sortable_top">
                <div class="mb-4 text-center">
                    <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock" onclick="populate_hidden_val('top')"><i class="bi bi-plus-circle"></i> {{ __('Add block') }}</a>
                    @if(count(layout_blocks($module, $content_id = null, $layout, $column = 'top')) == 0)<div class="text-info mt-2">{{ __('Ths section will be displayed only if there are blocks in this area') }}</div>@endif
                </div> 

                @foreach (layout_blocks($module, $content_id = null, $layout, $column = 'top') as $block)
                    <div class="builder-block movable" id="item-{{ $block->id }}">
                        <div class="float-end ms-2"><a target="_blank" href="{{ route('admin.blocks.show', ['id' => $block->block_id]) }}" class="btn btn-secondary btn-sm">{{ __('Manage') }}</a></div>
                        <b>@if($block->type == 'editor') {{ __('Text content (HTML)') }}
                            @elseif($block->type == 'links') {{ __('Links block') }}
                            @elseif($block->type == 'hero') {{ __('Hero block') }}
                            @elseif($block->type == 'ads') {{ __('Ads code') }}
                            @elseif($block->type == 'slider') {{ __('Slider') }}
                            @else {{ $block->type }}
                            @endif
                        </b>
                        <div class="text-muted">{{ $block->label }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-8">
            <div class="builder-col sortable" id="sortable_start">
                <div class="mb-4 text-center">
                    <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock" onclick="populate_hidden_val('start')"><i class="bi bi-plus-circle"></i> {{ __('Add block') }}</a>
                </div> 

                @foreach (layout_blocks($module, $content_id = null, $layout, $column = 'start') as $block)
                    <div class="builder-block movable" id="item-{{ $block->id }}">
                        <div class="float-end ms-2"><a target="_blank" href="{{ route('admin.blocks.show', ['id' => $block->block_id]) }}" class="btn btn-secondary btn-sm">{{ __('Manage') }}</a></div>
                        <b>@if($block->type == 'editor') {{ __('Text content (HTML)') }}
                            @elseif($block->type == 'links') {{ __('Links block') }}
                            @elseif($block->type == 'hero') {{ __('Hero block') }}
                            @elseif($block->type == 'ads') {{ __('Ads code') }}
                            @elseif($block->type == 'slider') {{ __('Slider') }}
                            @else {{ $block->type }}
                            @endif
                        </b>
                        <div class="text-muted">{{ $block->label }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-4">
            <div class="builder-col sortable" id="sortable_end">
                <div class="mb-4 text-center">
                    <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock" onclick="populate_hidden_val('end')"><i class="bi bi-plus-circle"></i> {{ __('Add block') }}</a>
                </div> 

                @foreach (layout_blocks($module, $content_id = null, $layout, $column = 'end') as $block)
                    <div class="builder-block movable" id="item-{{ $block->id }}">
                        <div class="float-end ms-2"><a target="_blank" href="{{ route('admin.blocks.show', ['id' => $block->block_id]) }}" class="btn btn-secondary btn-sm">{{ __('Manage') }}</a></div>
                        <b>@if($block->type == 'editor') {{ __('Text content (HTML)') }}
                            @elseif($block->type == 'links') {{ __('Links block') }}
                            @elseif($block->type == 'hero') {{ __('Hero block') }}
                            @elseif($block->type == 'ads') {{ __('Ads code') }}
                            @elseif($block->type == 'slider') {{ __('Slider') }}
                            @else {{ $block->type }}
                            @endif
                        </b>
                        <div class="text-muted">{{ $block->label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function populate_hidden_val(element) {
            document.getElementById("hidden_area").value = element;
        }
    </script>

    @include('admin.template.modals.layout-add-block')

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
                        url: "{{ route('admin.builder.sortable', ['module' => $module, 'layout' => $layout, 'column' => 'top']) }}",
                    });
                }
            });
            $("#sortable_top").disableSelection();

            $("#sortable_start").sortable({
                axis: 'y',
                opacity: 0.8,
                revert: true,                

                update: function(event, ui) {
                    var data = $(this).sortable('serialize');                
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: "{{ route('admin.builder.sortable', ['module' => $module, 'layout' => $layout, 'column' => 'start']) }}",
                    });
                }
            });
            $("#sortable_start").disableSelection();

            $("#sortable_end").sortable({
                axis: 'y',
                opacity: 0.8,
                revert: true,                

                update: function(event, ui) {
                    var data = $(this).sortable('serialize');                
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: "{{ route('admin.builder.sortable', ['module' => $module, 'layout' => $layout, 'column' => 'end']) }}",
                    });
                }
            });
            $("#sortable_end").disableSelection();

        });
    </script>
