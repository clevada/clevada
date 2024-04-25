@include('admin.includes.trumbowyg-assets')
@include('admin.includes.color-picker')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage block content') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <h4 class="card-title">{{ __('Manage block content') }} ({{ __('Accordion') }})</h4>
            </div>

        </div>

    </div>


    <div class="card-body">

        @php
            if (($is_footer_block ?? null) == 1) {
                $action = route('admin.template.footer.block', ['id' => $block->id]);
            } else {
                $action = route('admin.blocks.show', ['id' => $block->id]);
            }
        @endphp

        <form id="updateBlock" method="post" enctype="multipart/form-data" action="{{ $action }}">
            @csrf
            @method('PUT')

            @php
                $block_extra = unserialize($block->extra);
            @endphp

            <div class="card p-3 bg-light mb-4">

                <div class="form-group col-lg-4 col-md-6">
                    <label class="form-label" for="blockLabel">{{ __('Label') }} ({{ __('optional') }})</label>
                    <input class="form-control" type="text" id="blockLabel" name="label" value="{{ $block->label }}">
                    <div class="form-text">{{ __('You can enter a label for this block to easily identify it from multiple blocks of the same type on a page') }}</div>
                </div>

                <div class="form-group">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="hide" name="hide" @if ($block->hide ?? null) checked @endif>
                        <label class="form-check-label" for="hide">{{ __('Hide block') }}</label>
                    </div>
                    <div class="form-text">{{ __('Hidden blocks are not displayed on website') }}</div>
                </div>

                @php
                    $title_font_size = $block_extra['title_size'] ?? config('defaults.h4_size');
                @endphp

                <div class="col-12 col-xl-2 col-lg-4 col-md-6 mt-3">
                    <div class="form-group">
                        <label>{{ __('Title text size') }}</label>
                        <select class="form-select" name="title_size">
                            <option @if ($title_font_size == 'fs-6') selected @endif value="fs-6">{{ __('Normal') }}</option>
                            <option @if ($title_font_size == 'fs-5') selected @endif value="fs-5">{{ __('Large') }}</option>
                            <option @if ($title_font_size == 'fs-4') selected @endif value="fs-4">{{ __('Extra large') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <input class="form-control form-control-color" name="title_color" id="title_color" value="{{ $block_extra['title_color'] ?? '#000000' }}">
                        <label>{{ __('Title color') }}</label>
                        <script>
                            $('#title_color').spectrum({
                                type: "color",
                                showInput: true,
                                showInitial: true,
                                showAlpha: false,
                                showButtons: false,
                                allowEmpty: false,
                            });
                        </script>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <input class="form-control form-control-color" name="title_bg_color" id="title_bg_color" value="{{ $block_extra['title_bg_color'] ?? '#ffffff' }}">
                        <label>{{ __('Title background color') }}</label>
                        <script>
                            $('#title_bg_color').spectrum({
                                type: "color",
                                showInput: true,
                                showInitial: true,
                                showAlpha: false,
                                showButtons: false,
                                allowEmpty: false,
                            });
                        </script>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="collapse_first_item" name="collapse_first_item" @if ($block_extra['collapse_first_item'] ?? null) checked @endif>
                        <label class="form-check-label" for="collapse_first_item">{{ __('Collapse first item content') }}</label>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="remove_border" name="remove_border" @if ($block_extra['remove_border'] ?? null) checked @endif>
                        <label class="form-check-label" for="remove_border">{{ __('Remove border') }}</label>
                    </div>
                </div>

            </div>


            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            @php
                $header_array = unserialize($block->header);
            @endphp

            <div class="card p-3 bg-light mb-4">
                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="add_header" name="add_header" @if ($header_array['add_header'] ?? null) checked @endif>
                        <label class="form-check-label" for="add_header">{{ __('Add header content') }}</label>
                    </div>
                </div>

                <script>
                    $('#add_header').change(function() {
                        select = $(this).prop('checked');
                        if (select)
                            document.getElementById('hidden_div_header').style.display = 'block';
                        else
                            document.getElementById('hidden_div_header').style.display = 'none';
                    })
                </script>

                <div id="hidden_div_header" style="display: @if ($header_array['add_header'] ?? null) block @else none @endif" class="mt-2">
                    <div class="form-group">
                        <label>{{ __('Header title') }}</label>
                        <input class="form-control" name="header_title" value="{{ $header_array['title'] ?? null }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Header content') }}</label>
                        <textarea class="form-control trumbowyg" name="header_content">{{ $header_array['content'] ?? null }}</textarea>
                    </div>
                </div>
            </div>

            @php
                $content_array = unserialize($block->content ?? null);
            @endphp

            @if ($content_array)
                @for ($i = 0; $i < count($content_array); $i++)
                    <div class="form-group">
                        <label>{{ __('Title') }}</label>
                        <input class="form-control" type="text" name="title[]" value="{{ $content_array[$i]['title'] ?? null }}">
                    </div>

                    <div class="form-group">
                        <label>{{ __('Content') }}</label>
                        <textarea class="form-control trumbowyg" name="content[]">{{ $content_array[$i]['content'] ?? null }}</textarea>
                    </div>

                    <div class="mb-4"></div>
                @endfor
            @endif

            <div class="mb-3 mt-3">
                <button type="button" class="btn btn-light addButton"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
            </div>

            <!-- The template for adding new item -->
            <div class="form-group hide" id="ItemTemplate">

                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input class="form-control" type="text" name="title[]">
                </div>

                <div class="form-group">
                    <label>{{ __('Content') }}</label>
                    <textarea class="form-control trumbowyg" name="content[]"></textarea>
                </div>
                <div class="mb-4"></div>
            </div>

            <script>
                $(document).ready(function() {
                    urlIndex = 0;
                    //$('.trumbowyg').trumbowyg();
                    $('#updateBlock')
                        // Add button click handler
                        .on('click', '.addButton', function() {
                            urlIndex++;
                            var $template = $('#ItemTemplate'),
                                $clone = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-block-index', urlIndex)
                                .insertBefore($template);

                            // Update the name attributes
                            $clone
                                .find('[name="title"]').attr('name', 'updateBlock[' + urlIndex + '].title').end()
                                .find('[name="content"]').attr('name', 'updateBlock[' + urlIndex + '].content').end()
                                .find('textarea').trumbowyg();
                        })

                });
            </script>

            <div class="form-group">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
