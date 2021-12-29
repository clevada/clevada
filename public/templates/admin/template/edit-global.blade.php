@include('admin.includes.import-fonts')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.templates') }}">{{ __('Templates') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $template->label }}</li>
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
                    @include('admin.template.layouts.menu-template')
                </div>

            </div>

        </div>


        <div class="card-body">

            <div class="float-end"><a class="btn btn-secondary" target="_blank" href="{{ route('homepage', ['preview_template_id' => $template->id]) }}"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview website') }}</a></div>

            <h4 class="mt-2 mb-3">{{ __('Edit template') }}: {{ $template->label }}</h4>

            <div class="mb-3">
                @include('admin.template.layouts.menu-template-edit')
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated')
                        <h4 class="alert-heading">{{ __('Updated') }}</h4>
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Info: If you don\'t see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.') }}
                    @endif
                </div>
            @endif

            <form method="post">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="card bg-light p-3 mb-3">
                        <h5 class="fw-bold">{{ __('Main colors') }}</h5>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">

                                <div class="form-group">
                                    <input id="font_color" name="font_color" value="{{ get_template_value($template->id, 'font_color') ?? config('defaults.font_color') }}">
                                    <label>{{ __('Main text color') }}</label>
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <input id="bg_color" name="bg_color" value="{{ get_template_value($template->id, 'bg_color') ?? config('defaults.bg_color') }}">
                                    <label>{{ __('Body background color') }}</label>
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <input id="headings_color" name="headings_color" value="{{ get_template_value($template->id, 'headings_color') ?? config('defaults.headings_color') }}">
                                    <label>{{ __('Headings (H1-H4) color') }}</label>
                                    <script>
                                        $('#headings_color').spectrum({
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">

                                    <input id="light_color" name="light_color" value="{{ get_template_value($template->id, 'light_color') ?? config('defaults.light_color') }}">
                                    <label>{{ __('Light color') }}</label>
                                    <script>
                                        $('#light_color').spectrum({
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


                    <div class="card bg-light p-3 mb-3">
                        <h5 class="fw-bold">{{ __('Fonts settings') }}</h5>

                        <div class="row">

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">

                                    @php
                                        $global_font_size = get_template_value($template->id, 'font_size') ?? config('defaults.font_size');
                                    @endphp
                                    <label>{{ __('Main text size') }}</label>
                                    <select class="form-select" name="font_size">
                                        @foreach (template_font_sizes() as $size_font_size)
                                            <option @if ($global_font_size == $size_font_size->value) selected @endif value="{{ $size_font_size->value }}">{{ $size_font_size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    @php
                                        $h1_size = get_template_value($template->id, 'h1_size') ?? config('defaults.h1_size');
                                    @endphp
                                    <label>{{ __('Heading 1 (H1) size') }}</label>
                                    <select class="form-select" name="h1_size">
                                        @foreach (template_font_sizes() as $size_h1)
                                            <option @if ($h1_size == $size_h1->value) selected @endif value="{{ $size_h1->value }}">{{ $size_h1->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    @php
                                        $h2_size = get_template_value($template->id, 'h2_size') ?? config('defaults.h2_size');
                                    @endphp
                                    <label>{{ __('Heading 2 (H2) size') }}</label>
                                    <select class="form-select" name="h2_size">
                                        @foreach (template_font_sizes() as $size_h2)
                                            <option @if ($h2_size == $size_h2->value) selected @endif value="{{ $size_h2->value }}">{{ $size_h2->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    @php
                                        $h3_size = get_template_value($template->id, 'h3_size') ?? config('defaults.h3_size');
                                    @endphp
                                    <label>{{ __('Heading 3 (H3) size') }}</label>
                                    <select class="form-select" name="h3_size">
                                        @foreach (template_font_sizes() as $size_h3)
                                            <option @if ($h3_size == $size_h3->value) selected @endif value="{{ $size_h3->value }}">{{ $size_h3->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    @php
                                        $listing_title_size = get_template_value($template->id, 'listing_title_size') ?? config('defaults.font_size');
                                    @endphp
                                    <label>{{ __('Listing titles size') }}</label>
                                    <select class="form-select" name="listing_title_size">
                                        @foreach (template_font_sizes() as $size_listing_title_size)
                                            <option @if ($listing_title_size == $size_listing_title_size->value) selected @endif value="{{ $size_listing_title_size->value }}">{{ $size_listing_title_size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-12 col-xl-6">
                                <div class="form-group">
                                    @php
                                        $global_font_family = get_template_value($template->id, 'font_family') ?? config('defaults.font_family');
                                    @endphp

                                    <label>{{ __('Main font') }}</label>
                                    <select class="form-select" name="font_family">
                                        @foreach (template_fonts() as $font)
                                            <option @if ($global_font_family == $font->value) selected @endif value="{{ $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">[{{ $font->name }}]
                                                Almost before we knew it, we had left the ground.</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-6">
                                <div class="form-group">
                                    @php
                                        $headings_font_family = get_template_value($template->id, 'font_family_headings') ?? config('defaults.font_family');
                                    @endphp

                                    <label>{{ __('Headings and titles font') }}</label>
                                    <select class="form-select" name="font_family_headings">
                                        @foreach (template_fonts() as $font)
                                            <option @if ($headings_font_family == $font->value) selected @endif value="{{ $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">[{{ $font->name }}]
                                                Almost before we knew it, we had left the ground.</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-6">
                                <div class="form-group">
                                    @php
                                        $nav_font_family = get_template_value($template->id, 'font_family_nav') ?? config('defaults.font_family');
                                    @endphp

                                    <label>{{ __('Navigation font') }}</label>
                                    <select class="form-select" name="font_family_nav">
                                        @foreach (template_fonts() as $font)
                                            <option @if ($nav_font_family == $font->value) selected @endif value="{{ $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">[{{ $font->name }}]
                                                Almost before we knew it, we had left the ground.</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-6">
                                <div class="form-group">
                                    @php
                                        $footer_font_family = get_template_value($template->id, 'font_family_footer') ?? config('defaults.font_family');
                                    @endphp

                                    <label>{{ __('Footer font') }}</label>
                                    <select class="form-select" name="font_family_footer">
                                        @foreach (template_fonts() as $font)
                                            <option @if ($footer_font_family == $font->value) selected @endif value="{{ $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">[{{ $font->name }}]
                                                Almost before we knew it, we had left the ground.</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                     
                    </div>


                    <div class="card bg-light p-3 mb-3">
                        <a name="config_links" id="config_links"></a>

                        <h5 class="fw-bold mb-3">{{ __('Links settings') }}</h5>

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Links color') }}</label><br>
                                    <input id="link_color" name="link_color" value="{{ get_template_value($template->id, 'link_color') ?? config('defaults.link_color') }}">
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Links color on hover') }}</label><br>
                                    <input id="link_color_hover" name="link_color_hover" value="{{ get_template_value($template->id, 'link_color_hover') ?? config('defaults.link_color_hover') }}">
                                    <script>
                                        $('#link_color_hover').spectrum({
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Underline color') }}</label><br>
                                    <input id="link_color_underline" name="link_color_underline"
                                        value="{{ get_template_value($template->id, 'link_color_underline') ?? config('defaults.link_color_hover') }}">                                    
                                    <script>
                                        $('#link_color_underline').spectrum({
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Links decoration') }}</label>
                                    <select class="form-select" name="link_decoration">
                                        <option @if (get_template_value($template->id, 'link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                        <option @if (get_template_value($template->id, 'link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Links decoration on hover') }}</label>
                                    <select class="form-select" name="link_hover_decoration">
                                        <option @if (get_template_value($template->id, 'link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                        <option @if (get_template_value($template->id, 'link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Links decoration on hover animation') }}</label>
                                    <select class="form-select" name="link_hover_decoration_animation">
                                        <option @if (get_template_value($template, 'link_hover_decoration_animation') == 'none') selected @endif value="none">{{ __('No animation') }}</option>
                                        <option @if (get_template_value($template, 'link_hover_decoration_animation') == 'fade_in') selected @endif value="fade_in">{{ __('Fade in') }}</option>
                                        <option @if (get_template_value($template, 'link_hover_decoration_animation') == 'slide_in') selected @endif value="slide_in">{{ __('Slide in') }}</option>
                                        <option @if (get_template_value($template, 'link_hover_decoration_animation') == 'scale') selected @endif value="scale">{{ __('Scale from center)') }}</option>
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                    </div>


                    <div class="card bg-light p-3 mb-3">
                        <h4 class="fw-bold mb-3">{{ __('Buttons settings') }}</h4>

                        <h5 class="fw-bold">{{ __('Primary button') }}</h5>
                        <div class="row">
                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button1_bg_color" name="button1_bg_color" value="{{ get_template_value($template->id, 'button1_bg_color') ?? config('defaults.button_bg_color') }}">
                                    <label>{{ __('Color') }}</label>
                                    <script>
                                        $('#button1_bg_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button1_font_color" name="button1_font_color" value="{{ get_template_value($template->id, 'button1_font_color') ?? config('defaults.button_font_color') }}">
                                    <label>{{ __('Font color') }}</label>
                                    <script>
                                        $('#button1_font_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button1_bg_color_hover" name="button1_bg_color_hover"
                                        value="{{ get_template_value($template->id, 'button1_bg_color_hover') ?? config('defaults.button_bg_color_hover') }}">
                                    <label>{{ __('Color on hover') }}</label>
                                    <script>
                                        $('#button1_bg_color_hover').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button1_font_color_hover" name="button1_font_color_hover"
                                        value="{{ get_template_value($template->id, 'button1_font_color_hover') ?? config('defaults.button_font_color') }}">
                                    <label>{{ __('Font color on hover') }}</label>
                                    <script>
                                        $('#button1_font_color_hover').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button1_border_color" name="button1_border_color" value="{{ get_template_value($template->id, 'button1_border_color') ?? config('defaults.button_bg_color') }}">
                                    <label>{{ __('Border color') }}</label>
                                    <script>
                                        $('#button1_border_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button1_border_color_hover" name="button1_border_color_hover"
                                        value="{{ get_template_value($template->id, 'button1_border_color_hover') ?? config('defaults.button_bg_color_hover') }}">
                                    <label>{{ __('Border color on hover') }}</label>
                                    <script>
                                        $('#button1_border_color_hover').spectrum({
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

                        <h5 class="fw-bold">{{ __('Secondary button') }}</h5>
                        <div class="row">
                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button2_bg_color" name="button2_bg_color" value="{{ get_template_value($template->id, 'button2_bg_color') ?? config('defaults.button_bg_color') }}">
                                    <label>{{ __('Color') }}</label>
                                    <script>
                                        $('#button2_bg_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button2_font_color" name="button2_font_color" value="{{ get_template_value($template->id, 'button2_font_color') ?? config('defaults.button_font_color') }}">
                                    <label>{{ __('Font color') }}</label>
                                    <script>
                                        $('#button2_font_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button2_bg_color_hover" name="button2_bg_color_hover"
                                        value="{{ get_template_value($template->id, 'button2_bg_color_hover') ?? config('defaults.button_bg_color_hover') }}">
                                    <label>{{ __('Color on hover') }}</label>
                                    <script>
                                        $('#button2_bg_color_hover').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button2_font_color_hover" name="button2_font_color_hover"
                                        value="{{ get_template_value($template->id, 'button2_font_color_hover') ?? config('defaults.button_font_color') }}">
                                    <label>{{ __('Font color on hover') }}</label>
                                    <script>
                                        $('#button2_font_color_hover').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button2_border_color" name="button2_border_color" value="{{ get_template_value($template->id, 'button2_border_color') ?? config('defaults.button_bg_color') }}">
                                    <label>{{ __('Border color') }}</label>
                                    <script>
                                        $('#button2_border_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button2_border_color_hover" name="button2_border_color_hover"
                                        value="{{ get_template_value($template->id, 'button2_border_color_hover') ?? config('defaults.button_bg_color_hover') }}">
                                    <label>{{ __('Border color on hover') }}</label>
                                    <script>
                                        $('#button2_border_color_hover').spectrum({
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

                        <h5 class="fw-bold">{{ __('Tertiary button') }}</h5>
                        <div class="row">
                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button3_bg_color" name="button3_bg_color" value="{{ get_template_value($template->id, 'button3_bg_color') ?? config('defaults.button_bg_color') }}">
                                    <label>{{ __('Color') }}</label>
                                    <script>
                                        $('#button3_bg_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button3_font_color" name="button3_font_color" value="{{ get_template_value($template->id, 'button3_font_color') ?? config('defaults.button_font_color') }}">
                                    <label>{{ __('Font color') }}</label>
                                    <script>
                                        $('#button3_font_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button3_bg_color_hover" name="button3_bg_color_hover"
                                        value="{{ get_template_value($template->id, 'button3_bg_color_hover') ?? config('defaults.button_bg_color_hover') }}">
                                    <label>{{ __('Color on hover') }}</label>
                                    <script>
                                        $('#button3_bg_color_hover').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button3_font_color_hover" name="button3_font_color_hover"
                                        value="{{ get_template_value($template->id, 'button3_font_color_hover') ?? config('defaults.button_font_color') }}">
                                    <label>{{ __('Font color on hover') }}</label>
                                    <script>
                                        $('#button3_font_color_hover').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button3_border_color" name="button3_border_color" value="{{ get_template_value($template->id, 'button3_border_color') ?? config('defaults.button_bg_color') }}">
                                    <label>{{ __('Border color') }}</label>
                                    <script>
                                        $('#button3_border_color').spectrum({
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

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <input id="button3_border_color_hover" name="button3_border_color_hover"
                                        value="{{ get_template_value($template->id, 'button3_border_color_hover') ?? config('defaults.button_bg_color_hover') }}">
                                    <label>{{ __('Border color on hover') }}</label>
                                    <script>
                                        $('#button3_border_color_hover').spectrum({
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


                    <div class="card bg-light p-3 mb-3">
                        <a name="config_links" id="config_links"></a>

                        <h5 class="fw-bold mb-3">{{ __('Breadcrumb navigation settings') }}</h5>

                        <div class="row">

                            <div class="form-group col-md-3 col-sm-6 col-12">
                                <label>{{ __('Select breadcrumb style') }}</label>
                                <select name="breadcrumb_style" class="form-select" id="breadcrumb_style" onchange="showDiv()">
                                    <option @if (get_template_value($template->id, 'breadcrumb_style') == 'simple') selected @endif value="simple">{{ __('Simple') }}</option>
                                    <option @if (get_template_value($template->id, 'breadcrumb_style') == 'box') selected @endif value="box">{{ __('Box') }}</option>
                                </select>
                            </div>

                            <script>
                                function showDiv() {
                                    var select = document.getElementById('breadcrumb_style');
                                    var value = select.options[select.selectedIndex].value;

                                    if (value == 'box') {
                                        document.getElementById('hidden_div_breadcrumb_style').style.display = 'block';
                                    } else {
                                        document.getElementById('hidden_div_breadcrumb_style').style.display = 'none';
                                    }
                                }
                            </script>

                            <div id="hidden_div_breadcrumb_style" style="display: @if (get_template_value($template->id, 'breadcrumb_style') == 'box') block @else none @endif">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="form-group">
                                            <label>{{ __('Breadcrumb background color') }}</label><br>
                                            <input id="breadcrumb_bg_color" name="breadcrumb_bg_color" value="{{ get_template_value($template->id, 'breadcrumb_bg_color') ?? config('defaults.bg_color') }}">
                                            <script>
                                                $('#breadcrumb_bg_color').spectrum({
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

                                    <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="form-group">
                                            <label>{{ __('Breadcrumb border color') }}</label><br>
                                            <input id="breadcrumb_border_color" name="breadcrumb_border_color"
                                                value="{{ get_template_value($template->id, 'breadcrumb_border_color') ?? config('defaults.bg_color') }}">
                                            <script>
                                                $('#breadcrumb_border_color').spectrum({
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

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Breadcrumb text color') }}</label><br>
                                    <input id="breadcrumb_font_color" name="breadcrumb_font_color" value="{{ get_template_value($template->id, 'breadcrumb_font_color') ?? config('defaults.font_color') }}">
                                    <script>
                                        $('#breadcrumb_font_color').spectrum({
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Breadcrumb links color') }}</label><br>
                                    <input id="breadcrumb_link_color" name="breadcrumb_link_color" value="{{ get_template_value($template->id, 'breadcrumb_link_color') ?? config('defaults.link_color') }}">
                                    <script>
                                        $('#breadcrumb_link_color').spectrum({
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

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Breadcrumb links color on hover') }}</label><br>
                                    <input id="breadcrumb_link_color_hover" name="breadcrumb_link_color_hover"
                                        value="{{ get_template_value($template->id, 'breadcrumb_link_color_hover') ?? config('defaults.link_color_hover') }}">
                                    <script>
                                        $('#breadcrumb_link_color_hover').spectrum({
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


                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Breadcrumb links decoration') }}</label>
                                    <select class="form-select" name="breadcrumb_link_decoration">
                                        <option @if (get_template_value($template->id, 'breadcrumb_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                        <option @if (get_template_value($template->id, 'breadcrumb_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                <div class="form-group">
                                    <label>{{ __('Breadcrumb links decoration on hover') }}</label>
                                    <select class="form-select" name="breadcrumb_link_hover_decoration">
                                        <option @if (get_template_value($template->id, 'breadcrumb_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                        <option @if (get_template_value($template->id, 'breadcrumb_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Breadcrumb link weight') }}</label>
                                    <select class="form-select" name="breadcrumb_link_font_weight">
                                        <option @if (get_template_value($template->id, 'breadcrumb_link_font_weight') == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                        <option @if (get_template_value($template->id, 'breadcrumb_link_font_weight') == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button type="submit" class="btn btn-primary mt-3">{{ __('Update template') }}</button>
            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
