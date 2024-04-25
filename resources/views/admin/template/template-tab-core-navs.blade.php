@include('admin.template.includes.import-fonts')
@include('admin.includes.color-picker')



<div class="fw-bold mb-3 fs-5">{{ __('Main navigation menu') }}</div>

<div class="form-group mb-3">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='navbar_show_search_form'>
        <input class="form-check-input" type="checkbox" id="navbar_show_search_form" name="navbar_show_search_form" @if ($templateConfig->navbar_show_search_form ?? null) checked @endif>
        <label class="form-check-label" for="navbar_show_search_form">{{ __('Show search form') }}</label>
    </div>
    <div class="form-text">{{ __('Caution: For navigation menu with many links, it is recommended to leave this option unchecked and use a link for the search button instead. You can add the search button to the "Menu Links" page.') }}</div>
</div>

<div class="row">

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Link size') }}</label>
            <select class="form-select" name="style_nav_fs">
                @foreach ($font_sizes as $font_size)
                    <option @if (($templateConfig->style_nav_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_nav_fs ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Link weight') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_nav_fw">
                <option @if (($templateConfig->style_nav_fw ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                <option @if (($templateConfig->style_nav_fw ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Link decoration') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_nav_a_decoration">
                <option @if (($templateConfig->style_nav_a_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_nav_a_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Link decoration on hover') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_nav_a_hover_decoration">
                <option @if (($templateConfig->style_nav_a_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_nav_a_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Underline line thickness') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_nav_a_underline_thickness">
                <option @if (($templateConfig->style_nav_a_underline_thickness ?? null) == 'auto') selected @endif value="auto">{{ __('Normal') }}</option>
                <option @if (($templateConfig->style_nav_a_underline_thickness ?? null) == '2px') selected @endif value="2px">{{ __('Thick') }}</option>
                <option @if (($templateConfig->style_nav_a_underline_thickness ?? null) == '3px') selected @endif value="3px">{{ __('Bold') }}</option>
                <option @if (($templateConfig->style_nav_a_underline_thickness ?? null) == '4px') selected @endif value="4px">{{ __('Bolder') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Underline offset') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_nav_a_underline_offset">
                <option @if (($templateConfig->style_nav_a_underline_offset ?? null) == 'auto') selected @endif value="auto">{{ __('Normal (no offset)') }}</option>
                <option @if (($templateConfig->style_nav_a_underline_offset ?? null) == '0.17em') selected @endif value="0.17em">{{ __('Small offset') }}</option>
                <option @if (($templateConfig->style_nav_a_underline_offset ?? null) == '0.35em') selected @endif value="0.35em">{{ __('Medium offset') }}</option>
                <option @if (($templateConfig->style_nav_a_underline_offset ?? null) == '0.6em') selected @endif value="0.6em">{{ __('Big offset') }}</option>
            </select>
        </div>
    </div>

</div>

<div class="row  mt-3">
    <div class="col-sm-4 col-md-3 col-12">
        <div class="form-group mb-2">
            <input id="style_nav_bg" name="style_nav_bg" value="{{ $templateConfig->style_nav_bg ?? 'white' }}">
            <label>{{ __('Background color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_nav_bg ?? 'white' }}</div>
            <script>
                $('#style_nav_bg').spectrum({
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
            <input id="style_nav_a" name="style_nav_a" value="{{ $templateConfig->style_nav_a ?? '#2c52e5' }}">
            <label>{{ __('Links color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_nav_a ?? '#2c52e5' }}</div>
            <script>
                $('#style_nav_a').spectrum({
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
            <input id="style_nav_a_hover" name="style_nav_a_hover" value="{{ $templateConfig->style_nav_a_hover ?? '#1034bd' }}">
            <label>{{ __('Link color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_nav_a_hover ?? '#1034bd' }}</div>
            <script>
                $('#style_nav_a_hover').spectrum({
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
            <label>{{ __('Link size') }}</label>
            <select class="form-select" name="style_nav_dropdown_fs">
                @foreach ($font_sizes as $font_size)
                    <option @if (($templateConfig->style_nav_dropdown_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_nav_dropdown_fs ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Link weight') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_nav_dropdown_fw">
                <option @if (($templateConfig->style_nav_dropdown_fw ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                <option @if (($templateConfig->style_nav_dropdown_fw ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
            </select>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-sm-4 col-md-3 col-12">
        <div class="form-group mb-2">
            <input id="style_nav_dropdown_bg" name="style_nav_dropdown_bg" value="{{ $templateConfig->style_nav_dropdown_bg ?? '#ededed' }}">
            <label>{{ __('Background color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_nav_dropdown_bg ?? '#ededed' }}</div>
            <script>
                $('#style_nav_dropdown_bg').spectrum({
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
            <input id="style_nav_dropdown_a" name="style_nav_dropdown_a" value="{{ $templateConfig->style_nav_dropdown_a ?? '#2c52e5' }}">
            <label>{{ __('Links color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_nav_dropdown_a ?? '#2c52e5' }}</div>
            <script>
                $('#style_nav_dropdown_a').spectrum({
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
            <input id="style_nav_dropdown_bg_hover" name="style_nav_dropdown_bg_hover" value="{{ $templateConfig->style_nav_dropdown_bg_hover ?? '#d7d7d7' }}">
            <label>{{ __('Background color (on mouse hover)') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_nav_dropdown_bg_hover ?? '#d7d7d7' }}</div>
            <script>
                $('#style_nav_dropdown_bg_hover').spectrum({
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
            <input id="style_nav_dropdown_a_hover" name="style_nav_dropdown_a_hover" value="{{ $templateConfig->style_nav_dropdown_a_hover ?? '#1034bd' }}">
            <label>{{ __('Link color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_nav_dropdown_a_hover ?? '#1034bd' }}</div>
            <script>
                $('#style_nav_dropdown_a_hover').spectrum({
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
            <label>{{ __('Link decoration') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer_a_decoration">
                <option @if (($templateConfig->style_footer_a_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_footer_a_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Link decoration on hover') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer_a_hover_decoration">
                <option @if (($templateConfig->style_footer_a_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_footer_a_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>
</div>


<div class="row mt-3">
    <div class="col-sm-4 col-md-3 col-12">
        <div class="form-group mb-2">
            <input id="style_footer_bg" name="style_footer_bg" value="{{ $templateConfig->style_footer_bg ?? '#f3f6f4' }}">
            <label>{{ __('Background color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer_bg ?? '#f3f6f4' }}</div>
            <script>
                $('#style_footer_bg').spectrum({
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
            <input id="style_footer_text" name="style_footer_text" value="{{ $templateConfig->style_footer_text ?? 'black' }}">
            <label>{{ __('Text color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer_text ?? 'black' }}</div>
            <script>
                $('#style_footer_text').spectrum({
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
            <input id="style_footer_a" name="style_footer_a" value="{{ $templateConfig->style_footer_a ?? '#2c52e5' }}">
            <label>{{ __('Links color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer_a ?? '#2c52e5' }}</div>
            <script>
                $('#style_footer_a').spectrum({
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
            <input id="style_footer_a_hover" name="style_footer_a_hover" value="{{ $templateConfig->style_footer_a_hover ?? '#1034bd' }}">
            <label>{{ __('Link color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer_a_hover ?? '#1034bd' }}</div>
            <script>
                $('#style_footer_a_hover').spectrum({
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
            <label>{{ __('Link decoration') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer2_a_decoration">
                <option @if (($templateConfig->style_footer2_a_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_footer2_a_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Link decoration on hover') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_footer2_a_hover_decoration">
                <option @if (($templateConfig->style_footer2_a_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_footer2_a_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>
</div>


<div class="row mt-3">
    <div class="col-sm-4 col-md-3 col-12">
        <div class="form-group mb-2">
            <input id="style_footer2_bg" name="style_footer2_bg" value="{{ $templateConfig->style_footer2_bg ?? '#ececec' }}">
            <label>{{ __('Background color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer2_bg ?? '#ececec' }}</div>
            <script>
                $('#style_footer2_bg').spectrum({
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
            <input id="style_footer2_text" name="style_footer2_text" value="{{ $templateConfig->style_footer2_text ?? 'black' }}">
            <label>{{ __('Text color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer2_text ?? 'black' }}</div>
            <script>
                $('#style_footer2_text').spectrum({
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
            <input id="style_footer2_a" name="style_footer2_a" value="{{ $templateConfig->style_footer2_a ?? '#2c52e5' }}">
            <label>{{ __('Links color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer2_a ?? '#2c52e5' }}</div>
            <script>
                $('#style_footer2_a').spectrum({
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
            <input id="style_footer2_a_hover" name="style_footer2_a_hover" value="{{ $templateConfig->style_footer2_a_hover ?? '#1034bd' }}">
            <label>{{ __('Link color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_footer2_a_hover ?? '#1034bd' }}</div>
            <script>
                $('#style_footer2_a_hover').spectrum({
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
