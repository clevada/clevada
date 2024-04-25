@include('admin.template.includes.import-fonts')
@include('admin.includes.color-picker')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template') }}">{{ __('Template') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template.styles') }}">{{ __('Styles') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $style->label }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                @include('admin.template.includes.menu-template')
            </div>

        </div>

    </div>


    <div class="card-body">

        <div class="mt-2 mb-2 fs-5">{{ __('Edit custom style') }}: <b>{{ $style->label }}</b></div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'updated')
                    <div class="fw-bold">{{ __('Updated') }}</div>
                    <i class="bi bi-exclamation-circle"></i>
                    {{ __("Note: If you don't see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.") }}
                @endif
                @if ($message == 'created')
                    <h4 class="alert-heading">{{ __('Created') }}</h4>
                @endif
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'no_label')
                    {{ __('Error. Input label') }}
                @endif
                @if ($message == 'duplicate')
                    {{ __('Error. This style exists') }}
                @endif
            </div>
        @endif

        @php
            if ($style->link_decoration == 'none') {
                $text_decoration = 'none';
            } else {
                $text_decoration = 'underline';
            }
            if ($style->link_hover_decoration == 'none') {
                $text_decoration_hover = 'none';
            } else {
                $text_decoration_hover = 'underline';
            }
        @endphp

        

        <a target="_blank" class="btn btn-gear btn-lg" href="{{ route('admin.preview-style', ['id' => $style->id]) }}">{{ __('Preview style') }}</a>

        <div class="form-text text-muted small mt-3"><i class="bi bi-info-circle"></i> {{ __('Update style to preview using new changes') }}</div>

        <hr>

        <form method="post">
            @csrf
            @method('PUT')


            <div class="form-group mb-4">
                <div class="col-12 col-lg-3 col-md-4">
                    <div class="form-group">
                        <label>{{ __('Label') }}</label>
                        <input class="form-control" name="label" type="text" required value="{{ $style->label }}" />
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-6 col-md-6 col-12">
                    <div class="form-group">
                        <label>{{ __('Font family') }}</label>
                        <select class="form-select" name="font_family">
                            @foreach ($fonts as $font)
                                <option @if ($style->font_family == $font->value) selected @endif value="{{ $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">[{{ $font->name }}]
                                    Almost before we knew it, we had left the ground.</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Text align') }}</label>
                        <select class="form-select" name="text_align">
                            <option @if ($style->text_align == 'left') selected @endif value="left">{{ __('Left') }}</option>
                            <option @if ($style->text_align == 'right') selected @endif value="right">{{ __('Right') }}</option>
                            <option @if ($style->text_align == 'center') selected @endif value="center">{{ __('Center') }}</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="fw-bold fs-5 mb-2">{{ __('Colors') }}</div>

            <div class="row">
                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <input id="text_color" name="text_color" value="{{ $style->text_color ?? config('clevada.defaults.font_color') }}">
                        <label>{{ __('Main text color') }}</label>
                        <div class="mt-1 small"> {{ strtoupper($style->text_color) ?? config('clevada.defaults.font_color') }}</div>
                        <script>
                            $('#text_color').spectrum({
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

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <input id="link_color" name="link_color" value="{{ $style->link_color ?? config('clevada.defaults.link_color') }}">
                        <label>{{ __('Link color') }}</label>
                        <div class="mt-1 small"> {{ strtoupper($style->link_color) ?? config('clevada.defaults.link_color') }}</div>
                        <script>
                            $('#link_color').spectrum({
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

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <input id="link_hover_color" name="link_hover_color" value="{{ $style->link_hover_color ?? config('clevada.defaults.link_color_hover') }}">
                        <label>{{ __('Link color on mouse hover') }}</label>
                        <div class="mt-1 small"> {{ strtoupper($style->link_hover_color) ?? config('clevada.defaults.link_color_hover') }}</div>
                        <script>
                            $('#link_hover_color').spectrum({
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

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <input id="link_underline_color" name="link_underline_color" value="{{ $style->link_underline_color ?? config('clevada.defaults.link_color_underline') }}">
                        <label>{{ __('Underline color') }}</label>
                        <div class="mt-1 small"> {{ strtoupper($style->link_underline_color) ?? config('clevada.defaults.link_color_underline') }}</div>
                        <script>
                            $('#link_underline_color').spectrum({
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

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <input id="link_underline_color_hover" name="link_underline_color_hover" value="{{ $style->link_underline_color_hover ?? config('clevada.defaults.link_color_underline_hover') }}">
                        <label>{{ __('Underline color on hover') }}</label>
                        <div class="mt-1 small"> {{ strtoupper($style->link_underline_color_hover) ?? config('clevada.defaults.link_color_underline_hover') }}</div>
                        <script>
                            $('#link_underline_color_hover').spectrum({
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

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <input id="caption_color" name="caption_color" value="{{ $style->caption_color ?? 'grey' }}">
                        <label>{{ __('Caption text color') }}</label>
                        <div class="mt-1 small"> {{ strtoupper($style->caption_color) ?? config('clevada.defaults.link_color_underline_hover') }}</div>
                        <script>
                            $('#caption_color').spectrum({
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

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_custom_bg" name="use_custom_bg" @if ($style->bg_color ?? null) checked @endif>
                            <label class="form-check-label" for="use_custom_bg">
                                @if ($style->is_default == 1)
                                    {{ __('Body background color') }}@else{{ __('Use custom background color') }}
                                @endif
                            </label>
                        </div>
                        <div class="form-text">
                            {{ __('This is the color of the section row (full width) who use this style. If disabled, default section background color will be used.') }}
                        </div>
                    </div>
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

                <div class="col-sm-4 col-md-3 col-12">
                    <div id="hidden_div_color" style="display: @if ($style->bg_color ?? null) block @else none @endif" class="mt-1">
                        <div class="form-group mb-4">
                            <input id="bg_color" name="bg_color" value="{{ $style->bg_color ?? '#ffffff' }}">
                            <label>{{ __('Background color') }}</label>
                            <div class="mt-1 small"> {{ strtoupper($style->bg_color) ?? '#ffffff' }}</div>
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
                </div>
            </div>


            <div class="fw-bold fs-5 mb-2">{{ __('Sizes') }}</div>

            <div class="row">
                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Text font size') }}</label>
                        <select class="form-select" name="text_size">
                            @foreach ($font_sizes as $font_size)
                                <option @if ($style->text_size == $font_size->value) selected @endif @if (!$style->text_size && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Title font size') }}</label>
                        <select class="form-select" name="title_size">
                            @foreach ($font_sizes as $font_size)
                                <option @if ($style->title_size == $font_size->value) selected @endif @if (!$style->title_size && $font_size->value == '1.4rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Subtitle font size') }}</label>
                        <select class="form-select" name="subtitle_size">
                            @foreach ($font_sizes as $font_size)
                                <option @if ($style->subtitle_size == $font_size->value) selected @endif @if (!$style->subtitle_size && $font_size->value == '1.2rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Caption font size') }}</label>
                        <select class="form-select" name="caption_size">
                            @foreach ($font_sizes as $font_size)
                                <option @if ($style->caption_size == $font_size->value) selected @endif @if (!$style->caption_size && $font_size->value == '0.95rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <div class="fw-bold fs-5 mb-2">{{ __('Font weights') }}</div>

            <div class="row">
                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Font weight (text)') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="text_font_weight">
                            <option @if ($style->text_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                            <option @if ($style->text_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Font weight (links)') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_font_weight">
                            <option @if ($style->link_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                            <option @if ($style->link_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Font weight (title)') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="title_font_weight">
                            <option @if ($style->title_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                            <option @if ($style->title_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Font weight (subtitle)') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="subtitle_font_weight">
                            <option @if ($style->subtitle_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                            <option @if ($style->subtitle_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                        </select>
                    </div>
                </div>


                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Caption font style') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="caption_style">
                            <option @if ($style->caption_style == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                            <option @if ($style->caption_style == 'italic') selected @endif value="italic">{{ __('Italic') }}</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="fw-bold fs-5 mb-2">{{ __('Links underline') }}</div>

            <div class="row">
                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Link decoration') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_decoration">
                            <option @if ($style->link_decoration == 'none') selected @endif value="none">{{ __('None') }}</option>
                            <option @if ($style->link_decoration == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                            <option @if ($style->link_decoration == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                            <option @if ($style->link_decoration == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Link decoration on hover') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_hover_decoration">
                            <option @if ($style->link_hover_decoration == 'none') selected @endif value="none">{{ __('None') }}</option>
                            <option @if ($style->link_hover_decoration == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                            <option @if ($style->link_hover_decoration == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                            <option @if ($style->link_hover_decoration == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Underline line thickness') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_underline_thickness">
                            <option @if ($style->link_underline_thickness == 'auto') selected @endif value="auto">{{ __('Normal') }}</option>
                            <option @if ($style->link_underline_thickness == '3px') selected @endif value="3px">{{ __('Bold') }}</option>
                            <option @if ($style->link_underline_thickness == '6px') selected @endif value="6px">{{ __('Bolder') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 col-md-3 col-12">
                    <div class="form-group mb-4">
                        <label>{{ __('Underline offset') }}</label>
                        <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_underline_offset">
                            <option @if ($style->link_underline_offset == 'auto') selected @endif value="auto">{{ __('Normal (no offset)') }}</option>
                            <option @if ($style->link_underline_offset == '0.17em') selected @endif value="0.17em">{{ __('Small offset') }}</option>
                            <option @if ($style->link_underline_offset == '0.35em') selected @endif value="0.35em">{{ __('Medium offset') }}</option>
                            <option @if ($style->link_underline_offset == '0.6em') selected @endif value="0.6em">{{ __('Big offset') }}</option>
                        </select>
                        <div class="text-muted small">{{ __('Distance between link text and underline') }}</div>
                    </div>
                </div>
            </div>




            <button type="submit" class="btn btn-primary">{{ __('Update style') }}</button>

        </form>

    </div>
    <!-- end card-body -->

</div>
