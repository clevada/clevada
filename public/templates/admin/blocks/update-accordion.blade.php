@include('admin.includes.trumbowyg-assets')

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


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12">
                    <h4 class="card-title">{{ __('Manage block content') }} ({{ $block->type_label }})</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            <form id="updateBlock" method="post" enctype="multipart/form-data">
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

                    <div class="form-group mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_custom_bg" name="use_custom_bg" @if ($block_extra['bg_color'] ?? null) checked @endif>
                            <label class="form-check-label" for="use_custom_bg">{{ __('Use custom background color for this section') }}</label>
                        </div>
                        <div class="form-text">{{ __('This is the color of the section row (full width). If disabled, default website background color will be used.') }}</div>
                    </div>

                    <script>
                        $('#use_custom_bg').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div_color').style.display = 'block';
                            else
                                document.getElementById('hidden_div_color').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div_color" style="display: @if (isset($block_extra['bg_color'])) block @else none @endif" class="mt-2">
                        <div class="form-group">
                            <input class="form-control form-control-color" id="bg_color" name="bg_color" value="@if (isset($block_extra['bg_color'])) {{ $block_extra['bg_color'] }} @else #fbf7f0 @endif">
                            <label>{{ __('Background color') }}</label>
                            <script>
                                $('#bg_color').spectrum({
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

                    <div class="form-group mb-0 mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_custom_font_color" name="use_custom_font_color" @if ($block_extra['font_color'] ?? null) checked @endif>
                            <label class="form-check-label" for="use_custom_font_color">{{ __('Use custom font color for this section') }}</label>
                        </div>
                        <div class="form-text">{{ __('If disabled, default website font color will be used.') }}</div>
                    </div>

                    <script>
                        $('#use_custom_font_color').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div_font_color').style.display = 'block';
                            else
                                document.getElementById('hidden_div_font_color').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div_font_color" style="display: @if (isset($block_extra['font_color'])) block @else none @endif" class="mt-2">
                        <div class="form-group">
                            <input class="form-control form-control-color" id="font_color" name="font_color" value="@if (isset($block_extra['font_color'])) {{ $block_extra['font_color'] }} @else #000000 @endif">
                            <label>{{ __('Font color') }}</label>
                            <script>
                                $('#font_color').spectrum({
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


                    @php
                        $title_font_size = $block_extra['title_size'] ?? config('defaults.h4_size');
                    @endphp

                    <div class="col-12 col-xl-2 col-lg-4 col-md-6 mt-3">
                        <div class="form-group">
                            <label>{{ __('Title text size') }}</label>
                            <select class="form-select" name="title_size">
                                @foreach (template_font_sizes() as $selected_font_size_title)
                                    <option @if ($title_font_size == $selected_font_size_title->value) selected @endif value="{{ $selected_font_size_title->value }}">{{ $selected_font_size_title->name }}</option>
                                @endforeach
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

                    <div class="form-group mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="collapse_first_item" name="collapse_first_item" @if ($block_extra['collapse_first_item'] ?? null) checked @endif>
                            <label class="form-check-label" for="collapse_first_item">{{ __('Collapse first item content') }}</label>
                        </div>
                    </div>

                    @include('admin.includes.offcanvas-block-settings')
                    
                </div>


                <h5 class="mb-3">{{ __('Block content') }}:</h5>

                @foreach ($langs as $lang)

                    @if (count(sys_langs()) > 1 && $block_module != 'posts')
                        <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                    @endif

                    @php
                        $header_array = unserialize($lang->block_header);
                    @endphp

                    <div class="card p-3 bg-light mb-4">
                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="add_header_{{ $lang->id }}" name="add_header_{{ $lang->id }}" @if ($header_array['add_header'] ?? null) checked @endif>
                                <label class="form-check-label" for="add_header_{{ $lang->id }}">{{ __('Add header content') }}</label>
                            </div>
                        </div>

                        <script>
                            $('#add_header_{{ $lang->id }}').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_header_{{ $lang->id }}').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_header_{{ $lang->id }}').style.display = 'none';
                            })
                        </script>

                        <div id="hidden_div_header_{{ $lang->id }}" style="display: @if ($header_array['add_header'] ?? null) block @else none @endif" class="mt-2">
                            <div class="form-group">
                                <label>{{ __('Header title') }}</label>
                                <input class="form-control" name="header_title_{{ $lang->id }}" value="{{ $header_array['title'] ?? null }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Header content') }}</label>
                                <textarea class="form-control trumbowyg" name="header_content_{{ $lang->id }}">{{ $header_array['content'] ?? null }}</textarea>
                            </div>
                        </div>
                    </div>

                    @php
                        $content_array = unserialize($lang->block_content);
                    @endphp

                    @if ($content_array)
                        @for ($i = 0; $i < count($content_array); $i++)
                            <div class="form-group">
                                <label>{{ __('Title') }}</label>
                                <input class="form-control" type="text" name="title_{{ $lang->id }}[]" value="{{ $content_array[$i]['title'] ?? null }}">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Content') }}</label>
                                <textarea class="form-control trumbowyg" name="content_{{ $lang->id }}[]">{{ $content_array[$i]['content'] ?? null }}</textarea>
                            </div>

                            <div class="mb-4"></div>
                        @endfor
                    @endif

                    <div class="mb-3 mt-3">
                        <button type="button" class="btn btn-light addButton_{{ $lang->id }}"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
                    </div>

                    <!-- The template for adding new item -->
                    <div class="form-group hide" id="ItemTemplate_{{ $lang->id }}">

                        <div class="form-group">
                            <label>{{ __('Title') }}</label>
                            <input class="form-control" type="text" name="title_{{ $lang->id }}[]">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Content') }}</label>
                            <textarea class="form-control trumbowyg_{{ $lang->id }}" name="content_{{ $lang->id }}[]"></textarea>
                        </div>
                        <div class="mb-4"></div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            urlIndex_{{ $lang->id }} = 0;
                            //$('.trumbowyg_{{ $lang->id }}').trumbowyg();
                            $('#updateBlock')
                                // Add button click handler
                                .on('click', '.addButton_{{ $lang->id }}', function() {
                                    urlIndex_{{ $lang->id }}++;
                                    var $template = $('#ItemTemplate_{{ $lang->id }}'),
                                        $clone = $template
                                        .clone()
                                        .removeClass('hide')
                                        .removeAttr('id')
                                        .attr('data-block-index', urlIndex_{{ $lang->id }})
                                        .insertBefore($template);

                                    // Update the name attributes
                                    $clone
                                        .find('[name="title_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].title_{{ $lang->id }}').end()
                                        .find('[name="content_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].content_{{ $lang->id }}').end()
                                        .find('textarea').trumbowyg();
                                })

                        });
                    </script>

                    <div class="mb-4"></div>

                    @php
                        if ($block_module == 'posts') {
                            break;
                        }
                    @endphp

                    @if (count(sys_langs()) > 1 && !$loop->last)<hr>@endif

                @endforeach


                <div class="form-group">
                    <input type="hidden" name="type_id" value="{{ $block->type_id }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update block') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
