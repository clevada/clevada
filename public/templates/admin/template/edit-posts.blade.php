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

            <div class="float-end"><a class="btn btn-secondary" target="_blank" href="{{ route('posts', ['preview_template_id' => $template->id]) }}"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview template') }}</a></div>

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
                            <h5>{{ __('Articles listing style (main page and categories)') }}</h5>

                            <div class="form-group col-md-6 col-sm-12 col-12">
                                <label>{{ __('Select listing style') }}</label>
                                <select name="posts_style" class="form-select" id="posts_style" onchange="showDiv()">
                                    <option @if (get_template_value($template->id, 'posts_style') == 'rows') selected @endif value="rows">{{ __('Rows') }}</option>
                                    <option @if (get_template_value($template->id, 'posts_style') == 'columns') selected @endif value="columns">{{ __('Columns') }}</option>
                                </select>
                            </div>

                            <script>
                                function showDiv() {
                                    var select = document.getElementById('posts_style');
                                    var value = select.options[select.selectedIndex].value;

                                    if (value == 'columns') {
                                        document.getElementById('div_columns').style.display = 'block';
                                    } else {
                                        document.getElementById('div_columns').style.display = 'none';
                                    }
                                }
                            </script>

                            <div id="div_columns" style="display: @if (get_template_value($template->id, 'posts_style') == 'columns') block @else none @endif">
                                <div class="form-group col-md-6 col-sm-12 col-12">
                                    <label>{{ __('Number of columns') }}</label>
                                    <select name="posts_columns" class="form-select">
                                        <option @if (get_template_value($template->id, 'posts_columns') == '2') selected @endif value="2">2</option>
                                        <option @if (get_template_value($template->id, 'posts_columns') == '3') selected @endif value="3">3</option>
                                        <option @if (get_template_value($template->id, 'posts_columns') == '4') selected @endif value="4">4</option>
                                        <option @if (get_template_value($template->id, 'posts_columns') == '6') selected @endif value="6">6</option>
                                    </select>
                                    <div class="form-text">
                                        {{ __('Note: This is the number of maximum columns for large displays. For smaller displays, the columns are changed automatically') }}.</div>
                                </div>
                            </div>

                            <hr>

                            <h5> {{ __('Posts titles') }}</h5>

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Title link color') }}</label><br>
                                        <input id="posts_title_link_color" name="posts_title_link_color"
                                            value="{{ get_template_value($template->id, 'posts_title_link_color') ?? config('defaults.link_color') }}">
                                        <script>
                                            $('#posts_title_link_color').spectrum({
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
                                        <label>{{ __('Link color on hover') }}</label><br>
                                        <input id="posts_title_link_color_hover" name="posts_title_link_color_hover"
                                            value="{{ get_template_value($template->id, 'posts_title_link_color_hover') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#posts_title_link_color_hover').spectrum({
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
                                        <label>{{ __('Links underline color') }}</label><br>
                                        <input id="posts_title_link_color_underline" name="posts_title_link_color_underline"
                                            value="{{ get_template_value($template->id, 'posts_title_link_color_underline') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#posts_title_link_color_underline').spectrum({
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
                                        <select class="form-select" name="posts_title_link_decoration">
                                            <option @if (get_template_value($template->id, 'posts_title_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'posts_title_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration on hover') }}</label>
                                        <select class="form-select" name="posts_title_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Links hover animation') }}</label>
                                        <select class="form-select" name="posts_title_link_hover_decoration_animation">
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration_animation') == 'none') selected @endif value="none">{{ __('No animation') }}</option>
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration_animation') == 'slide_left') selected @endif value="slide_left">{{ __('Slide from left') }}</option>
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration_animation') == 'slide_right') selected @endif value="slide_right">{{ __('Slide from right') }}</option>
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration_animation') == 'scale') selected @endif value="scale">{{ __('Scale from center') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="form-group col-md-4 col-sm-12">
                                    <label>{{ __('Display article date') }}</label>
                                    <select name="posts_show_date" class="form-select">
                                        <option @if (get_template_value($template->id, 'posts_show_date') == 'date') selected @endif value="date">{{ __('Date only') }}</option>
                                        <option @if (get_template_value($template->id, 'posts_show_date') == 'datetime') selected @endif value="datetime">{{ __('Date and time') }}</option>
                                        <option @if (get_template_value($template->id, 'posts_show_date') == 'no') selected @endif value="no">{{ __('Do not show') }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 col-sm-12">
                                    <label>{{ __('Display article author') }}</label>
                                    <select name="posts_show_author" class="form-select">
                                        <option @if (get_template_value($template->id, 'posts_show_author') == 'name') selected @endif value="date">{{ __('Author name only') }}</option>
                                        <option @if (get_template_value($template->id, 'posts_show_author') == 'name_avatar') selected @endif value="datetime">{{ __('Author name and avatar') }}</option>
                                        <option @if (get_template_value($template->id, 'posts_show_author') == 'no') selected @endif value="no">{{ __('Do not show') }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 col-sm-12">
                                    @php
                                        $posts_titles_font_size = get_template_value($template->id, 'posts_titles_font_size') ?? config('defaults.h4_size');
                                    @endphp
                                    <label>{{ __('Titles font size') }}</label>
                                    <select class="form-select" name="posts_titles_font_size">
                                        @foreach (template_font_sizes() as $selectes_font_size_title)
                                            <option @if ($posts_titles_font_size == $selectes_font_size_title->value) selected @endif value="{{ $selectes_font_size_title->value }}">{{ $selectes_font_size_title->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_hide_image'>
                                    <input class="form-check-input" type="checkbox" id="posts_hide_image" name="posts_hide_image" @if (get_template_value($template->id, 'posts_hide_image')) checked @endif>
                                    <label class="form-check-label" for="posts_hide_image">{{ __('Hide main image') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_image_shaddow'>
                                    <input class="form-check-input" type="checkbox" id="posts_image_shaddow" name="posts_image_shaddow" @if (get_template_value($template->id, 'posts_image_shaddow')) checked @endif>
                                    <label class="form-check-label" for="posts_image_shaddow">{{ __('Add shaddow for main image') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_show_summary'>
                                    <input class="form-check-input" type="checkbox" id="posts_show_summary" name="posts_show_summary" @if (get_template_value($template->id, 'posts_show_summary')) checked @endif>
                                    <label class="form-check-label" for="posts_show_summary">{{ __('Show content summary') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_show_time_read'>
                                    <input class="form-check-input" type="checkbox" id="posts_show_time_read" name="posts_show_time_read" @if (get_template_value($template->id, 'posts_show_time_read')) checked @endif>
                                    <label class="form-check-label" for="posts_show_time_read">{{ __('Show time to read') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_show_comments_count'>
                                    <input class="form-check-input" type="checkbox" id="posts_show_comments_count" name="posts_show_comments_count" @if (get_template_value($template->id, 'posts_show_comments_count')) checked @endif>
                                    <label class="form-check-label" for="posts_show_comments_count">{{ __('Show comments counter') }}</label>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_show_read_more'>
                                    <input class="form-check-input" type="checkbox" id="posts_show_read_more" name="posts_show_read_more" @if (get_template_value($template->id, 'posts_show_read_more')) checked @endif>
                                    <label class="form-check-label" for="posts_show_read_more">{{ __('Show "Read more" text') }}</label>
                                </div>
                            </div>

                            <hr>
                            
                            <h5>{{ __('Articles card style') }}</h5>
                            <div class="form-group col-md-6 col-12">
                                <label>{{ __('Select card style') }}</label>
                                <select name="posts_card_style" class="form-select" id="posts_card_style" onchange="showCardStyleDiv()">
                                    <option @if (get_template_value($template->id, 'posts_card_style') == 'none') selected @endif value="none">{{ __('No card') }}</option>
                                    <option @if (get_template_value($template->id, 'posts_card_style') == 'box') selected @endif value="box">{{ __('Card box') }}</option>
                                </select>
                            </div>
                            <script>
                                function showCardStyleDiv() {
                                    var select = document.getElementById('posts_card_style');
                                    var value = select.options[select.selectedIndex].value;

                                    if (value == 'box') {
                                        document.getElementById('div_posts_card_style').style.display = 'block';
                                    } else {
                                        document.getElementById('div_posts_card_style').style.display = 'none';
                                    }
                                }
                            </script>

                            <div id="div_posts_card_style" style="display: @if (get_template_value($template->id, 'posts_card_style') == 'box') block @else none @endif">

                                <div class="form-group">
                                    <input class="form-control form-control-color" name="posts_card_bg_color" id="posts_card_bg_color"
                                        value="{{ get_template_value($template->id, 'posts_card_bg_color') ?? config('defaults.bg_color') }}">
                                    <label>{{ __('Card background color') }}</label>
                                    <script>
                                        $('#posts_card_bg_color').spectrum({
                                            type: "color",
                                            showInput: true,
                                            showInitial: true,
                                            showAlpha: false,
                                            showButtons: false,
                                            allowEmpty: false,
                                        });
                                    </script>
                                </div>

                                <div class="form-group mb-0">
                                    <div class="form-check form-switch">
                                        <input type='hidden' value='' name='posts_card_shaddow'>
                                        <input class="form-check-input" type="checkbox" id="posts_card_shaddow" name="posts_card_shaddow" @if (get_template_value($template->id, 'posts_card_shaddow')) checked @endif>
                                        <label class="form-check-label" for="posts_card_shaddow">{{ __('Add shaddow to article card') }}</label>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    <div class="col-md-6 col-12">
                        <div class="card bg-light p-3 mb-3">
                            <h5>{{ __('Article page settings') }}</h5>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='post_hide_image'>
                                    <input class="form-check-input" type="checkbox" id="post_hide_image" name="post_hide_image" @if (get_template_value($template->id, 'post_hide_image')) checked @endif>
                                    <label class="form-check-label" for="post_hide_image">{{ __('Hide main image') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='post_image_shaddow'>
                                    <input class="form-check-input" type="checkbox" id="post_image_shaddow" name="post_image_shaddow" @if (get_template_value($template->id, 'post_image_shaddow')) checked @endif>
                                    <label class="form-check-label" for="post_image_shaddow">{{ __('Add shaddow for main image') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='post_show_social'>
                                    <input class="form-check-input" type="checkbox" id="post_show_social" name="post_show_social" @if (get_template_value($template->id, 'post_show_social')) checked @endif>
                                    <label class="form-check-label" for="post_show_social">{{ __('Show share buttons from social networks') }}</label>
                                    <div class="form-text"><a href="{{ route('admin.config.integration') }}">{{ __('Manage share buttons settings') }}</a></div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='post_image_force_full_width'>
                                    <input class="form-check-input" type="checkbox" id="post_image_force_full_width" name="post_image_force_full_width" @if (get_template_value($template->id, 'post_image_force_full_width')) checked @endif>
                                    <label class="form-check-label" for="post_image_force_full_width">{{ __('Force main image full width') }}</label>
                                    <div class="form-text">{{ __('This apply if main image width is smaller than post container width') }}</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label>{{ __('Main image height') }}</label>
                                <select name="post_image_height" class="form-select">
                                    <option @if (get_template_value($template->id, 'post_image_height') == 'original') selected @endif value="original">{{ __('Original') }}</option>
                                    <option @if (get_template_value($template->id, 'post_image_height') == '60vh') selected @endif value="60vh">{{ __('Tight') }}</option>
                                    <option @if (get_template_value($template->id, 'post_image_height') == '45vh') selected @endif value="45vh">{{ __('Tighter') }}</option>
                                    <option @if (get_template_value($template->id, 'post_image_height') == '35vh') selected @endif value="35vh">{{ __('Slim') }}</option>
                                </select>
                            </div>

                            <hr>

                            <div class="form-group col-md-6 col-12">
                                <label>{{ __('Tags style') }}</label>
                                <select name="post_tags_style" class="form-select" id="post_tags_style" onchange="showTagsDiv()">
                                    <option @if (get_template_value($template->id, 'post_tags_style') == 'link') selected @endif value="link">{{ __('Regular links') }}</option>
                                    <option @if (get_template_value($template->id, 'post_tags_style') == 'box') selected @endif value="box">{{ __('Boxes') }}</option>
                                </select>
                            </div>

                            <script>
                                function showTagsDiv() {
                                    var select = document.getElementById('post_tags_style');
                                    var value = select.options[select.selectedIndex].value;

                                    if (value == 'box') {
                                        document.getElementById('hidden_div_tags').style.display = 'block';
                                    } else {
                                        document.getElementById('hidden_div_tags').style.display = 'none';
                                    }
                                }
                            </script>

                            <div id="hidden_div_tags" style="display: @if (get_template_value($template->id, 'post_tags_style') == 'box') block @else none @endif">

                                <div class="form-group">
                                    <input class="form-control form-control-color" name="post_tags_box_bg_color" id="post_tags_box_bg_color"
                                        value="{{ get_template_value($template->id, 'post_tags_box_bg_color') ?? '#999999' }}">
                                    <label>{{ __('Box background color') }}</label>
                                    <script>
                                        $('#post_tags_box_bg_color').spectrum({
                                            type: "color",
                                            showInput: true,
                                            showInitial: true,
                                            showAlpha: false,
                                            showButtons: false,
                                            allowEmpty: false,
                                        });
                                    </script>
                                </div>

                                <div class="form-group">
                                    <input class="form-control form-control-color" name="post_tags_box_font_color" id="post_tags_box_font_color"
                                        value="{{ get_template_value($template->id, 'post_tags_box_font_color') ?? '#ffffff' }}">
                                    <label>{{ __('Box font color') }}</label>
                                    <script>
                                        $('#post_tags_box_font_color').spectrum({
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
