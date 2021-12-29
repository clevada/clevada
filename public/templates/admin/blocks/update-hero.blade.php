@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage content block') }}</li>
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
                    <h4 class="card-title">{{ __('Edit block') }} ({{ $block->type_label }})</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            <form method="post" enctype="multipart/form-data">
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

                    <div class="form-group mb-3">
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
                                document.getElementById('hidden_div_bg_color_select').style.display = 'block';
                            else
                                document.getElementById('hidden_div_bg_color_select').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div_bg_color_select" style="display: @if (isset($block_extra['bg_color'])) block @else none @endif" class="mb-2">
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

                    <div class="form-group mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_image" name="use_image" @if ($block_extra['use_image'] ?? null) checked @endif>
                            <label class="form-check-label" for="use_image">{{ __('Use image') }}</label>
                        </div>
                    </div>

                    <script>
                        $('#use_image').change(function() {
                            select = $(this).prop('checked');
                            if (select) {
                                document.getElementById('hidden_div_image').style.display = 'block';
                                document.getElementById('hidden_div_bg_color').style.display = 'none';
                            } else {
                                document.getElementById('hidden_div_image').style.display = 'none';
                                document.getElementById('hidden_div_bg_color').style.display = 'block';
                            }
                        })
                    </script>

                    <div id="hidden_div_image" style="display: @if (isset($block_extra['use_image'])) block @else none @endif" class="mt-2">

                        <div class="form-group col-md-4 col-xl-2">
                            <label>{{ __('Image position') }}</label>
                            <select class="form-select" name="image_position" id="image_position" onchange="change_image_position()">
                                <option @if (($block_extra['image_position'] ?? null) == 'top') selected @endif value="top">{{ __('Top (above text content)') }}</option>
                                <option @if (($block_extra['image_position'] ?? null) == 'bottom') selected @endif value="bottom">{{ __('Bottom (below text content)') }}</option>
                                <option @if (($block_extra['image_position'] ?? null) == 'right') selected @endif value="right">{{ __('Right') }}</option>
                                <option @if (($block_extra['image_position'] ?? null) == 'left') selected @endif value="left">{{ __('Left') }}</option>
                                <option @if (($block_extra['image_position'] ?? null) == 'cover') selected @endif value="cover">{{ __('Background cover') }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="formFile" class="form-label">{{ __('Image') }}</label>
                            <input class="form-control" type="file" id="formFile" name="image">
                        </div>
                        @if ($block_extra['image'] ?? null)
                            <a target="_blank" href="{{ image($block_extra['image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($block_extra['image']) }}"
                                    class="img-fluid"></a>
                        @endif

                        <script>
                            function change_image_position() {
                                var select = document.getElementById('image_position');
                                var value = select.options[select.selectedIndex].value;
                                if (value == 'cover') {
                                    document.getElementById('hidden_div_not_cover').style.display = 'none';
                                    document.getElementById('hidden_div_cover').style.display = 'block';
                                } else {
                                    document.getElementById('hidden_div_not_cover').style.display = 'block';
                                    document.getElementById('hidden_div_cover').style.display = 'none';
                                }

                            }
                        </script>

                        <div id="hidden_div_not_cover" style="display: @if (($block_extra['image_position'] ?? null) == 'cover') none @else block @endif" class="mt-4">
                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="shaddow" name="shaddow" @if ($block_extra['shaddow'] ?? null) checked @endif>
                                    <label class="form-check-label" for="shaddow">{{ __('Add shaddow to image') }}</label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="img_click" name="img_click" @if ($block_extra['img_click'] ?? null) checked @endif>
                                    <label class="form-check-label" for="img_click">{{ __('Click on image to show original size image') }}</label>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-3 col-xl-2 col-12 form-group mt-2">
                                <label class="form-label">{{ __('Image width') }}</label>
                                <select name="img_container_width" class="form-select">
                                    <option @if (($block_extra['img_container_width'] ?? null) == 'col-12') selected @endif value="col-12">{{ __('Full width') }}</option>
                                    <option @if (($block_extra['img_container_width'] ?? null) == 'col-12 col-md-8 offset-md-2') selected @endif value="col-12 col-md-8 offset-md-2">{{ __('75%') }}</option>
                                    <option @if (($block_extra['img_container_width'] ?? null) == 'col-12 col-md-6 offset-md-3') selected @endif value="col-12 col-md-6 offset-md-3">{{ __('50%') }}</option>
                                </select>
                            </div>                           
                        </div>

                        <div id="hidden_div_cover" style="display: @if (($block_extra['use_image'] ?? null) && ($block_extra['image_position'] ?? null) == 'cover')) block @else none @endif" class="mt-4">
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
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-4 col-lg-3 col-xl-2 col-12 form-group mt-2">
                            @php
                                $title_font_size = $block_extra['title_font_size'] ?? config('defaults.h4_size');
                            @endphp

                            <label>{{ __('Title font size') }}</label>
                            <select class="form-select" name="title_font_size">
                                @foreach (template_font_sizes() as $selected_font_size_title)
                                    <option @if ($title_font_size == $selected_font_size_title->value) selected @endif value="{{ $selected_font_size_title->value }}">{{ $selected_font_size_title->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-3 col-xl-2 col-12 form-group mt-2">
                            @php
                                $text_font_size = $block_extra['text_font_size'] ?? config('defaults.font_size');
                            @endphp

                            <label>{{ __('Text font size') }}</label>
                            <select class="form-select" name="text_font_size">
                                @foreach (template_font_sizes() as $selected_font_size_title)
                                    <option @if ($text_font_size == $selected_font_size_title->value) selected @endif value="{{ $selected_font_size_title->value }}">{{ $selected_font_size_title->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-xl-2 col-lg-3 col-sm-12 mt-2">
                        <input class="form-control form-control-color" id="font_color" name="font_color" value="{{ $block_extra['font_color'] ?? '#000000' }}">
                        <label>{{ __('Text color') }}</label>
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

                    <div class="form-group">
                        <label>{{ __('Title') }}</label>
                        <input class="form-control" name="title_{{ $lang->id }}" value="{{ $content_array['title'] ?? null }}">
                    </div>

                    <div class="form-group">
                        <label>{{ __('Content') }}</label>
                        <textarea class="form-control trumbowyg" name="content_{{ $lang->id }}">{{ $content_array['content'] ?? null }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-lg-3 col-xl-2">
                            <div class="form-group">
                                <label>{{ __('Button 1 label') }}</label>
                                <input type="text" class="form-control" name="btn1_label_{{ $lang->id }}" value="{{ $content_array['btn1_label'] ?? null }}">
                                <div class="form-text">{{ __('Leave empty to hide button') }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3 col-xl-2">
                            <div class="form-group">
                                <label>{{ __('Button 1 URL') }}</label>
                                <input type="text" class="form-control" name="btn1_url_{{ $lang->id }}" value="{{ $content_array['btn1_url'] ?? null }}">
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3 col-xl-2">
                            <div class="form-group">
                                <label>{{ __('Button 1 style') }}</label>
                                <select class="form-select" name="btn1_style_{{ $lang->id }}">
                                    <option @if (($content_array['btn1_style'] ?? null) == 'btn1') selected @endif value="btn1">{{ __('Primary button') }}</option>
                                    <option @if (($content_array['btn1_style'] ?? null) == 'btn2') selected @endif value="btn2">{{ __('Secondary button') }}</option>
                                    <option @if (($content_array['btn1_style'] ?? null) == 'btn3') selected @endif value="btn3">{{ __('Tertiary button') }}</option>
                                </select>
                                <div class="form-text">{{ __('You can edit buttons look in template section') }}</div>
                            </div>
                        </div>

                         <div class="col-md-4 col-lg-3 col-xl-4">
                            <div class="form-group">
                                <label>{{ __('Button 1 info text') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="btn1_info_{{ $lang->id }}" value="{{ $content_array['btn1_info'] ?? null }}">
                                <div class="form-text">{{ __('You can add a text to display under the button') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-lg-3 col-xl-2">
                            <div class="form-group">
                                <label>{{ __('Button 2 label') }}</label>
                                <input type="text" class="form-control" name="btn2_label_{{ $lang->id }}" value="{{ $content_array['btn2_label'] ?? null }}">
                                <div class="form-text">{{ __('Leave empty to hide button') }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3 col-xl-2">
                            <div class="form-group">
                                <label>{{ __('Button 2 URL') }}</label>
                                <input type="text" class="form-control" name="btn2_url_{{ $lang->id }}" value="{{ $content_array['btn2_url'] ?? null }}">
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3 col-xl-2">
                            <div class="form-group">
                                <label>{{ __('Button 2 style') }}</label>
                                <select class="form-select" name="btn2_style_{{ $lang->id }}">
                                    <option @if (($content_array['btn2_style'] ?? null) == 'btn1') selected @endif value="btn1">{{ __('Primary button') }}</option>
                                    <option @if (($content_array['btn2_style'] ?? null) == 'btn2') selected @endif value="btn2">{{ __('Secondary button') }}</option>
                                    <option @if (($content_array['btn2_style'] ?? null) == 'btn3') selected @endif value="btn3">{{ __('Tertiary button') }}</option>
                                </select>
                                <div class="form-text">{{ __('You can edit buttons look in template section') }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3 col-xl-4">
                            <div class="form-group">
                                <label>{{ __('Button 2 info text') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="btn2_info_{{ $lang->id }}" value="{{ $content_array['btn2_info'] ?? null }}">
                                <div class="form-text">{{ __('You can add a text to display under the button') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4"></div>

                    @php
                        if ($block_module == 'posts') {
                            break;
                        }
                    @endphp

                    @if (count(sys_langs()) > 1 && !$loop->last)<hr>@endif

                @endforeach


                <div class="form-group">
                    <input type="hidden" name="existing_image" value="{{ $block_extra['image'] ?? null }}">
                    <input type="hidden" name="type_id" value="{{ $block->type_id }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update block') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
