<!-- Color picker -->
<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">


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

            <div class="float-end"><a class="btn btn-secondary" target="_blank" href="{{ route('forum', ['preview_template_id' => $template->id]) }}"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview forum section') }}</a></div>

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

                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="card bg-light p-3 mb-3">
                            <h5>{{ __('Cards header settings') }}</h5>

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Background color') }}</label><br>
                                        <input id="forum_card_header_bg_color" name="forum_card_header_bg_color" value="{{ get_template_value($template->id, 'forum_card_header_bg_color') ?? '#16537E' }}">
                                        <script>
                                            $('#forum_card_header_bg_color').spectrum({
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
                                        <label>{{ __('Link color') }}</label><br>
                                        <input id="forum_card_header_link_color" name="forum_card_header_link_color"
                                            value="{{ get_template_value($template->id, 'forum_card_header_link_color') ?? '#FFFFFF' }}">
                                        <script>
                                            $('#forum_card_header_link_color').spectrum({
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
                                        <label>{{ __('Text color') }}</label><br>
                                        <input id="forum_card_header_font_color" name="forum_card_header_font_color"
                                            value="{{ get_template_value($template->id, 'forum_card_header_font_color') ?? '#FFFFFF' }}">
                                        <script>
                                            $('#forum_card_header_font_color').spectrum({
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
                            <h5>{{ __('Categories cards settings') }}</h5>

                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Links color') }}</label><br>
                                        <input id="forum_categ_link_color" name="forum_categ_link_color" value="{{ get_template_value($template->id, 'forum_categ_link_color') ?? '#16537E' }}">
                                        <script>
                                            $('#forum_categ_link_color').spectrum({
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
                                        <input id="forum_categ_link_color_hover" name="forum_categ_link_color_hover"
                                            value="{{ get_template_value($template->id, 'forum_categ_link_color_hover') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#forum_categ_link_color_hover').spectrum({
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
                                        <input id="forum_categ_link_color_underline" name="forum_categ_link_color_underline"
                                            value="{{ get_template_value($template->id, 'forum_categ_link_color_underline') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#forum_categ_link_color_underline').spectrum({
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
                                        <label>{{ __('Card background color') }}</label><br>
                                        <input id="forum_categ_bg_color" name="forum_categ_bg_color" value="{{ get_template_value($template->id, 'forum_categ_bg_color') ?? '#F8F9FA' }}">
                                        <script>
                                            $('#forum_categ_bg_color').spectrum({
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
                                        <label>{{ __('Card border color') }}</label><br>
                                        <input id="forum_categ_border_color" name="forum_categ_border_color" value="{{ get_template_value($template->id, 'forum_categ_border_color') ?? '#F8F9FA' }}">
                                        <script>
                                            $('#forum_categ_border_color').spectrum({
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
                                        <label>{{ __('Text color') }}</label><br>
                                        <input id="forum_categ_font_color" name="forum_categ_font_color"
                                            value="{{ get_template_value($template->id, 'forum_categ_font_color') ?? config('defaults.font_color') }}">
                                        <script>
                                            $('#forum_categ_font_color').spectrum({
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
                                        <select class="form-select" name="forum_categ_link_decoration">
                                            <option @if (get_template_value($template->id, 'forum_categ_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'forum_categ_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Decoration on hover') }}</label>
                                        <select class="form-select" name="forum_categ_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'forum_categ_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'forum_categ_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            @php
                                                $forum_categ_font_size = get_template_value($template->id, 'forum_categ_font_size') ?? config('defaults.font_size');
                                            @endphp
                                            <label>{{ __('Category font size') }}</label>
                                            <select class="form-select" name="forum_categ_font_size">
                                                @foreach (template_font_sizes() as $fontsize)
                                                    <option @if ($forum_categ_font_size == $fontsize->value) selected @endif value="{{ $fontsize->value }}">{{ $fontsize->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            @php
                                                $forum_categ_font_weight = get_template_value($template->id, 'forum_categ_font_weight') ?? 'normal';
                                            @endphp
                                            <label>{{ __('Category font weight') }}</label>
                                            <select class="form-select" name="forum_categ_font_weight">
                                                <option @if ($forum_categ_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                                <option @if ($forum_categ_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            @php
                                                $forum_subcateg_font_size = get_template_value($template->id, 'forum_subcateg_font_size') ?? config('defaults.font_size');
                                            @endphp
                                            <label>{{ __('Subcategory font size') }}</label>
                                            <select class="form-select" name="forum_subcateg_font_size">
                                                @foreach (template_font_sizes() as $fontsize)
                                                    <option @if ($forum_subcateg_font_size == $fontsize->value) selected @endif value="{{ $fontsize->value }}">{{ $fontsize->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            @php
                                                $forum_subcateg_font_weight = get_template_value($template->id, 'forum_subcateg_font_weight') ?? 'normal';
                                            @endphp
                                            <label>{{ __('Subcategory font weight') }}</label>
                                            <select class="form-select" name="forum_subcateg_font_weight">
                                                <option @if ($forum_subcateg_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                                <option @if ($forum_subcateg_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="card bg-light p-3 mb-3">
                            <h5>{{ __('Posts author bar') }}</h5>

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Bar style') }}</label>
                                    <select class="form-select" name="forum_posts_author_layout">
                                        <option @if (get_template_value($template->id, 'forum_posts_author_bar') == 'horizontal') selected @endif value="horizontal">{{ __('Horizontal') }}</option>
                                        <option @if (get_template_value($template->id, 'forum_posts_author_bar') == 'vertical') selected @endif value="vertical">{{ __('Vertical') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Bar background color') }}</label><br>
                                        <input id="forum_post_author_bar_bg_color" name="forum_post_author_bar_bg_color"
                                            value="{{ get_template_value($template->id, 'forum_post_author_bar_bg_color') ?? '#16537E' }}">
                                        <script>
                                            $('#forum_post_author_bar_bg_color').spectrum({
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
                                        <label>{{ __('Bar text color') }}</label><br>
                                        <input id="forum_post_author_bar_font_color" name="forum_post_author_bar_font_color"
                                            value="{{ get_template_value($template->id, 'forum_post_author_bar_font_color') ?? '#FFFFFF' }}">
                                        <script>
                                            $('#forum_post_author_bar_font_color').spectrum({
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
                                        <label>{{ __('Bar links color') }}</label><br>
                                        <input id="forum_post_author_bar_link_color" name="forum_post_author_bar_link_color"
                                            value="{{ get_template_value($template->id, 'forum_post_author_bar_link_color') ?? '#16537E' }}">
                                        <script>
                                            $('#forum_post_author_bar_link_color').spectrum({
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
                                        <select class="form-select" name="forum_post_author_bar_link_decoration">
                                            <option @if (get_template_value($template->id, 'forum_post_author_bar_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'forum_post_author_bar_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Decoration on hover') }}</label>
                                        <select class="form-select" name="forum_post_author_bar_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'forum_post_author_bar_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'forum_post_author_bar_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <h5>{{ __('Posts cards settings') }}</h5>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Card background color') }}</label><br>
                                        <input id="forum_post_bg_color" name="forum_post_bg_color" value="{{ get_template_value($template->id, 'forum_post_bg_color') ?? '#F8F9FA' }}">
                                        <script>
                                            $('#forum_post_bg_color').spectrum({
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
                                        <label>{{ __('Card border color') }}</label><br>
                                        <input id="forum_post_border_color" name="forum_post_border_color" value="{{ get_template_value($template->id, 'forum_post_border_color') ?? '#F8F9FA' }}">
                                        <script>
                                            $('#forum_post_border_color').spectrum({
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
                                        <label>{{ __('Text color') }}</label><br>
                                        <input id="forum_post_font_color" name="forum_post_font_color"
                                            value="{{ get_template_value($template->id, 'forum_post_font_color') ?? config('defaults.font_color') }}">
                                        <script>
                                            $('#forum_post_font_color').spectrum({
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
                                        <label>{{ __('Links color') }}</label><br>
                                        <input id="forum_post_link_color" name="forum_post_link_color" value="{{ get_template_value($template->id, 'forum_post_link_color') ?? '#16537E' }}">
                                        <script>
                                            $('#forum_post_link_color').spectrum({
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
                                        <input id="forum_post_link_color_hover" name="forum_post_link_color_hover"
                                            value="{{ get_template_value($template->id, 'forum_post_link_color_hover') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#forum_post_link_color_hover').spectrum({
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
                                        <input id="forum_post_link_color_underline" name="forum_post_link_color_underline"
                                            value="{{ get_template_value($template->id, 'forum_post_link_color_underline') ?? config('defaults.link_color_hover') }}">
                                        <script>
                                            $('#forum_post_link_color_underline').spectrum({
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
                                        <select class="form-select" name="forum_posts_title_link_decoration">
                                            <option @if (get_template_value($template->id, 'posts_title_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'posts_title_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Decoration on hover') }}</label>
                                        <select class="form-select" name="posts_title_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'posts_title_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button type="submit" class="btn btn-primary mt-3">{{ __('Update template') }}</button>
            </form>


            <hr>

            <div class="row">
                <div class="col-12 col-md-6">
                    @include('admin.template.layouts.edit-layout-top') 
                </div>

                <div class="col-12 col-md-6">
                    @include('admin.template.layouts.edit-layout-bottom') 
                </div>

                <div class="col-12 col-md-6">
                    @include('admin.template.layouts.edit-layout-sidebar') 
                </div>
            </div>

        </div>
        <!-- end card-body -->

    </div>

</section>
