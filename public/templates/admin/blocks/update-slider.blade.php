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


                    <div class="form-group col-md-4 col-xl-2">
                        <label>{{ __('Background style') }}</label>
                        <select class="form-select" name="bg_style" id="bg_style" onchange="change_bg_style()">
                            <option @if (($block_extra['bg_style'] ?? null) == 'color') selected @endif value="color">{{ __('Color') }}</option>
                            <option @if (($block_extra['bg_style'] ?? null) == 'image') selected @endif value="image">{{ __('Image') }}</option>
                        </select>
                    </div>

                    <script>
                        function change_bg_style() {
                            var select = document.getElementById('bg_style');
                            var value = select.options[select.selectedIndex].value;
                            if (value == 'color') {
                                document.getElementById('hidden_div_bg_image').style.display = 'none';
                                document.getElementById('hidden_div_bg_color').style.display = 'block';
                            } else {
                                document.getElementById('hidden_div_bg_image').style.display = 'block';
                                document.getElementById('hidden_div_bg_color').style.display = 'none';
                            }

                        }
                    </script>


                    <div id="hidden_div_bg_color" style="display: @if (!isset($block_extra['bg_style']) || ($block_extra['bg_style'] ?? null) == 'color') block @else none @endif">
                        <div class="form-group">
                            <input class="form-control form-control-color" id="bg_color" name="bg_color" value="@if (isset($block_extra['bg_color'])) {{ $block_extra['bg_color'] }} @else #444444 @endif">
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


                    <div id="hidden_div_bg_image" style="display: @if (($block_extra['bg_style'] ?? null) == 'image') block @else none @endif">
                        <div class="form-group col-xl-2 col-md-3 col-sm-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cover_dark" name="cover_dark" @if ($block_extra['cover_dark'] ?? null) checked @endif>
                                <label class="form-check-label" for="cover_dark">{{ __('Add dark layer to background cover') }}</label>
                            </div>
                        </div>

                        <div class="form-group col-xl-2 col-md-3 col-sm-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cover_fixed" name="cover_fixed" @if ($block_extra['cover_fixed'] ?? null) checked @endif>
                                <label class="form-check-label" for="cover_fixed">{{ __('Fixed background') }}</label>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="formFile" class="form-label">{{ __('Image') }}</label>
                            <input class="form-control" type="file" id="formFile" name="bg_image">
                        </div>
                        @if ($block_extra['bg_image'] ?? null)
                            <a target="_blank" href="{{ image($block_extra['bg_image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($block_extra['bg_image']) }}"
                                    class="img-fluid"></a>
                        @endif
                    </div>

                    <div class="form-group mt-3">
                        <input class="form-control form-control-color" id="font_color" name="font_color" value="@if (isset($block_extra['font_color'])) {{ $block_extra['font_color'] }} @else #ffffff @endif">
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

                    <div class="row">
                        <div class="col-md-4 col-lg-3 col-12 form-group">
                            @php
                                $title_font_size = $block_extra['title_font_size'] ?? config('defaults.h3_size');
                            @endphp

                            <label>{{ __('Title font size') }}</label>
                            <select class="form-select" name="title_font_size">
                                @foreach (template_font_sizes() as $selectes_font_size_title)
                                    <option @if ($title_font_size == $selectes_font_size_title->value) selected @endif value="{{ $selectes_font_size_title->value }}">{{ $selectes_font_size_title->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-3 col-12 form-group">
                            @php
                                $content_font_size = $block_extra['content_font_size'] ?? config('defaults.font_size');
                            @endphp

                            <label>{{ __('Content font size') }}</label>
                            <select class="form-select" name="content_font_size">
                                @foreach (template_font_sizes() as $selectes_font_size_title)
                                    <option @if ($content_font_size == $selectes_font_size_title->value) selected @endif value="{{ $selectes_font_size_title->value }}">{{ $selectes_font_size_title->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-3 col-12 form-group">
                        @php
                            $content_font_size = $block_extra['content_font_size'] ?? config('defaults.font_size');
                        @endphp

                        <label>{{ __('Interval duration (in seconds)') }}</label>
                        <input class="form-control" name="delay_seconds" value="{{ $block_extra['delay_seconds'] ?? null }}">
                        <div class="form-text">{{ 'Change the amount of time (in seconds) to delay between automatically cycling to the next item. Leave empty for no delay to next item' }}</div>
                    </div>

                    @include('admin.includes.offcanvas-block-settings')
                    
                </div>


                <h5 class="mb-3">{{ __('Block content') }}:</h5>

                @foreach ($langs as $lang)

                    @if (count(sys_langs()) > 1 && $block_module != 'posts')
                        <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                    @endif

                    @php
                        $content_array = unserialize($lang->block_content);
                    @endphp

                    @if ($content_array)
                        @for ($i = 0; $i < count($content_array); $i++)
                            <div class="card p-3 bg-light mb-4">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Link title') }}</label>
                                            <input type="text" class="form-control" name="title_{{ $lang->id }}[]" value="{{ $content_array[$i]['title'] ?? null }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>{{ __('URL') }} ({{ __('optional') }})</label>
                                            <input type="text" class="form-control" name="url_{{ $lang->id }}[]" value="{{ $content_array[$i]['url'] ?? null }}">
                                            <div class="form-text">{{ __('If you add URL, a button with "Read more" will be added.') }}</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Content') }}</label>
                                            <textarea class="form-control trumbowyg" name="content_{{ $lang->id }}[]">{{ $content_array[$i]['content'] ?? null }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                                            <input class="form-control" type="file" id="formFile" name="image_{{ $lang->id }}[]" multiple>
                                            <div class="form-text small">{{ __("If you do not add an image, the slider text will be displayed in full width") }}</div>
                                        </div>
                                        @if ($content_array[$i]['image'] ?? null)
                                            <a target="_blank" href="{{ image($content_array[$i]['image']) }}"><img style="max-width: 300px; max-height: 100px;"
                                                    src="{{ image($content_array[$i]['image']) }}" class="img-fluid"></a>
                                            <input type="hidden" name="existing_image_{{ $lang->id }}[]" value="{{ $content_array[$i]['image'] ?? null }}">
                                        @endif
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Position') }}</label>
                                            <input type="text" class="form-control" name="position_{{ $lang->id }}[]" value="{{ $content_array[$i]['position'] ?? null }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Read more button style') }}</label>
                                            <select class="form-select" name="btn_style_{{ $lang->id }}[]">
                                                <option @if (($content_array[$i]['btn_style'] ?? null) == 'btn1') selected @endif value="btn1">{{ __('Primary button') }}</option>
                                                <option @if (($content_array[$i]['btn_style'] ?? null) == 'btn2') selected @endif value="btn2">{{ __('Secondary button') }}</option>
                                                <option @if (($content_array[$i]['btn_style'] ?? null) == 'btn3') selected @endif value="btn3">{{ __('Tertiary button') }}</option>
                                            </select>
                                            <div class="form-text">{{ __('You can edit buttons look in template section') }}</div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="mb-4"></div>
                        @endfor
                    @endif

                    <div class="mb-3 mt-3">
                        <button type="button" class="btn btn-light addButton_{{ $lang->id }}"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
                    </div>

                    <!-- The template for adding new item -->
                    <div class="form-group hide" id="ItemTemplate_{{ $lang->id }}">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('Title') }}</label>
                                    <input type="text" class="form-control" name="title_{{ $lang->id }}[]" />
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('URL') }}</label>
                                    <input type="text" class="form-control" name="url_{{ $lang->id }}[]" />
                                    <div class="form-text">{{ __('If you add URL, a button with "Read more" will be added.') }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('Content') }}</label>
                                    <textarea class="form-control trumbowyg_{{ $lang->id }}" name="content_{{ $lang->id }}[]"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                                    <input class="form-control" type="file" id="formFile" name="image_{{ $lang->id }}[]" multiple>
                                    <div class="form-text small">{{ __("If you do not add an image, the slider text will be displayed in full width") }}</div>
                                </div>
                            </div>

                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label>{{ __('Position') }}</label>
                                    <input type="text" class="form-control" name="position_{{ $lang->id }}[]" />
                                </div>
                            </div>

                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label>{{ __('Read more button style') }}</label>
                                    <select class="form-select" name="btn_style_{{ $lang->id }}[]">
                                        <option value="btn1">{{ __('Primary button') }}</option>
                                        <option value="btn2">{{ __('Secondary button') }}</option>
                                        <option value="btn3">{{ __('Tertiary button') }}</option>
                                    </select>
                                    <div class="form-text">{{ __('You can edit buttons look in template section') }}</div>
                                </div>
                            </div>

                        </div>
                        <div class="mb-4"></div>
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
                                        .find('[name="title_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].title_{{ $lang->id }}').end()
                                        .find('[name="url_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].url_{{ $lang->id }}').end()
                                        .find('[name="content_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].content_{{ $lang->id }}').end()
                                        .find('[name="image_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].image_{{ $lang->id }}').end()
                                        .find('[name="position_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].position_{{ $lang->id }}').end()
                                        .find('[name="btn_style_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].btn_style_{{ $lang->id }}').end()
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
                    <input type="hidden" name="existing_bg_image" value="{{ $block_extra['bg_image'] ?? null }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
