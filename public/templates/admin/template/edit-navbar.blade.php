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

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

                    <div class="col-12">

                        <div class="card bg-light p-3 mb-3">

                            <h5 class="fw-bold">{{ __('Main navbar settings') }}</h5>
                            <small class="mb-3">{{ __('This navbar contain navigation links') }}</small>

                            <div class="row">

                                <div class="col-12 col-xl-2 col-lg-6 col-md-12">
                                    <div class="form-group">

                                        @php
                                            $navbar_font_size = get_template_value($template->id, 'navbar_font_size') ?? config('defaults.font_size');
                                        @endphp
                                        <label>{{ __('Font size') }}</label>
                                        <select class="form-select" name="navbar_font_size">
                                            @foreach (template_font_sizes() as $size_font_size)
                                                <option @if ($navbar_font_size == $size_font_size->value) selected @endif value="{{ $size_font_size->value }}">{{ $size_font_size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Search form') }}</label>
                                        <select class="form-select" name="navbar_searchform">
                                            <option @if (get_template_value($template->id, 'navbar_searchform') == 'none') selected @endif value="none">{{ __('Do not display') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_searchform') == 'form') selected @endif value="form">{{ __('Form') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_searchform') == 'icon') selected @endif value="icon">{{ __('Icon and popup') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Link weight') }}</label>
                                        <select class="form-select" name="navbar_link_font_weight">
                                            <option @if (get_template_value($template->id, 'navbar_link_font_weight') == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_link_font_weight') == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Links align') }}</label>
                                        <select class="form-select" name="navbar_links_align">
                                            <option @if (get_template_value($template->id, 'navbar_links_align') == 'ms-auto') selected @endif value="ms-auto">{{ __('Right') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_links_align') == 'me-auto') selected @endif value="me-auto">{{ __('Left') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_links_align') == 'me-auto ms-auto') selected @endif value="me-auto ms-auto">{{ __('Center') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <input id="navbar_bg_color" name="navbar_bg_color" value="{{ get_template_value($template->id, 'navbar_bg_color') ?? config('defaults.nav_bg_color') }}">
                                        <label>{{ __('Background color') }}</label>
                                        <script>
                                            $('#navbar_bg_color').spectrum({
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
                                        <input id="navbar_font_color" name="navbar_font_color" value="{{ get_template_value($template->id, 'navbar_font_color') ?? config('defaults.nav_font_color') }}">
                                        <label>{{ __('Text color') }}</label>
                                        <script>
                                            $('#navbar_font_color').spectrum({
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

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links color') }}</label><br>
                                        <input id="navbar_link_color" name="navbar_link_color" value="{{ get_template_value($template->id, 'navbar_link_color') ?? config('defaults.nav_link_color') }}">
                                        <script>
                                            $('#navbar_link_color').spectrum({
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
                                        <input id="navbar_link_color_hover" name="navbar_link_color_hover"
                                            value="{{ get_template_value($template->id, 'navbar_link_color_hover') ?? config('defaults.nav_link_color_hover') }}">
                                        <script>
                                            $('#navbar_link_color_hover').spectrum({
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
                                        <label>{{ __('Links underline color') }}</label><br>
                                        <input id="navbar_link_color_underline" name="navbar_link_color_underline"
                                            value="{{ get_template_value($template->id, 'navbar_link_color_underline') ?? config('defaults.nav_link_color_hover') }}">
                                        <script>
                                            $('#navbar_link_color_underline').spectrum({
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
                                        <select class="form-select" name="navbar_link_decoration">
                                            <option @if (get_template_value($template->id, 'navbar_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration on hover') }}</label>
                                        <select class="form-select" name="navbar_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'navbar_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration on hover animation') }}</label>
                                        <select class="form-select" name="navbar_link_hover_decoration_animation">
                                            <option @if (get_template_value($template->id, 'navbar_link_hover_decoration_animation') == 'none') selected @endif value="none">{{ __('No animation') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_link_hover_decoration_animation') == 'slide_left') selected @endif value="slide_left">{{ __('Slide from left') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_link_hover_decoration_animation') == 'slide_right') selected @endif value="slide_right">{{ __('Slide from right') }}</option>
                                            <option @if (get_template_value($template->id, 'navbar_link_hover_decoration_animation') == 'scale') selected @endif value="scale">{{ __('Scale from center') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='navbar_shaddow'>
                                            <input class="form-check-input" type="checkbox" id="navbar_shaddow" name="navbar_shaddow" @if (get_template_value($template->id, 'navbar_shaddow')) checked @endif>
                                            <label class="form-check-label" for="navbar_shaddow">{{ __('Add shaddow under main navigation') }}</label>
                                        </div>                                       
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='navbar_hide_logo'>
                                            <input class="form-check-input" type="checkbox" id="navbar_hide_logo" name="navbar_hide_logo" @if (get_template_value($template->id, 'navbar_hide_logo')) checked @endif>
                                            <label class="form-check-label" for="navbar_hide_logo">{{ __('Hide logo') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='navbar_hide_langs'>
                                            <input class="form-check-input" type="checkbox" id="navbar_hide_langs" name="navbar_hide_langs" @if (get_template_value($template->id, 'navbar_hide_langs')) checked @endif>
                                            <label class="form-check-label" for="navbar_hide_langs">{{ __('Hide language selector (if available)') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='navbar_hide_auth'>
                                            <input class="form-check-input" type="checkbox" id="navbar_hide_auth" name="navbar_hide_auth" @if (get_template_value($template->id, 'navbar_hide_auth')) checked @endif>
                                            <label class="form-check-label" for="navbar_hide_auth">{{ __('Hide user menu') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='navbar_sticky'>
                                            <input class="form-check-input" type="checkbox" id="navbar_sticky" name="navbar_sticky" @if (get_template_value($template->id, 'navbar_sticky')) checked @endif>
                                            <label class="form-check-label" for="navbar_sticky">{{ __('Sticky navigation') }}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    <div class="col-12">

                        <div class="card bg-light p-3 mb-3">

                            <h5 class="fw-bold">{{ __('Secondary navbar') }}</h5>
                            <small class="mb-3">{{ __('This navbar is above main navbar') }}</small>

                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='navbar2_show'>
                                    <input class="form-check-input" type="checkbox" id="navbar2_show" name="navbar2_show" @if (get_template_value($template->id, 'navbar2_show')) checked @endif>
                                    <label class="form-check-label" for="navbar2_show">{{ __('Show secondary navbar') }}</label>
                                </div>
                            </div>

                            <script>
                                $('#navbar2_show').change(function() {
                                    select = $(this).prop('checked');
                                    if (select)
                                        document.getElementById('hidden_div_nav2').style.display = 'block';
                                    else
                                        document.getElementById('hidden_div_nav2').style.display = 'none';
                                })
                            </script>

                            <div id="hidden_div_nav2" style="display: @if (get_template_value($template->id, 'navbar2_show')) block @else none @endif">
                                <div class="row">

                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Layout style') }}</label>
                                            <select class="form-select" name="navbar2_layout">
                                                <option value="">- {{ __('Select layout style') }} -</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'logo_left') selected @endif value="logo_left">{{ __('Logo only (left)') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'logo_right') selected @endif value="logo_right">{{ __('Logo only (right)') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'logo_center') selected @endif value="logo_center">{{ __('Logo only (center)') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'logo_extra') selected @endif value="logo_extra">{{ __('Logo (left) - Extra links (right)') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'extra_logo') selected @endif value="extra_logo">{{ __('Extra links (left) - Logo (right') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'logo_search') selected @endif value="logo_search">{{ __('Logo (left) - Search form (right)') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'search_extra') selected @endif value="search_extra">{{ __('Search form (left) - Extra links (right)') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'logo_search_extra') selected @endif value="logo_search_extra">{{ __('Logo (left) - Search form - Extra links (right)') }}</option>                                              
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'extra_left') selected @endif value="extra_left">{{ __('Extra links (left)') }}</option>                                              
                                                <option @if (get_template_value($template->id, 'navbar2_layout') == 'extra_right') selected @endif value="extra_right">{{ __('Extra links (right)') }}</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-xl-2 col-lg-6 col-md-12">
                                        <div class="form-group">
                                            @php
                                                $navbar2_font_size = get_template_value($template->id, 'navbar2_font_size') ?? config('defaults.font_size');
                                            @endphp
                                            <label>{{ __('Font size') }}</label>
                                            <select class="form-select" name="navbar2_font_size">
                                                @foreach (template_font_sizes() as $font2)
                                                    <option @if ($navbar2_font_size == $font2->value) selected @endif value="{{ $font2->value }}">{{ $font2->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Link weight') }}</label>
                                            <select class="form-select" name="navbar2_link_font_weight">
                                                <option @if (get_template_value($template->id, 'navbar2_link_font_weight') == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar2_link_font_weight') == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <input id="navbar2_bg_color" name="navbar2_bg_color" value="{{ get_template_value($template->id, 'navbar2_bg_color') ?? config('defaults.nav_bg_color') }}">
                                            <label>{{ __('Background color') }}</label>
                                            <script>
                                                $('#navbar2_bg_color').spectrum({
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
                                            <input id="navbar2_font_color" name="navbar2_font_color"
                                                value="{{ get_template_value($template->id, 'navbar2_font_color') ?? config('defaults.nav_font_color') }}">
                                            <label>{{ __('Text color') }}</label>
                                            <script>
                                                $('#navbar2_font_color').spectrum({
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
                                            <input id="navbar2_link_color" name="navbar2_link_color"
                                                value="{{ get_template_value($template->id, 'navbar2_link_color') ?? config('defaults.nav_link_color') }}">
                                            <label>{{ __('Links color') }}</label>
                                            <script>
                                                $('#navbar2_link_color').spectrum({
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
                                            <input id="navbar2_link_color_hover" name="navbar2_link_color_hover"
                                                value="{{ get_template_value($template->id, 'navbar2_link_color_hover') ?? config('defaults.nav_link_color_hover') }}">
                                            <label>{{ __('Links color on hover') }}</label>
                                            <script>
                                                $('#navbar2_link_color_hover').spectrum({
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

                                <h5 class="mb-3">{{ __('Extra links') }}:</h5>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='navbar2_show_langs'>
                                            <input class="form-check-input" type="checkbox" id="navbar2_show_langs" name="navbar2_show_langs" @if (get_template_value($template->id, 'navbar2_show_langs')) checked @endif>
                                            <label class="form-check-label" for="navbar2_show_langs">{{ __('Show language selector (if available)') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='navbar2_show_auth'>
                                            <input class="form-check-input" type="checkbox" id="navbar2_show_auth" name="navbar2_show_auth" @if (get_template_value($template->id, 'navbar2_show_auth')) checked @endif>
                                            <label class="form-check-label" for="navbar2_show_auth">{{ __('Show user menu') }}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    <div class="col-12">
                        <div class="card bg-light p-3 mb-3">
                            <h5 class="fw-bold">{{ __('Dropdown settings') }}</h5>
                            <div class="row">
                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        @php
                                            $dropdown_font_size = get_template_value($template->id, 'dropdown_font_size') ?? config('defaults.font_size');
                                        @endphp
                                        <label>{{ __('Font size') }}</label>
                                        <select class="form-select" name="dropdown_font_size">
                                            @foreach (template_font_sizes() as $selectes_font_size)
                                                <option @if ($dropdown_font_size == $selectes_font_size->value) selected @endif value="{{ $selectes_font_size->value }}">{{ $selectes_font_size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Link weight') }}</label>
                                        <select class="form-select" name="dropdown_link_font_weight">
                                            <option @if (get_template_value($template->id, 'dropdown_link_font_weight') == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                            <option @if (get_template_value($template->id, 'dropdown_link_font_weight') == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <input id="dropdown_bg_color" name="dropdown_bg_color" value="{{ get_template_value($template->id, 'dropdown_bg_color') ?? '#eeeeee' }}">
                                        <label>{{ __('Dropdown background color') }}</label>
                                        <script>
                                            $('#dropdown_bg_color').spectrum({
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
                                        <input id="dropdown_font_color" name="dropdown_font_color" value="{{ get_template_value($template->id, 'dropdown_font_color') ?? config('defaults.nav_font_color') }}">
                                        <label>{{ __('Dropdown text color') }}</label>
                                        <script>
                                            $('#dropdown_font_color').spectrum({
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

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links color') }}</label><br>
                                        <input id="dropdown_link_color" name="dropdown_link_color" value="{{ get_template_value($template->id, 'dropdown_link_color') ?? config('defaults.nav_link_color') }}">
                                        <script>
                                            $('#dropdown_link_color').spectrum({
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
                                        <input id="dropdown_link_color_hover" name="dropdown_link_color_hover"
                                            value="{{ get_template_value($template->id, 'dropdown_link_color_hover') ?? config('defaults.nav_link_color_hover') }}">
                                        <script>
                                            $('#dropdown_link_color_hover').spectrum({
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
                                        <label>{{ __('Links underline color') }}</label><br>
                                        <input id="dropdown_link_color_underline" name="dropdown_link_color_underline"
                                            value="{{ get_template_value($template->id, 'dropdown_link_color_underline') ?? config('defaults.nav_link_color_hover') }}">
                                        <script>
                                            $('#dropdown_link_color_underline').spectrum({
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
                                        <select class="form-select" name="dropdown_link_decoration">
                                            <option @if (get_template_value($template->id, 'dropdown_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'dropdown_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration on hover') }}</label>
                                        <select class="form-select" name="dropdown_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'dropdown_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'dropdown_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration on hover animation') }}</label>
                                        <select class="form-select" name="dropdown_link_hover_decoration_animation">
                                            <option @if (get_template_value($template->id, 'dropdown_link_hover_decoration_animation') == 'none') selected @endif value="none">{{ __('No animation') }}</option>
                                            <option @if (get_template_value($template->id, 'dropdown_link_hover_decoration_animation') == 'slide_left') selected @endif value="slide_left">{{ __('Slide from left') }}</option>
                                            <option @if (get_template_value($template->id, 'dropdown_link_hover_decoration_animation') == 'slide_right') selected @endif value="slide_right">{{ __('Slide from right') }}</option>
                                            <option @if (get_template_value($template->id, 'dropdown_link_hover_decoration_animation') == 'scale') selected @endif value="scale">{{ __('Scale from center') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input type='hidden' value='' name='dropdown_shaddow'>
                                            <input class="form-check-input" type="checkbox" id="dropdown_shaddow" name="dropdown_shaddow" @if (get_template_value($template->id, 'dropdown_shaddow')) checked @endif>
                                            <label class="form-check-label" for="dropdown_shaddow">{{ __('Add shaddow to dropdown box') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="card bg-light p-3 mb-3">
                            <h5 class="fw-bold">{{ __('Notiffications top bar') }}</h5>
                            <small class="mb-3">{{ __('Add a top bar with text content. HTML code is allowed.') }}</small>

                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='navbar3_show'>
                                    <input class="form-check-input" type="checkbox" id="navbar3_show" name="navbar3_show" @if (get_template_value($template->id, 'navbar3_show')) checked @endif>
                                    <label class="form-check-label" for="navbar3_show">{{ __('Show notiffication bar') }}</label>
                                </div>
                            </div>

                            <script>
                                $('#navbar3_show').change(function() {
                                    select = $(this).prop('checked');
                                    if (select)
                                        document.getElementById('hidden_div_nav3').style.display = 'block';
                                    else
                                        document.getElementById('hidden_div_nav3').style.display = 'none';
                                })
                            </script>

                            <div id="hidden_div_nav3" style="display: @if (get_template_value($template->id, 'navbar3_show')) block @else none @endif">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Content') }}</label>
                                            <textarea class="form-control" name="navbar3_content" rows="2">{{ get_template_value($template->id, 'navbar3_content') ?? null }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            @php
                                                $navbar3_font_size = get_template_value($template->id, 'navbar3_font_size') ?? config('defaults.font_size');
                                            @endphp
                                            <label>{{ __('Font size') }}</label>
                                            <select class="form-select" name="navbar3_font_size">
                                                @foreach (template_font_sizes() as $selected_font_size)
                                                    <option @if ($navbar3_font_size == $selected_font_size->value) selected @endif value="{{ $selected_font_size->value }}">{{ $selected_font_size->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Content align') }}</label>
                                            <select class="form-select" name="navbar3_content_align">
                                                <option @if (get_template_value($template->id, 'navbar3_content_align') == 'text-start') selected @endif value="text-start">{{ __('Left') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar3_content_align') == 'text-center') selected @endif value="text-center">{{ __('Center') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar3_content_align') == 'text-end') selected @endif value="text-end">{{ __('Right') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <input id="navbar3_bg_color" name="navbar3_bg_color" value="{{ get_template_value($template->id, 'navbar3_bg_color') ?? config('defaults.nav_bg_color') }}">
                                            <label>{{ __('Background color') }}</label>
                                            <script>
                                                $('#navbar3_bg_color').spectrum({
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
                                            <input id="navbar3_font_color" name="navbar3_font_color"
                                                value="{{ get_template_value($template->id, 'navbar3_font_color') ?? config('defaults.nav_font_color') }}">
                                            <label>{{ __('Text color') }}</label>
                                            <script>
                                                $('#navbar3_font_color').spectrum({
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

                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <div class="form-check form-switch">
                                                <input type='hidden' value='' name='navbar3_sticky'>
                                                <input class="form-check-input" type="checkbox" id="navbar3_sticky" name="navbar3_sticky" @if (get_template_value($template->id, 'navbar3_sticky')) checked @endif>
                                                <label class="form-check-label" for="navbar3_sticky">{{ __('Sticky bar') }}</label>
                                            </div>
                                            <div class="form-text">{{ __('Note: notification bar can be sticky only if main navbar navigation is not sticky') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="form-group">
                                            <label>{{ __('Links color') }}</label><br>
                                            <input id="navbar3_link_color" name="navbar3_link_color"
                                                value="{{ get_template_value($template->id, 'navbar3_link_color') ?? config('defaults.nav_link_color') }}">
                                            <script>
                                                $('#navbar3_link_color').spectrum({
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
                                            <input id="navbar3_link_color_hover" name="navbar3_link_color_hover"
                                                value="{{ get_template_value($template->id, 'navbar3_link_color_hover') ?? config('defaults.nav_link_color_hover') }}">
                                            <script>
                                                $('#navbar3_link_color_hover').spectrum({
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
                                            <select class="form-select" name="navbar3_link_decoration">
                                                <option @if (get_template_value($template->id, 'navbar3_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar3_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="form-group">
                                            <label>{{ __('Links decoration on hover') }}</label>
                                            <select class="form-select" name="navbar3_link_hover_decoration">
                                                <option @if (get_template_value($template->id, 'navbar3_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                                <option @if (get_template_value($template->id, 'navbar3_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button type="submit" class="btn btn-primary mt-3">{{ __('Update') }}</button>

            </form>

            
        </div>
        <!-- end card-body -->

    </div>

</section>
