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

            <div class="float-end"><a class="btn btn-secondary" target="_blank" href="{{ route('posts', ['preview_template_id' => $template->id]) }}"><i class="bi bi-box-arrow-up-right"></i>
                    {{ __('Preview template') }}</a></div>

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

            <form method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 col-12">
                        <div class="card bg-light p-3 mb-3">
                            <h5>{{ __('Knowledge Base main page settings') }}</h5>

                            <div class="form-group col-md-6 col-sm-12 col-12">
                                <label>{{ __('Main page style') }}</label>
                                <select name="docs_index_style" class="form-select" id="docs_index_style" onchange="showIndexStyleDiv()">
                                    <option @if (get_template_value($template->id, 'docs_index_style') == 'list') selected @endif value="list">{{ __('List') }}</option>
                                    <option @if (get_template_value($template->id, 'docs_index_style') == 'cards') selected @endif value="cards">{{ __('Cards') }}</option>
                                </select>
                            </div>

                            <script>
                                function showIndexStyleDiv() {
                                    var select = document.getElementById('docs_index_style');
                                    var value = select.options[select.selectedIndex].value;

                                    if (value == 'cards') {
                                        document.getElementById('div_index_style_cards').style.display = 'block';
                                        document.getElementById('div_index_style_list').style.display = 'none';
                                    } else {
                                        document.getElementById('div_index_style_cards').style.display = 'none';
                                        document.getElementById('div_index_style_list').style.display = 'block';
                                    }
                                }
                            </script>

                            <div id="div_index_style_cards" style="display: @if (get_template_value($template->id, 'docs_index_style') == 'cards') block @else none @endif">

                                <div class="form-group col-md-6 col-sm-12">
                                    @php
                                        $docs_cards_icon_size = get_template_value($template->id, 'docs_cards_icon_size') ?? config('defaults.h1_size');
                                    @endphp
                                    <label>{{ __('Icons size') }}</label>
                                    <select class="form-select" name="docs_cards_icon_size">
                                        @foreach (template_font_sizes() as $selectes_icon_size_title)
                                            <option @if ($docs_cards_icon_size == $selectes_icon_size_title->value) selected @endif value="{{ $selectes_icon_size_title->value }}">{{ $selectes_icon_size_title->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Font color') }}</label><br>
                                            <input id="docs_cards_font_color" name="docs_cards_font_color"
                                                value="{{ get_template_value($template->id, 'docs_cards_font_color') ?? config('defaults.font_color') }}">
                                            <script>
                                                $('#docs_cards_font_color').spectrum({
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
                                    
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Background color') }}</label><br>
                                            <input id="docs_cards_bg_color" name="docs_cards_bg_color" value="{{ get_template_value($template->id, 'docs_cards_bg_color') ?? '#fbf7f0' }}">
                                            <script>
                                                $('#docs_cards_bg_color').spectrum({
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

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Font color on hover') }}</label><br>
                                            <input id="docs_cards_font_color_hover" name="docs_cards_font_color_hover"
                                                value="{{ get_template_value($template->id, 'docs_cards_font_color_hover') ?? config('defaults.font_color') }}">
                                            <script>
                                                $('#docs_cards_font_color_hover').spectrum({
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

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label>{{ __('Background on hover') }}</label><br>
                                            <input id="docs_cards_bg_color_hover" name="docs_cards_bg_color_hover" value="{{ get_template_value($template->id, 'docs_cards_bg_color_hover') ?? '#fbf7f0' }}">
                                            <script>
                                                $('#docs_cards_bg_color_hover').spectrum({
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

                                <div class="form-group mt-2 mb-0">
                                    <div class="form-check form-switch">
                                        <input type='hidden' value='' name='docs_cards_shaddow'>
                                        <input class="form-check-input" type="checkbox" id="docs_cards_shaddow" name="docs_cards_shaddow" @if (get_template_value($template->id, 'docs_cards_shaddow')) checked @endif>
                                        <label class="form-check-label" for="docs_cards_shaddow">{{ __('Add shaddow on card hover') }}</label>
                                    </div>
                                </div>                               

                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Links color') }}</label><br>
                                        <input id="docs_home_link_color" name="docs_home_link_color" value="{{ get_template_value($template->id, 'docs_home_link_color') ?? config('defaults.link_color') }}">
                                        <script>
                                            $('#docs_home_link_color').spectrum({
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

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Links color on hover') }}</label><br>
                                        <input id="docs_home_link_color_hover" name="docs_home_link_color_hover" value="{{ get_template_value($template->id, 'docs_home_link_color_hover') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#docs_home_link_color_hover').spectrum({
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

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Underline color') }}</label><br>
                                        <input id="docs_home_link_color_underline" name="docs_home_link_color_underline"
                                            value="{{ get_template_value($template->id, 'docs_home_link_color_underline') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#docs_home_link_color_underline').spectrum({
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

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration') }}</label>
                                        <select class="form-select" name="docs_home_link_decoration">
                                            <option @if (get_template_value($template->id, 'docs_home_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'docs_home_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration on hover') }}</label>
                                        <select class="form-select" name="docs_home_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'docs_home_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'docs_home_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <div class="form-check form-switch">
                                        <input type='hidden' value='' name='docs_hide_icons'>
                                        <input class="form-check-input" type="checkbox" id="docs_hide_icons" name="docs_hide_icons" @if (get_template_value($template->id, 'docs_hide_icons')) checked @endif>
                                        <label class="form-check-label" for="docs_hide_icons">{{ __('Hide icons') }}</label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    @php
                                        $docs_home_title_size = get_template_value($template->id, 'docs_home_title_size') ?? config('defaults.h4_size');
                                    @endphp
                                    <label>{{ __('Category title font size') }}</label>
                                    <select class="form-select" name="docs_home_title_size">
                                        @foreach (template_font_sizes() as $selectes_title_size)
                                            <option @if ($docs_home_title_size == $selectes_title_size->value) selected @endif value="{{ $selectes_title_size->value }}">{{ $selectes_title_size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-sm-12 col-12">
                                    <label>{{ __('Number of columns') }}</label>
                                    <select name="docs_index_columns" class="form-select">
                                        <option @if (get_template_value($template->id, 'docs_index_columns') == '2') selected @endif value="2">2</option>
                                        <option @if (null === get_template_value($template->id, 'docs_index_columns') || get_template_value($template->id, 'docs_index_columns') == '3') selected @endif value="3">3</option>
                                        <option @if (get_template_value($template->id, 'docs_index_columns') == '4') selected @endif value="4">4</option>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='docs_hide_featured'>
                                    <input class="form-check-input" type="checkbox" id="docs_hide_featured" name="docs_hide_featured" @if (get_template_value($template->id, 'docs_hide_featured')) checked @endif>
                                    <label class="form-check-label" for="docs_hide_featured">{{ __('Hide featured articles') }}</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Features articles section background color') }}</label><br>
                                    <input id="docs_features_bg_color" name="docs_features_bg_color" value="{{ get_template_value($template->id, 'docs_features_bg_color') ?? '#FBF7F0' }}">
                                    <script>
                                        $('#docs_features_bg_color').spectrum({
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


                    <div class="col-md-6 col-12">
                        <div class="card bg-light p-3 mb-3">
                            <h5>{{ __('Search bar settings') }}</h5>

                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    @php
                                        $search_bar_title_size = get_template_value($template->id, 'search_bar_title_size') ?? config('defaults.h2_size');
                                    @endphp
                                    <label>{{ __('Title size') }}</label>
                                    <select class="form-select" name="search_bar_title_size">
                                        @foreach (template_font_sizes() as $selectes_search_bar_title_size)
                                            <option @if ($search_bar_title_size == $selectes_search_bar_title_size->value) selected @endif value="{{ $selectes_search_bar_title_size->value }}">{{ $selectes_search_bar_title_size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-sm-12">
                                    @php
                                        $search_bar_subtitle_size = get_template_value($template->id, 'search_bar_subtitle_size') ?? config('defaults.h4_size');
                                    @endphp
                                    <label>{{ __('Subtite size') }}</label>
                                    <select class="form-select" name="search_bar_subtitle_size">
                                        @foreach (template_font_sizes() as $selectes_search_bar_subtitle_size)
                                            <option @if ($search_bar_subtitle_size == $selectes_search_bar_subtitle_size->value) selected @endif value="{{ $selectes_search_bar_subtitle_size->value }}">{{ $selectes_search_bar_subtitle_size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Search bar background color') }}</label><br>
                                        <input id="docs_search_bar_bg_color" name="docs_search_bar_bg_color" value="{{ get_template_value($template->id, 'docs_search_bar_bg_color') ?? '#f9f3e8' }}">
                                        <script>
                                            $('#docs_search_bar_bg_color').spectrum({
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

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Search bar text color') }}</label><br>
                                        <input id="docs_search_bar_font_color" name="docs_search_bar_font_color" value="{{ get_template_value($template->id, 'docs_search_bar_font_color') ?? '#3e3e3e' }}">
                                        <script>
                                            $('#docs_search_bar_font_color').spectrum({
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

                            <div class="form-group mb-2">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='docs_disable_search_autocomplete'>
                                    <input class="form-check-input" type="checkbox" id="docs_disable_search_autocomplete" name="docs_disable_search_autocomplete" @if (get_template_value($template->id, 'docs_disable_search_autocomplete')) checked @endif>
                                    <label class="form-check-label" for="docs_disable_search_autocomplete">{{ __('Disable search autocomplete') }}</label>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='docs_search_bar_use_cover'>
                                    <input class="form-check-input" type="checkbox" id="docs_search_bar_use_cover" name="docs_search_bar_use_cover" @if (get_template_value($template->id, 'docs_search_bar_use_cover')) checked @endif>
                                    <label class="form-check-label" for="docs_search_bar_use_cover">{{ __('Use cover image') }}</label>
                                </div>
                            </div>

                            <script>
                                $('#docs_search_bar_use_cover').change(function() {
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

                            <div id="hidden_div_image" style="display: @if (get_template_value($template->id, 'docs_search_bar_use_cover')) block @else none @endif" class="mt-2">

                                <div class="form-group col-12">
                                    <label for="formFile" class="form-label">{{ __('Image') }}</label>
                                    <input class="form-control" type="file" id="formFile" name="docs_search_bar_cover_img">
                                </div>
                                @if (get_template_value($template->id, 'docs_search_bar_cover_img') ?? null)
                                    <a target="_blank" href="{{ image(get_template_value($template->id, 'docs_search_bar_cover_img')) }}"><img style="max-width: 300px; max-height: 100px;"
                                            src="{{ image(get_template_value($template->id, 'docs_search_bar_cover_img')) }}" class="img-fluid"></a>
                                @endif

                                <div class="form-group col-12 mb-0 mt-3">
                                    <div class="form-check form-switch">
                                        <input type='hidden' value='' name='docs_search_bar_cover_dark'>
                                        <input class="form-check-input" type="checkbox" id="docs_search_bar_cover_dark" name="docs_search_bar_cover_dark" @if (get_template_value($template->id, 'docs_search_bar_cover_dark')) checked @endif>
                                        <label class="form-check-label" for="docs_search_bar_cover_dark">{{ __('Add dark layer to background cover') }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card bg-light p-3 mb-3">
                            <h5>{{ __('Knowledge Base article page settings') }}</h5>

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Categories sidebar position') }}</label>
                                    <select class="form-select" name="docs_categ_sidebar_position">
                                        <option @if (get_template_value($template->id, 'docs_categ_sidebar_position') == 'left') selected @endif value="left">{{ __('Left') }}</option>
                                        <option @if (get_template_value($template->id, 'docs_categ_sidebar_position') == 'right') selected @endif value="right">{{ __('Right') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Sidebar background color') }}</label><br>
                                    <input id="docs_categ_sidebar_bg_color" name="docs_categ_sidebar_bg_color" value="{{ get_template_value($template->id, 'docs_categ_sidebar_bg_color') ?? '#fbf7f0' }}">
                                    <script>
                                        $('#docs_categ_sidebar_bg_color').spectrum({
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

                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button type="submit" class="btn btn-primary mt-3">{{ __('Update template') }}</button>
            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
