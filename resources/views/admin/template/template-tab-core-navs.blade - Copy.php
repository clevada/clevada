@include('admin.template.includes.import-fonts')
@include('admin.includes.color-picker')


<form method="post">
    @csrf
    @method('PUT')

    <div class="fw-bold mb-3 fs-5">{{ __('Main navigation menu') }}</div>

    <div class="row">
        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group">
                <label>{{ __('Navigation menu height') }}</label>
                <select class="form-select" name="style_navbar_height">
                    <option @if (($templateConfig->style_navbar_height ?? null) == '65px') selected @endif value="65px">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_navbar_height ?? null) == '50px') selected @endif value="50px">{{ __('Small') }}</option>
                    <option @if (($templateConfig->style_navbar_height ?? null) == '90px') selected @endif value="90px">{{ __('Large') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font size') }}</label>
                <select class="form-select" name="style_navbar_text_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_navbar_text_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_navbar_text_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Font weight') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_font_weight">
                    <option @if (($templateConfig->style_navbar_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_navbar_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_link_decoration">
                    <option @if (($templateConfig->style_navbar_link_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_navbar_link_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                    <option @if (($templateConfig->style_navbar_link_decoration ?? null) == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                    <option @if (($templateConfig->style_navbar_link_decoration ?? null) == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration on hover') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_link_hover_decoration">
                    <option @if (($templateConfig->style_navbar_link_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_navbar_link_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                    <option @if (($templateConfig->style_navbar_link_hover_decoration ?? null) == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                    <option @if (($templateConfig->style_navbar_link_hover_decoration ?? null) == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Underline line thickness') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_link_underline_thickness">
                    <option @if (($templateConfig->style_navbar_link_underline_thickness ?? null) == 'auto') selected @endif value="auto">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_navbar_link_underline_thickness ?? null) == '3px') selected @endif value="3px">{{ __('Bold') }}</option>
                    <option @if (($templateConfig->style_navbar_link_underline_thickness ?? null) == '6px') selected @endif value="6px">{{ __('Bolder') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Underline offset') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_link_underline_offset">
                    <option @if (($templateConfig->style_navbar_link_underline_offset ?? null) == 'auto') selected @endif value="auto">{{ __('Normal (no offset)') }}</option>
                    <option @if (($templateConfig->style_navbar_link_underline_offset ?? null) == '0.17em') selected @endif value="0.17em">{{ __('Small offset') }}</option>
                    <option @if (($templateConfig->style_navbar_link_underline_offset ?? null) == '0.35em') selected @endif value="0.35em">{{ __('Medium offset') }}</option>
                    <option @if (($templateConfig->style_navbar_link_underline_offset ?? null) == '0.6em') selected @endif value="0.6em">{{ __('Big offset') }}</option>
                </select>
            </div>
        </div>

    </div>

    <div class="row  mt-3">
        <div class="col-sm-4 col-md-3 col-12">
            <div class="form-group mb-2">
                <input id="style_navbar_bg_color" name="style_navbar_bg_color" value="{{ $templateConfig->style_navbar_bg_color ?? 'white' }}">
                <label>{{ __('Background color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_navbar_bg_color ?? 'white' }}</div>
                <script>
                    $('#style_navbar_bg_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_navbar_link_color" name="style_navbar_link_color" value="{{ $templateConfig->style_navbar_link_color ?? 'blue' }}">
                <label>{{ __('Links color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_navbar_link_color ?? 'blue' }}</div>
                <script>
                    $('#style_navbar_link_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_navbar_link_hover_color" name="style_navbar_link_hover_color" value="{{ $templateConfig->style_navbar_link_hover_color ?? 'blue' }}">
                <label>{{ __('Link color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_navbar_link_hover_color ?? 'blue' }}</div>
                <script>
                    $('#style_navbar_link_hover_color').spectrum({
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

    <hr>



    <div class="fw-bold mb-3 fs-5">{{ __('Navigation dropdown menu') }}</div>

    <div class="row">

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font size') }}</label>
                <select class="form-select" name="style_navbar_dropdown_text_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_navbar_dropdown_text_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_navbar_dropdown_text_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>



        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Font weight') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_dropdown_font_weight">
                    <option @if (($templateConfig->style_navbar_dropdown_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_navbar_dropdown_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_dropdown_link_decoration">
                    <option @if (($templateConfig->style_navbar_dropdown_link_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_navbar_dropdown_link_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                    <option @if (($templateConfig->style_navbar_dropdown_link_decoration ?? null) == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                    <option @if (($templateConfig->style_navbar_dropdown_link_decoration ?? null) == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration on hover') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_navbar_dropdown_link_hover_decoration">
                    <option @if (($templateConfig->style_navbar_dropdown_link_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_navbar_dropdown_link_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                    <option @if (($templateConfig->style_navbar_dropdown_link_hover_decoration ?? null) == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                    <option @if (($templateConfig->style_navbar_dropdown_link_hover_decoration ?? null) == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                </select>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-sm-4 col-md-3 col-12">
            <div class="form-group mb-2">
                <input id="style_navbar_dropdown_bg_color" name="style_navbar_dropdown_bg_color" value="{{ $templateConfig->style_navbar_dropdown_bg_color ?? '#ededed' }}">
                <label>{{ __('Background color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_navbar_dropdown_bg_color ?? '#ededed' }}</div>
                <script>
                    $('#style_navbar_dropdown_bg_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_navbar_dropdown_link_color" name="style_navbar_dropdown_link_color" value="{{ $templateConfig->style_navbar_dropdown_link_color ?? 'blue' }}">
                <label>{{ __('Links color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_navbar_dropdown_link_color ?? 'blue' }}</div>
                <script>
                    $('#style_navbar_dropdown_link_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_navbar_dropdown_bg_color_hover" name="style_navbar_dropdown_bg_color_hover" value="{{ $templateConfig->style_navbar_dropdown_bg_color_hover ?? '#d7d7d7' }}">
                <label>{{ __('Background color (on mouse hover)') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_navbar_dropdown_bg_color_hover ?? '#d7d7d7' }}</div>
                <script>
                    $('#style_navbar_dropdown_bg_color_hover').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_navbar_dropdown_link_hover_color" name="style_navbar_dropdown_link_hover_color" value="{{ $templateConfig->style_navbar_dropdown_link_hover_color ?? 'blue' }}">
                <label>{{ __('Link color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_navbar_dropdown_link_hover_color ?? 'blue' }}</div>
                <script>
                    $('#style_navbar_dropdown_link_hover_color').spectrum({
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

    <hr>



    <div class="fw-bold mb-3 fs-5">{{ __('Main footer') }}</div>

    <div class="row">

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font size (text and links)') }}</label>
                <select class="form-select" name="style_footer_text_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_footer_text_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_footer_text_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font size (titles and headings)') }}</label>
                <select class="form-select" name="style_footer_title_size">
                    @foreach ($font_sizes as $title_size)
                        <option @if (($templateConfig->style_footer_title_size ?? null) == $title_size->value) selected @endif @if (!($templateConfig->style_footer_title_size ?? null) && $title_size->value == '1.2rem') selected @endif value="{{ $title_size->value }}">{{ $title_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font weight (titles and headings)') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer_title_weight">
                    <option @if (($templateConfig->style_footer_title_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_footer_title_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font weight (links)') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer_link_weight">
                    <option @if (($templateConfig->style_footer_link_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_footer_link_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Link decoration') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer_link_decoration">
                    <option @if (($templateConfig->style_footer_link_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_footer_link_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Link decoration on hover') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer_link_hover_decoration">
                    <option @if (($templateConfig->style_footer_link_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_footer_link_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-sm-4 col-md-3 col-12">
            <div class="form-group mb-2">
                <input id="style_footer_bg_color" name="style_footer_bg_color" value="{{ $templateConfig->style_footer_bg_color ?? 'white' }}">
                <label>{{ __('Background color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer_bg_color ?? 'white' }}</div>
                <script>
                    $('#style_footer_bg_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_footer_text_color" name="style_footer_text_color" value="{{ $templateConfig->style_footer_text_color ?? 'black' }}">
                <label>{{ __('Text color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer_text_color ?? 'black' }}</div>
                <script>
                    $('#style_footer_text_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_footer_link_color" name="style_footer_link_color" value="{{ $templateConfig->style_footer_link_color ?? 'blue' }}">
                <label>{{ __('Links color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer_link_color ?? 'blue' }}</div>
                <script>
                    $('#style_footer_link_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_footer_link_hover_color" name="style_footer_link_hover_color" value="{{ $templateConfig->style_footer_link_hover_color ?? 'blue' }}">
                <label>{{ __('Link color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer_link_hover_color ?? 'blue' }}</div>
                <script>
                    $('#style_footer_link_hover_color').spectrum({
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

    <hr>



    <div class="fw-bold mb-3 fs-5">{{ __('Secondary footer (if enabled)') }}</div>


    <div class="row">

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font size (text and links)') }}</label>
                <select class="form-select" name="style_footer2_text_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_footer2_text_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_footer2_text_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font size (titles and headings)') }}</label>
                <select class="form-select" name="style_footer2_title_size">
                    @foreach ($font_sizes as $title_size)
                        <option @if (($templateConfig->style_footer2_title_size ?? null) == $title_size->value) selected @endif @if (!($templateConfig->style_footer2_title_size ?? null) && $title_size->value == '1.2rem') selected @endif value="{{ $title_size->value }}">{{ $title_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font weight (titles and headings)') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer2_title_weight">
                    <option @if (($templateConfig->style_footer2_title_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_footer2_title_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font weight (links)') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer2_link_weight">
                    <option @if (($templateConfig->style_footer2_link_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_footer2_link_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Link decoration') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer2_link_decoration">
                    <option @if (($templateConfig->style_footer2_link_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_footer2_link_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Link decoration on hover') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer2_link_hover_decoration">
                    <option @if (($templateConfig->style_footer2_link_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_footer2_link_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-sm-4 col-md-3 col-12">
            <div class="form-group mb-2">
                <input id="style_footer2_bg_color" name="style_footer2_bg_color" value="{{ $templateConfig->style_footer2_bg_color ?? 'white' }}">
                <label>{{ __('Background color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer2_bg_color ?? 'white' }}</div>
                <script>
                    $('#style_footer2_bg_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_footer2_text_color" name="style_footer2_text_color" value="{{ $templateConfig->style_footer2_text_color ?? 'black' }}">
                <label>{{ __('Text color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer2_text_color ?? 'black' }}</div>
                <script>
                    $('#style_footer2_text_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_footer2_link_color" name="style_footer2_link_color" value="{{ $templateConfig->style_footer2_link_color ?? 'blue' }}">
                <label>{{ __('Links color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer2_link_color ?? 'blue' }}</div>
                <script>
                    $('#style_footer2_link_color').spectrum({
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
            <div class="form-group mb-2">
                <input id="style_footer2_link_hover_color" name="style_footer2_link_hover_color" value="{{ $templateConfig->style_footer2_link_hover_color ?? 'blue' }}">
                <label>{{ __('Link color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_footer2_link_hover_color ?? 'blue' }}</div>
                <script>
                    $('#style_footer2_link_hover_color').spectrum({
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

    <hr>

    <button type="submit" class="btn btn-primary">{{ __('Update style') }}</button>

</form>
