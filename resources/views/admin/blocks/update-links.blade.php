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


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <h4 class="card-title">@include('admin.includes.block_type_label', ['type' => $block->type]) - {{ __('Manage block content') }}</h4>
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
                        <input class="form-check-input" type="checkbox" id="use_custom_style" name="use_custom_style" @if ($block_extra['style_id'] ?? null) checked @endif>
                        <label class="form-check-label" for="use_custom_style">{{ __('Use custom style for this section') }}</label>
                    </div>
                </div>

                <script>
                    $('#use_custom_style').change(function() {
                        select = $(this).prop('checked');
                        if (select)
                            document.getElementById('hidden_div_style').style.display = 'block';
                        else
                            document.getElementById('hidden_div_style').style.display = 'none';
                    })
                </script>

                <div id="hidden_div_style" style="display: @if (isset($block_extra['style_id'])) block @else none @endif" class="mt-2">
                    <div class="form-group col-xl-3 col-lg-4 col-md-6 mb-0">
                        <label>{{ __('Select custom style') }} [<a class="fw-bold" target="_blank" href="{{ route('admin.template.styles') }}">{{ __('manage custom styles') }}</a>]</label>
                        <select class="form-select" id="style_id" name="style_id" value="@if (isset($block_extra['style_id'])) {{ $block_extra['style_id'] }} @else #fbf7f0 @endif">
                            <option value="">-- {{ __('select') }} --</option>
                            @foreach ($styles as $style)
                                <option @if (($block_extra['style_id'] ?? null) == $style->id) selected @endif value="{{ $style->id }}">{{ $style->label }}</option>
                            @endforeach
                        </select>
                        @if (count($styles) == 0)
                            <div class="small text-info mt-1">{{ __("You don't have custom styles created") }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group col-md-4 col-xl-3 mt-3">
                    <label>{{ __('Links display style') }}</label>
                    <select class="form-select" name="display_style">
                        <option @if (($block_extra['display_style'] ?? null) == 'list') selected @endif value="list">{{ __('Ordered list (one link per line)') }}</option>
                        <option @if (($block_extra['display_style'] ?? null) == 'multiple') selected @endif value="multiple">{{ __('One after another') }}</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="new_tab" name="new_tab" @if ($block_extra['new_tab'] ?? null) checked @endif>
                        <label class="form-check-label" for="new_tab">{{ __('Open links in new tab') }}</label>
                    </div>
                </div>

            </div>


            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            @foreach ($content_langs as $lang)
                @if (count($langs) > 1 && $block_module != 'posts')
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
                            <textarea class="form-control trumbowyg" rows="2" name="header_content_{{ $lang->id }}">{{ $header_array['content'] ?? null }}</textarea>
                        </div>
                    </div>
                </div>

                @php
                    $content_array = unserialize($lang->block_content);
                @endphp

                @if ($content_array)
                    @for ($i = 0; $i < count($content_array); $i++)
                        <div class="row">
                            <div class="col-md-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('Link title') }}</label>
                                    <input type="text" class="form-control" name="a_title_{{ $lang->id }}[]" value="{{ $content_array[$i]['title'] ?? null }}">
                                </div>
                            </div>

                            <div class="col-md-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('URL') }}</label>
                                    <input type="text" class="form-control" name="a_url_{{ $lang->id }}[]" value="{{ $content_array[$i]['url'] ?? null }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('Icon code') }} ({{ __('optional') }})</label>
                                    <input type="text" class="form-control" name="a_icon_{{ $lang->id }}[]" value="{{ $content_array[$i]['icon'] ?? null }}">
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif

                <div class="mb-3">
                    <button type="button" class="btn btn-light addButton_{{ $lang->id }}"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
                </div>

                <!-- The template for adding new item -->
                <div class="form-group hide" id="ItemTemplate_{{ $lang->id }}">
                    <div class="row">
                        <div class="col-md-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Link title') }}</label>
                                <input type="text" class="form-control" name="a_title_{{ $lang->id }}[]" />
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>{{ __('URL') }}</label>
                                <input type="text" class="form-control" name="a_url_{{ $lang->id }}[]" />
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Icon code') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="a_icon_{{ $lang->id }}[]" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3"></div>
                </div>

                <script>
                    $(document).ready(function() {
                        urlIndex_{{ $lang->id }} = 0;
                        $('#updateBlock')
                            // Add button click handler
                            .on('click', '.addButton_{{ $lang->id }}', function() {
                                urlIndex_{{ $lang->id }}++;
                                var $template = $('#ItemTemplate_{{ $lang->id }}'),
                                    $clone = $template
                                    .clone()
                                    .removeClass('hide')
                                    .removeAttr('id')
                                    .attr('data-proforma-index', urlIndex_{{ $lang->id }})
                                    .insertBefore($template);

                                // Update the name attributes
                                $clone
                                    .find('[name="a_title_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].a_title_{{ $lang->id }}').end()
                                    .find('[name="a_url_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].a_url_{{ $lang->id }}').end()
                                    .find('[name="a_icon_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].a_icon_{{ $lang->id }}').end();
                            })

                    });
                </script>

                <div class="mb-4"></div>

                @php
                    if ($block_module == 'posts') {
                        break;
                    }
                @endphp

                @if (count($langs) > 1 && !$loop->last)
                    <hr>
                @endif
            @endforeach

            <div class="form-group">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
