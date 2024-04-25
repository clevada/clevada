<hr>

<div class="form-group mb-3">
    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSettings"  aria-expanded="false" aria-controls="collapseSettings"><i class="bi bi-chevron-down"></i>
        {{ __('Block style settings') }}</button>
    <div class="small text-muted mt-1">{{ __('Manage font styles, sizes, colors, background, spacing and more...') }}</div>
</div>

<div class="collapse" id="collapseSettings">
    
    <div class="card card-body">

        <div class="form-group mb-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="use_custom_bg" name="use_custom_bg" @if ($block_extra['bg_color'] ?? null) checked @endif>
                <label class="form-check-label" for="use_custom_bg">{{ __('Section custom background color') }}</label>
            </div>
            <div class="form-text">{{ __('If disabled, default website background color is used.') }}</div>
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
                        hideAfterPaletteSelect: true,
                        showInput: false,
                        showInitial: true,
                        showAlpha: false,
                        showButtons: true,
                        allowEmpty: false,
                    });
                </script>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 col-12 form-group mt-2">
                <label class="form-label">{{ __('Padding top') }}</label>
                <select name="padding_top" class="form-select">
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-0') selected @endif value="pt-0">{{ __('No space') }}</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-1') selected @endif value="pt-1">10px</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-2') selected @endif value="pt-2">20px</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-3') selected @endif value="pt-3">30px</option>
                    <option @if (!isset($block_extra['padding_top']) || ($block_extra['padding_top'] ?? null) == 'pt-4') selected @endif value="pt-4">40px ({{ __('default') }})</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-5') selected @endif value="pt-5">50px</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-10') selected @endif value="pt-10">100px</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-15') selected @endif value="pt-15">150px</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-20') selected @endif value="pt-20">200px</option>
                    <option @if (($block_extra['padding_top'] ?? null) == 'pt-30') selected @endif value="pt-30">300px</option>
                </select>
                <div class="form-text">{{ __('Section top space') }}</div>
            </div>

            <div class="col-md-6 col-12 form-group mt-2">
                <label class="form-label">{{ __('Padding bottom') }}</label>
                <select name="padding_bottom" class="form-select">
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-0') selected @endif value="pt-0">{{ __('No space') }}</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-1') selected @endif value="pt-1">10px</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-2') selected @endif value="pt-2">20px</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-3') selected @endif value="pt-3">30px</option>
                    <option @if (!isset($block_extra['padding_bottom']) || ($block_extra['padding_bottom'] ?? null) == 'pt-4') selected @endif value="pt-4">40px ({{ __('default') }})</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-5') selected @endif value="pt-5">50px</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-10') selected @endif value="pt-10">100px</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-15') selected @endif value="pt-15">150px</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-20') selected @endif value="pt-20">200px</option>
                    <option @if (($block_extra['padding_bottom'] ?? null) == 'pt-30') selected @endif value="pt-30">300px</option>
                </select>
                <div class="form-text">{{ __('Section bottom space') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-12 form-group mt-2">
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

            <div class="col-md-6 col-12 form-group mt-2">
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

        <div class="row">
            <div class="col-md-4 col-12 form-group mt-2">
                <input class="form-control form-control-color" id="title_font_color" name="title_font_color" value="{{ $block_extra['title_font_color'] ?? '#444444' }}">
                <label>{{ __('Title font color') }}</label>
                <script>
                    $('#title_font_color').spectrum({
                        type: "color",
                        hideAfterPaletteSelect: true,
                        showInput: true,
                        showInitial: true,
                        showAlpha: false,
                        showButtons: true,
                        allowEmpty: false,
                    });                   
                </script>
            </div>

            <div class="col-md-4 col-12 form-group mt-2">
                <input class="form-control form-control-color" id="text_font_color" name="text_font_color" value="{{ $block_extra['text_font_color'] ?? '#444444' }}">
                <label>{{ __('Text font color') }}</label>
                <script>
                    $('#text_font_color').spectrum({
                        type: "color",
                        hideAfterPaletteSelect: true,
                        showInput: false,
                        showInitial: true,
                        showAlpha: false,
                        showButtons: true,
                        allowEmpty: false,
                    });
                </script>
            </div>
        </div>


        <div class="row">
            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label>{{ __('Links color') }}</label><br>
                    <input id="link_color" name="link_color" value="{{ $block_extra['link_color'] ?? config('defaults.link_color') }}">
                    <script>
                        $('#link_color').spectrum({
                            type: "color",
                            hideAfterPaletteSelect: true,
                            showInput: false,
                            showInitial: true,
                            showAlpha: false,
                            showButtons: true,
                            allowEmpty: false,
                        });
                    </script>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label>{{ __('Links color on hover') }}</label><br>
                    <input id="link_color_hover" name="link_color_hover" value="{{ $block_extra['link_color_hover'] ?? config('defaults.link_color_hover') }}">
                    <script>
                        $('#link_color_hover').spectrum({
                            type: "color",
                            hideAfterPaletteSelect: true,
                            showInput: false,
                            showInitial: true,
                            showAlpha: false,
                            showButtons: true,
                            allowEmpty: false,
                        });
                    </script>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label>{{ __('Links underline color') }}</label><br>
                    <input id="link_color_underline" name="link_color_underline" value="{{ $block_extra['link_color_underline'] ?? config('defaults.link_color_hover') }}">
                    <script>
                        $('#link_color_underline').spectrum({
                            type: "color",
                            hideAfterPaletteSelect: true,
                            showInput: false,
                            showInitial: true,
                            showAlpha: false,
                            showButtons: true,
                            allowEmpty: false,
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>{{ __('Links decoration') }}</label>
                    <select class="form-select" name="link_decoration">
                        <option @if (($block_extra['link_decoration'] ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                        <option @if (($block_extra['link_decoration'] ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-6 ">
                <div class="form-group">
                    <label>{{ __('Decoration on hover') }}</label>
                    <select class="form-select" name="link_hover_decoration">
                        <option @if (($block_extra['link_hover_decoration'] ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                        <option @if (($block_extra['link_hover_decoration'] ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                    </select>
                </div>
            </div>
        </div>



        <div class="form-group mt-2 mb-0">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="use_cover" name="use_cover" @if ($block_extra['use_cover'] ?? null) checked @endif>
                <label class="form-check-label" for="use_cover">{{ __('Use cover image') }}</label>
            </div>
            <div class="form-text">{{ __('Add a cover background image for this section') }}</div>
        </div>

        <script>
            $('#use_cover').change(function() {
                select = $(this).prop('checked');
                if (select) {
                    document.getElementById('hidden_div_cover').style.display = 'block';
                } else {
                    document.getElementById('hidden_div_cover').style.display = 'none';
                }
            })
        </script>

        <div id="hidden_div_cover" style="display: @if (isset($block_extra['use_cover'])) block @else none @endif" class="mt-2">
            <div class="form-group col-12">
                <label for="cover" class="form-label">{{ __('Image') }}</label>
                <input class="form-control" type="file" id="cover" name="cover">
            </div>
            @if ($block_extra['cover'] ?? null)
                <a target="_blank" href="{{ image($block_extra['cover']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($block_extra['cover']) }}" class="img-fluid"></a>
            @endif

            <div class="form-group col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cover_dark" name="cover_dark" @if ($block_extra['cover_dark'] ?? null) checked @endif>
                    <label class="form-check-label" for="cover_dark">{{ __('Add dark layer to background cover') }}</label>
                </div>
            </div>

            <div class="form-group col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cover_fixed" name="cover_fixed" @if ($block_extra['cover_fixed'] ?? null) checked @endif>
                    <label class="form-check-label" for="cover_fixed">{{ __('Fixed background image') }}</label>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="form-group mb-0">
    <button type="submit" class="btn btn-primary">{{ __('Update block') }}</button>
</div>
