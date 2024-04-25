@include('admin.template.includes.import-fonts')
@include('admin.includes.color-picker')

<div class="fw-bold mb-3 fs-5">{{ __('Style (fonts, colors, links...)') }}</div>

<div class="form-group mb-3">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='website_container_fluid'>
        <input class="form-check-input" type="checkbox" id="website_container_fluid" name="website_container_fluid" @if ($templateConfig->website_container_fluid ?? null) checked @endif>
        <label class="form-check-label" for="website_container_fluid">{{ __('Fluid website layout') }}</label>
    </div>
    <div class="form-text">{{ __('If checked, the width of the content container will be full width of the display.') }}</div>
</div>

<div class="col-sm-4 col-md-3 col-xxl-2 col-12">
    <div class="form-group mb-4">
        <label>{{ __('Default font size') }}</label>
        <select class="form-select" name="style_fs">
            @foreach ($font_sizes as $font_size)
                <option @if (($templateConfig->style_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_fs ?? null) && $font_size->value == '1.1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
            @endforeach
        </select>
        <div class="form-text">{{ __('Relative to main font size (16px)') }}</div>
    </div>
</div>



<div class="row">

    <div class="row">
        <div class="col-sm-6 col-md-4 col-12 mb-2">
            <div class="form-group">
                <label>{{ __('Headings font (used in headings and titles)') }}</label>
                <select class="form-select" name="style_font_family_headings">
                    @foreach ($fonts as $font)
                        <option @if (($templateConfig->style_font_family_headings ?? null) == $font->import . '|' . $font->value) selected @endif value="{{ $font->import . '|' . $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">
                            [{{ $font->name }}]
                            Almost before we knew it, we had left the ground.</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-12 mb-2">
            <div class="form-group">
                <label>{{ __('Content font (used in pages and posts content)') }}</label>
                <select class="form-select" name="style_font_family_content">
                    @foreach ($fonts as $font)
                        <option @if (($templateConfig->style_font_family_content ?? null) == $font->import . '|' . $font->value) selected @endif value="{{ $font->import . '|' . $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">
                            [{{ $font->name }}]
                            Almost before we knew it, we had left the ground.</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-sm-6 col-md-4 col-12 mb-2">
            <div class="form-group">
                <label>{{ __('Navigaiton font (used in navbar menu, footer and breadcrumb navigation)') }}</label>
                <select class="form-select" name="style_font_family_navigation">
                    @foreach ($fonts as $font)
                        <option @if (($templateConfig->style_font_family_navigation ?? null) == $font->import . '|' . $font->value) selected @endif value="{{ $font->import . '|' . $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">
                            [{{ $font->name }}]
                            Almost before we knew it, we had left the ground.</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-4 col-md-3 col-xl-2 col-12">
            <div class="form-group mb-2">
                <input id="style_bg" name="style_bg" value="{{ $templateConfig->style_bg ?? 'white' }}">
                <label>{{ __('Background color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_bg ?? 'white' }}</div>
                <script>
                    $('#style_bg').spectrum({
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

        <div class="col-sm-4 col-md-3 col-xl-2 col-12">
            <div class="form-group mb-2">
                <input id="style_text" name="style_text" value="{{ $templateConfig->style_text ?? 'black' }}">
                <label>{{ __('Text color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_text ?? 'black' }}</div>
                <script>
                    $('#style_text').spectrum({
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

        <div class="col-sm-4 col-md-3 col-xl-2 col-12">
            <div class="form-group mb-2">
                <input id="style_a" name="style_a" value="{{ $templateConfig->style_a ?? '#2c52e5' }}">
                <label>{{ __('Link color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_a ?? '#2c52e5' }}</div>
                <script>
                    $('#style_a').spectrum({
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


        <div class="col-sm-4 col-md-3 col-xl-2 col-12">
            <div class="form-group mb-2">
                <input id="style_a_hover" name="style_a_hover" value="{{ $templateConfig->style_a_hover ?? '#1034bd' }}">
                <label>{{ __('Link color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_a_hover ?? '#1034bd' }}</div>
                <script>
                    $('#style_a_hover').spectrum({
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

        <div class="col-sm-4 col-md-3 col-xl-2 col-12">
            <div class="form-group mb-2">
                <input id="style_light" name="style_light" value="{{ $templateConfig->style_light ?? '#6d6d6d' }}">
                <label>{{ __('Light color (used for meta details)') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_light ?? '#6d6d6d' }}</div>
                <script>
                    $('#style_light').spectrum({
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


    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Link decoration') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_a_decoration">
                <option @if (($templateConfig->style_a_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_a_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Link decoration on hover') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_a_hover_decoration">
                <option @if (($templateConfig->style_a_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                <option @if (($templateConfig->style_a_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
            </select>
        </div>
    </div>



</div>

<hr>


<div class="fw-bold fs-5 mb-1">{{ __('Posts list') }}</div>
<div class="text-muted small mb-3">{{ __('Posts listings from main posts page and categories pages.') }}</div>

<div class="row mt-3">
    <div class="col-sm-4 col-md-3 col-12 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_posts_listing_a" name="style_posts_listing_a" value="{{ $templateConfig->style_posts_listing_a ?? '#2c52e5' }}">
            <label>{{ __('Link color') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_posts_listing_a ?? '#2c52e5' }}</div>
            <script>
                $('#style_posts_listing_a').spectrum({
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

    <div class="col-sm-4 col-md-3 col-12 col-xl-2">
        <div class="form-group mb-2">
            <input id="style_posts_listing_a_hover" name="style_posts_listing_a_hover" value="{{ $templateConfig->style_posts_listing_a_hover ?? '#1034bd' }}">
            <label>{{ __('Link color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ $templateConfig->style_posts_listing_a_hover ?? '#1034bd' }}</div>
            <script>
                $('#style_posts_listing_a_hover').spectrum({
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


<div class="row">
    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Article title text size') }}</label>
            <select class="form-select" name="style_posts_listing_title_fs">
                @foreach ($font_sizes as $font_size)
                    <option @if (($templateConfig->style_posts_listing_title_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_posts_listing_title_fs ?? null) && $font_size->value == '1.3rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Article title text weight') }}</label>
            <select class="form-select" name="style_posts_listing_title_fw">
                <option @if (($templateConfig->style_posts_listing_title_fw ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                <option @if (($templateConfig->style_posts_listing_title_fw ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Article summary text size') }}</label>
            <select class="form-select" name="style_posts_listing_summary_fs">
                @foreach ($font_sizes as $font_size)
                    <option @if (($templateConfig->style_posts_listing_summary_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_posts_listing_summary_fs ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<hr>


<div class="fw-bold fs-5 mb-1">{{ __('Post details page') }}</div>

<div class="row">

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Post content title size') }}</label>
            <select class="form-select" name="style_post_title_fs">
                @foreach ($font_sizes as $font_size)
                    <option @if (($templateConfig->style_post_title_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_post_title_fs ?? null) && $font_size->value == '2.5rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Post content summary size') }}</label>
            <select class="form-select" name="style_post_summary_fs">
                @foreach ($font_sizes as $font_size)
                    <option @if (($templateConfig->style_post_summary_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_post_summary_fs ?? null) && $font_size->value == '1.4rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
        <div class="form-group mb-4">
            <label>{{ __('Post content text size') }}</label>
            <select class="form-select" name="style_post_text_fs">
                @foreach ($font_sizes as $font_size)
                    <option @if (($templateConfig->style_post_text_fs ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_post_text_fs ?? null) && $font_size->value == '1.1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
        <div class="form-group mb-2">
            <label>{{ __('Post content line height') }}</label>
            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_line_height">
                <option @if (($templateConfig->style_post_line_height ?? null) == '1.6em') selected @endif value="1.6em">{{ __('Normal') }}</option>
                <option @if (($templateConfig->style_post_line_height ?? null) == '1.8em') selected @endif value="1.8em">{{ __('Medium') }}</option>
                <option @if (($templateConfig->style_post_line_height ?? null) == '2.2em') selected @endif value="2.2em">{{ __('Large') }}</option>
                <option @if (($templateConfig->style_post_line_height ?? null) == '2.6em') selected @endif value="2.6em">{{ __('Extra large') }}</option>
            </select>
        </div>
    </div>
</div>


<hr>


<div class="fw-bold fs-5 mb-1">{{ __('Main button style') }}</div>
<div class="text-muted small mb-3">{{ __('This is the main button, used in content pages and forms') }}</div>

<div class="row">
    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_btn_bg_color" name="style_btn_bg_color" value="{{ $templateConfig->style_btn_bg_color ?? 'white' }}">
            <label>{{ __('Background color') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_btn_bg_color ?? 'white') }}</div>
            <script>
                $('#style_btn_bg_color').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_btn_font_color" name="style_btn_font_color" value="{{ $templateConfig->style_btn_font_color ?? 'black' }}">
            <label>{{ __('Font color') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_btn_font_color ?? 'black') }}</div>
            <script>
                $('#style_btn_font_color').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_btn_bg_color_hover" name="style_btn_bg_color_hover" value="{{ $templateConfig->style_btn_bg_color_hover ?? 'grey' }}">
            <label>{{ __('Background color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_btn_bg_color_hover ?? 'grey') }}</div>
            <script>
                $('#style_btn_bg_color_hover').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_btn_font_color_hover" name="style_btn_font_color_hover" value="{{ $templateConfig->style_btn_font_color_hover ?? 'black' }}">
            <label>{{ __('Font color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->font_color_hover ?? 'black') }}</div>
            <script>
                $('#style_btn_font_color_hover').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_btn_border_color" name="style_btn_border_color" value="{{ $templateConfig->style_btn_border_color ?? 'black' }}">
            <label>{{ __('Border color') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_btn_border_color ?? 'blank') }}</div>
            <script>
                $('#style_btn_border_color').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_btn_border_color_hover" name="style_btn_border_color_hover" value="{{ $templateConfig->style_btn_border_color_hover ?? 'black' }}">
            <label>{{ __('Border color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_btn_border_color_hover ?? 'blank') }}</div>
            <script>
                $('#style_btn_border_color_hover').spectrum({
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

    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-3 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Border') }}</label>
                <select class="form-select" name="style_btn_border_width">
                    <option @if (($templateConfig->style_btn_border_width ?? null) == '0px') selected @endif value="0px">{{ __('No border') }}</option>
                    <option @if (($templateConfig->style_btn_border_width ?? null) == '1px') selected @endif value="1px">{{ __('Thin border') }}</option>
                    <option @if (($templateConfig->style_btn_border_width ?? null) == '2px') selected @endif value="2px">{{ __('Medium border') }}</option>
                    <option @if (($templateConfig->style_btn_border_width ?? null) == '3px') selected @endif value="3px">{{ __('Thick border') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Button border rounded') }}</label>
                <select class="form-select" name="style_btn_rounded">
                    <option @if (($templateConfig->style_btn_rounded ?? null) == '0') selected @endif value="0">{{ __('No radius') }}</option>
                    <option @if (($templateConfig->style_btn_rounded ?? null) == '0.2rem') selected @endif value="0.2rem">{{ __('Extra small') }}</option>
                    <option @if (($templateConfig->style_btn_rounded ?? null) == '0.35rem') selected @endif value="0.35rem">{{ __('Small') }}</option>
                    <option @if (($templateConfig->style_btn_rounded ?? null) == '0.45rem') selected @endif value="0.45rem">{{ __('Medium') }}</option>
                    <option @if (($templateConfig->style_btn_rounded ?? null) == '0.6rem') selected @endif value="0.6rem">{{ __('Large') }}</option>
                    <option @if (($templateConfig->style_btn_rounded ?? null) == '1rem') selected @endif value="1rem">{{ __('Extra large') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font weight') }}</label>
                <select class="form-select" name="style_btn_font_weight">
                    <option @if (($templateConfig->style_btn_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_btn_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>
    </div>


</div>

<hr>


<div class="fw-bold fs-5 mb-1">{{ __('Navigation button style') }}</div>
<div class="text-muted small mb-3">{{ __('This is the button used in navigation menu link (if exists)') }}</div>

<div class="row">
    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_nav_btn_bg_color" name="style_nav_btn_bg_color" value="{{ $templateConfig->style_nav_btn_bg_color ?? '#2986cc' }}">
            <label>{{ __('Background color') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_nav_btn_bg_color ?? '#2986cc') }}</div>
            <script>
                $('#style_nav_btn_bg_color').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_nav_btn_font_color" name="style_nav_btn_font_color" value="{{ $templateConfig->style_nav_btn_font_color ?? 'white' }}">
            <label>{{ __('Font color') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_nav_btn_font_color ?? 'white') }}</div>
            <script>
                $('#style_nav_btn_font_color').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_nav_btn_bg_color_hover" name="style_nav_btn_bg_color_hover" value="{{ $templateConfig->style_nav_btn_bg_color_hover ?? '#2b76ae' }}">
            <label>{{ __('Background color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_nav_btn_bg_color_hover ?? '#2b76ae') }}</div>
            <script>
                $('#style_nav_btn_bg_color_hover').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_nav_btn_font_color_hover" name="style_nav_btn_font_color_hover" value="{{ $templateConfig->style_nav_btn_font_color_hover ?? 'white' }}">
            <label>{{ __('Font color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->font_color_hover ?? 'white') }}</div>
            <script>
                $('#style_nav_btn_font_color_hover').spectrum({
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


    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_nav_btn_border_color" name="style_nav_btn_border_color" value="{{ $templateConfig->style_nav_btn_border_color ?? '#2986cc' }}">
            <label>{{ __('Border color') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_nav_btn_border_color ?? '#2986cc') }}</div>
            <script>
                $('#style_nav_btn_border_color').spectrum({
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

    <div class="col-12 col-lg-3 col-md-4 col-xl-2">
        <div class="form-group mb-4">
            <input id="style_nav_btn_border_color_hover" name="style_nav_btn_border_color_hover" value="{{ $templateConfig->style_nav_btn_border_color_hover ?? '#2986cc' }}">
            <label>{{ __('Border color on mouse hover') }}</label>
            <div class="mt-1 small"> {{ strtoupper($templateConfig->style_nav_btn_border_color_hover ?? '#2986cc') }}</div>
            <script>
                $('#style_nav_btn_border_color_hover').spectrum({
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

    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-3 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Border') }}</label>
                <select class="form-select" name="style_nav_btn_border_width">
                    <option @if (($templateConfig->style_nav_btn_border_width ?? null) == '0px') selected @endif value="0px">{{ __('No border') }}</option>
                    <option @if (($templateConfig->style_nav_btn_border_width ?? null) == '1px') selected @endif value="1px">{{ __('Thin border') }}</option>
                    <option @if (($templateConfig->style_nav_btn_border_width ?? null) == '2px') selected @endif value="2px">{{ __('Medium border') }}</option>
                    <option @if (($templateConfig->style_nav_btn_border_width ?? null) == '3px') selected @endif value="3px">{{ __('Thick border') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Button border rounded') }}</label>
                <select class="form-select" name="style_nav_btn_rounded">
                    <option @if (($templateConfig->style_nav_btn_rounded ?? null) == '0') selected @endif value="0">{{ __('No radius') }}</option>
                    <option @if (($templateConfig->style_nav_btn_rounded ?? null) == '0.2rem') selected @endif value="0.2rem">{{ __('Extra small') }}</option>
                    <option @if (($templateConfig->style_nav_btn_rounded ?? null) == '0.35rem') selected @endif value="0.35rem">{{ __('Small') }}</option>
                    <option @if (($templateConfig->style_nav_btn_rounded ?? null) == '0.45rem') selected @endif value="0.45rem">{{ __('Medium') }}</option>
                    <option @if (($templateConfig->style_nav_btn_rounded ?? null) == '0.6rem') selected @endif value="0.6rem">{{ __('Large') }}</option>
                    <option @if (($templateConfig->style_nav_btn_rounded ?? null) == '1rem') selected @endif value="1rem">{{ __('Extra large') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Font weight') }}</label>
                <select class="form-select" name="style_nav_btn_font_weight">
                    <option @if (($templateConfig->style_nav_btn_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_nav_btn_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>
