@include('admin.template.includes.import-fonts')
@include('admin.includes.color-picker')

<div class="fw-bold mb-3 fs-5">{{ __('Style (fonts, colors, links...)') }}</div>

<form method="post">
    @csrf
    @method('PUT')

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
            <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
                <div class="form-group mb-4">
                    <label>{{ __('Font size') }}</label>
                    <select class="form-select" name="style_text_size">
                        @foreach ($font_sizes as $font_size)
                            <option @if (($templateConfig->style_text_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_text_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">{{ __('Relative to main font size (16px)') }}</div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-sm-4 col-md-3 col-xl-2 col-12">
                <div class="form-group mb-2">
                    <input id="style_bg_color" name="style_bg_color" value="{{ $templateConfig->style_bg_color ?? 'white' }}">
                    <label>{{ __('Background color') }}</label>
                    <div class="mt-1 small"> {{ $templateConfig->style_bg_color ?? 'white' }}</div>
                    <script>
                        $('#style_bg_color').spectrum({
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
                    <input id="style_text_color" name="style_text_color" value="{{ $templateConfig->style_text_color ?? 'black' }}">
                    <label>{{ __('Text color') }}</label>
                    <div class="mt-1 small"> {{ $templateConfig->style_text_color ?? 'black' }}</div>
                    <script>
                        $('#style_text_color').spectrum({
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
                    <input id="style_link_color" name="style_link_color" value="{{ $templateConfig->style_link_color ?? '#3365cc' }}">
                    <label>{{ __('Link color') }}</label>
                    <div class="mt-1 small"> {{ $templateConfig->style_link_color ?? '#3365cc' }}</div>
                    <script>
                        $('#style_link_color').spectrum({
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
                    <input id="style_link_hover_color" name="style_link_hover_color" value="{{ $templateConfig->style_link_hover_color ?? '#3365cc' }}">
                    <label>{{ __('Link color on mouse hover') }}</label>
                    <div class="mt-1 small"> {{ $templateConfig->style_link_hover_color ?? '#3365cc' }}</div>
                    <script>
                        $('#style_link_hover_color').spectrum({
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
                    <input id="style_light_color" name="style_light_color" value="{{ $templateConfig->style_light_color ?? 'grey' }}">
                    <label>{{ __('Light color (used for meta details)') }}</label>
                    <div class="mt-1 small"> {{ $templateConfig->style_light_color ?? 'grey' }}</div>
                    <script>
                        $('#style_light_color').spectrum({
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

    <hr>


    <div class="fw-bold fs-5 mb-1">{{ __('Posts list') }}</div>
    <div class="text-muted small mb-3">{{ __('Posts listings from main posts page and categories pages.') }}</div>

    <div class="row">
        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Title font size') }}</label>
                <select class="form-select" name="style_listing_title_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_listing_title_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->listing_title_size ?? null) && $font_size->value == '1.3rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Text size (post summary)') }}</label>
                <select class="form-select" name="style_listing_text_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_listing_text_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->listing_text_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Text size (meta: icon, date, author, views...)') }}</label>
                <select class="form-select" name="style_listing_meta_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_listing_meta_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->listing_meta_size ?? null) && $font_size->value == '0.9rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Font weight (post title)') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_listing_title_font_weight">
                    <option @if (($templateConfig->style_listing_title_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_listing_title_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Font weight (post summary)') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_listing_text_font_weight">
                    <option @if (($templateConfig->style_listing_text_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_listing_text_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_listing_link_decoration">
                    <option @if (($templateConfig->style_listing_link_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_listing_link_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration on hover') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_listing_link_hover_decoration">
                    <option @if (($templateConfig->style_listing_link_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_listing_link_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Underline line thickness') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_listing_link_underline_thickness">
                    <option @if (($templateConfig->style_listing_link_underline_thickness ?? null) == 'auto') selected @endif value="auto">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_listing_link_underline_thickness ?? null) == '3px') selected @endif value="3px">{{ __('Bold') }}</option>
                    <option @if (($templateConfig->style_listing_link_underline_thickness ?? null) == '6px') selected @endif value="6px">{{ __('Bolder') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Underline offset') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_listing_link_underline_offset">
                    <option @if (($templateConfig->style_listing_link_underline_offset ?? null) == 'auto') selected @endif value="auto">{{ __('Normal (no offset)') }}</option>
                    <option @if (($templateConfig->style_listing_link_underline_offset ?? null) == '0.17em') selected @endif value="0.17em">{{ __('Small offset') }}</option>
                    <option @if (($templateConfig->style_listing_link_underline_offset ?? null) == '0.35em') selected @endif value="0.35em">{{ __('Medium offset') }}</option>
                    <option @if (($templateConfig->style_listing_link_underline_offset ?? null) == '0.6em') selected @endif value="0.6em">{{ __('Big offset') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-sm-4 col-md-3 col-12">
            <div class="form-group mb-4">
                <input id="style_listing_link_color" name="style_listing_link_color" value="{{ $templateConfig->style_listing_link_color ?? '#495057' }}">
                <label>{{ __('Link color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_listing_link_color ?? '#495057' }}</div>
                <script>
                    $('#style_listing_link_color').spectrum({
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
                <input id="style_listing_link_hover_color" name="style_listing_link_hover_color" value="{{ $templateConfig->style_listing_link_hover_color ?? 'blue' }}">
                <label>{{ __('Link color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_listing_link_hover_color ?? 'blue' }}</div>
                <script>
                    $('#style_listing_link_hover_color').spectrum({
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


    <div class="fw-bold fs-5 mb-1">{{ __('Post details page') }}</div>

    <div class="row">
        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Title font size') }}</label>
                <select class="form-select" name="style_post_title_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_post_title_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_post_title_size ?? null) && $font_size->value == '2rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Title font weight') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_title_font_weight">
                    <option @if (($templateConfig->style_post_title_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_post_title_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Summary text size') }}</label>
                <select class="form-select" name="style_post_summary_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_post_summary_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_post_summary_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Summary font weight') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_summary_font_weight">
                    <option @if (($templateConfig->style_post_summary_font_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_post_summary_font_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Meta text size (icon, date, author, views...)') }}</label>
                <select class="form-select" name="style_post_meta_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_post_meta_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_post_meta_size ?? null) && $font_size->value == '0.9rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Article text size') }}</label>
                <select class="form-select" name="style_post_text_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_post_text_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_post_text_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Article line height') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_line_height">
                    <option @if (($templateConfig->style_post_line_height ?? null) == '1.6em') selected @endif value="1.6em">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_post_line_height ?? null) == '1.8em') selected @endif value="1.8em">{{ __('Medium') }}</option>
                    <option @if (($templateConfig->style_post_line_height ?? null) == '2.2em') selected @endif value="2.2em">{{ __('Large') }}</option>
                    <option @if (($templateConfig->style_post_line_height ?? null) == '2.6em') selected @endif value="2.6em">{{ __('Extra large') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_link_decoration">
                    <option @if (($templateConfig->style_post_link_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_post_link_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Link decoration on hover') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_link_hover_decoration">
                    <option @if (($templateConfig->style_post_link_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                    <option @if (($templateConfig->style_post_link_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Underline line thickness') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_link_underline_thickness">
                    <option @if (($templateConfig->style_post_link_underline_thickness ?? null) == 'auto') selected @endif value="auto">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_post_link_underline_thickness ?? null) == '3px') selected @endif value="3px">{{ __('Bold') }}</option>
                    <option @if (($templateConfig->style_post_link_underline_thickness ?? null) == '6px') selected @endif value="6px">{{ __('Bolder') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Underline offset') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_post_link_underline_offset">
                    <option @if (($templateConfig->style_post_link_underline_offset ?? null) == 'auto') selected @endif value="auto">{{ __('Normal (no offset)') }}</option>
                    <option @if (($templateConfig->style_post_link_underline_offset ?? null) == '0.17em') selected @endif value="0.17em">{{ __('Small offset') }}</option>
                    <option @if (($templateConfig->style_post_link_underline_offset ?? null) == '0.35em') selected @endif value="0.35em">{{ __('Medium offset') }}</option>
                    <option @if (($templateConfig->style_post_link_underline_offset ?? null) == '0.6em') selected @endif value="0.6em">{{ __('Big offset') }}</option>
                </select>
            </div>
        </div>
    </div>


    <hr>

    <div class="fw-bold fs-5 mb-1">{{ __('Categories and tags links') }}</div>

    <div class="row">
        <div class="col-sm-4 col-md-3 col-xxl-2 col-12">
            <div class="form-group mb-4">
                <label>{{ __('Links size') }}</label>
                <select class="form-select" name="style_tags_categs_link_size">
                    @foreach ($font_sizes as $font_size)
                        <option @if (($templateConfig->style_tags_categs_link_size ?? null) == $font_size->value) selected @endif @if (!($templateConfig->style_tags_categs_link_size ?? null) && $font_size->value == '1rem') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-3  col-xxl-2 col-12">
            <div class="form-group mb-2">
                <label>{{ __('Links font weight') }}</label>
                <select class="form-select col-md-6 col-lg-4 col-xl-3" name="style_tags_categs_link_weight">
                    <option @if (($templateConfig->style_tags_categs_link_weight ?? null) == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                    <option @if (($templateConfig->style_tags_categs_link_weight ?? null) == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-3 col-12">
            <div class="form-group mb-4">
                <input id="style_tags_categs_link_color" name="style_tags_categs_link_color" value="{{ $templateConfig->style_tags_categs_link_color ?? '#3365cc' }}">
                <label>{{ __('Link color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_tags_categs_link_color ?? '#3365cc' }}</div>
                <script>
                    $('#style_tags_categs_link_color').spectrum({
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
                <input id="style_tags_categs_link_hover_color" name="style_tags_categs_link_hover_color" value="{{ $templateConfig->style_tags_categs_link_hover_color ?? '#3365cc' }}">
                <label>{{ __('Link color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_tags_categs_link_hover_color ?? '#3365cc' }}</div>
                <script>
                    $('#style_tags_categs_link_hover_color').spectrum({
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
                <input id="style_tags_categs_card_bg_color" name="style_tags_categs_card_bg_color" value="{{ $templateConfig->style_tags_categs_card_bg_color ?? '#F3F1EF' }}">
                <label>{{ __('Box background color') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_tags_categs_card_bg_color ?? '#F3F1EF' }}</div>
                <script>
                    $('#style_tags_categs_card_bg_color').spectrum({
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
                <input id="style_tags_categs_card_bg_hover_color" name="style_tags_categs_card_bg_hover_color" value="{{ $templateConfig->style_tags_categs_card_bg_hover_color ?? '#dbd8d4' }}">
                <label>{{ __('Box color on mouse hover') }}</label>
                <div class="mt-1 small"> {{ $templateConfig->style_tags_categs_card_bg_hover_color ?? '#dbd8d4' }}</div>
                <script>
                    $('#style_tags_categs_card_bg_hover_color').spectrum({
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

    <div class="fw-bold fs-5 mb-1">{{ __('Main button style') }}</div>

    <div class="row">
        <div class="col-12 col-lg-3 col-md-4">
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

        <div class="col-12 col-lg-3 col-md-4">
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

        <div class="col-12 col-lg-3 col-md-4">
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

        <div class="col-12 col-lg-3 col-md-4">
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


        <div class="col-12 col-lg-3 col-md-4">
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

        <div class="col-12 col-lg-3 col-md-4">
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

    </div>

    <hr>

    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>

</form>
