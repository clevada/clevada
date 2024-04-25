@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.template') }}">{{ __('Template builder') }}</a></li>
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

        <div class="mb-3">
            @include('admin.template.includes.menu-template-edit')
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success py-2">
                @if ($message == 'updated')
                    <div class="fw-bold">{{ __('Updated') }}</div>
                    <i class="bi bi-exclamation-circle"></i>
                    {{ __("Note: if you don't see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.") }}
                @endif
            </div>
        @endif

        <form method="post">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 col-12">
                    <div class="card bg-light p-3 mb-3">
                        <div class="fw-bold fs-5 mb-2">{{ __('Articles listing style (main page and categories)') }}</div>

                        <div class="row">
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label>{{ __('Select listing style') }}</label>
                                <select name="tpl_posts_style" class="form-select" id="tpl_posts_style" onchange="showDiv()">
                                    <option @if (($config->tpl_posts_style ?? null) == 'rows') selected @endif value="rows">{{ __('Rows') }}</option>
                                    <option @if (($config->tpl_posts_style ?? null) == 'columns') selected @endif value="columns">{{ __('Columns') }}</option>
                                </select>
                            </div>

                            <script>
                                function showDiv() {
                                    var select = document.getElementById('tpl_posts_style');
                                    var value = select.options[select.selectedIndex].value;

                                    if (value == 'columns') {
                                        document.getElementById('div_columns').style.display = 'block';
                                    } else {
                                        document.getElementById('div_columns').style.display = 'none';
                                    }
                                }
                            </script>

                            <div class="form-group col-md-6 col-sm-12 col-12">
                                <div id="div_columns" style="display: @if (($config->tpl_posts_style ?? null) == 'columns') block @else none @endif">
                                    <label>{{ __('Number of columns') }}</label>
                                    <select name="tpl_posts_columns" class="form-select">
                                        <option @if (($config->tpl_posts_columns ?? null) == '2') selected @endif value="2">2</option>
                                        <option @if (($config->tpl_posts_columns ?? null) == '3') selected @endif value="3">3</option>
                                        <option @if (($config->tpl_posts_columns ?? null) == '4') selected @endif value="4">4</option>
                                        <option @if (($config->tpl_posts_columns ?? null) == '6') selected @endif value="6">6</option>
                                    </select>
                                    <div class="form-text">
                                        {{ __('Note: This is the number of maximum columns for large displays. For smaller displays, the columns are changed automatically') }}.</div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label>{{ __('Display article date') }}</label>
                                <select name="tpl_posts_show_date" class="form-select">
                                    <option @if (($config->tpl_posts_show_date ?? null) == 'date') selected @endif value="date">{{ __('Date only') }}</option>
                                    <option @if (($config->tpl_posts_show_date ?? null) == 'datetime') selected @endif value="datetime">{{ __('Date and time') }}</option>
                                    <option @if (($config->tpl_posts_show_date ?? null) == '') selected @endif value="">{{ __('Do not show') }}</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label>{{ __('Display article author') }}</label>
                                <select name="tpl_posts_show_author" class="form-select">
                                    <option @if (($config->tpl_posts_show_author ?? null) == 'name') selected @endif value="name">{{ __('Author name only') }}</option>
                                    <option @if (($config->tpl_posts_show_author ?? null) == 'name_avatar') selected @endif value="name_avatar">{{ __('Author name and avatar') }}</option>
                                    <option @if (($config->tpl_posts_show_author ?? null) == 'no') selected @endif value="no">{{ __('Do not show') }}</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label>{{ __('Article summary') }}</label>
                                <select name="tpl_posts_summary" class="form-select">
                                    <option @if (($config->tpl_posts_summary ?? null) == '') selected @endif value="">{{ __('Do not show') }}</option>
                                    <option @if (($config->tpl_posts_summary ?? null) == 'text-clamp-2') selected @endif value="text-clamp-2">{{ __('Maximum 2 rows') }}</option>
                                    <option @if (($config->tpl_posts_summary ?? null) == 'text-clamp-3') selected @endif value="text-clamp-3">{{ __('Maximum 3 rows') }}</option>
                                    <option @if (($config->tpl_posts_summary ?? null) == 'text-clamp-4') selected @endif value="text-clamp-4">{{ __('Maximum 4 rows') }}</option>
                                    <option @if (($config->tpl_posts_summary ?? null) == 'text-clamp-5') selected @endif value="text-clamp-5">{{ __('Maximum 5 rows') }}</option>
                                    <option @if (($config->tpl_posts_summary ?? null) == 'text-clamp-6') selected @endif value="text-clamp-6">{{ __('Maximum 6 rows') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_categs_hide_breadcrumb'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_categs_hide_breadcrumb" name="tpl_posts_categs_hide_breadcrumb" @if ($config->tpl_posts_categs_hide_breadcrumb ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_categs_hide_breadcrumb">{{ __('Hide breadcrumb navigation') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_hide_image'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_hide_image" name="tpl_posts_hide_image" @if ($config->tpl_posts_hide_image ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_hide_image">{{ __('Hide main image') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_image_shadow'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_image_shadow" name="tpl_posts_image_shadow" @if ($config->tpl_posts_image_shadow ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_image_shadow">{{ __('Add shaddow for main image') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_image_rounded'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_image_rounded" name="tpl_posts_image_rounded" @if ($config->tpl_posts_image_rounded ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_image_rounded">{{ __('Rounded border for main image') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_image_zoom'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_image_zoom" name="tpl_posts_image_zoom" @if ($config->tpl_posts_image_zoom ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_image_zoom">{{ __('Add zoom effect on mouse over image') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_show_time_read'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_show_time_read" name="tpl_posts_show_time_read" @if ($config->tpl_posts_show_time_read ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_show_time_read">{{ __('Show time to read') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_show_likes_count'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_show_likes_count" name="tpl_posts_show_likes_count" @if ($config->tpl_posts_show_likes_count ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_show_likes_count">{{ __('Show likes counter') }}</label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_show_views_count'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_show_views_count" name="tpl_posts_show_views_count" @if ($config->tpl_posts_show_views_count ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_show_views_count">{{ __('Show views counter') }}</label>
                            </div>
                        </div>
                    </div>



                    <div class="card bg-light p-3 mb-3">
                        <div class="fw-bold fs-5 mb-2">{{ __('Article page') }}</div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_post_hide_image'>
                                <input class="form-check-input" type="checkbox" id="tpl_post_hide_image" name="tpl_post_hide_image" @if ($config->tpl_post_hide_image ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_post_hide_image">{{ __('Hide main image') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_post_image_shadow'>
                                <input class="form-check-input" type="checkbox" id="tpl_post_image_shadow" name="tpl_post_image_shadow" @if ($config->tpl_post_image_shadow ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_post_image_shadow">{{ __('Add shadow for main image') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_post_head_align_center'>
                                <input class="form-check-input" type="checkbox" id="tpl_post_head_align_center" name="tpl_post_head_align_center" @if ($config->tpl_post_head_align_center ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_post_head_align_center">{{ __('Align center article header (title, meta and main image)') }}</label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_post_hide_breadcrumb'>
                                <input class="form-check-input" type="checkbox" id="tpl_post_hide_breadcrumb" name="tpl_post_hide_breadcrumb" @if ($config->tpl_post_hide_breadcrumb ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_post_hide_breadcrumb">{{ __('Hide breadcrumb navigation') }}</label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_post_image_force_full_width'>
                                <input class="form-check-input" type="checkbox" id="tpl_post_image_force_full_width" name="tpl_post_image_force_full_width" @if ($config->tpl_post_image_force_full_width ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_post_image_force_full_width">{{ __('Force main image full width') }}</label>
                                <div class="text-muted small">{{ __('If image is smaller, force image to be full width') }}</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_post_image_rounded'>
                                <input class="form-check-input" type="checkbox" id="tpl_post_image_rounded" name="tpl_post_image_rounded" @if ($config->tpl_post_image_rounded ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_post_image_rounded">{{ __('Rounded border for main image') }}</label>
                            </div>
                        </div>

                        <div class="form-group col-md-6 col-12">
                            <label>{{ __('Main image height') }}</label>
                            <select name="tpl_post_image_height_class" class="form-select">
                                <option @if (($config->tpl_post_image_height_class ?? null) == '') selected @endif value="">{{ __('Original') }}</option>
                                <option @if (($config->tpl_post_image_height_class ?? null) == 'post-main-img-tight') selected @endif value="post-main-img-tight">{{ __('Tight') }}</option>
                                <option @if (($config->tpl_post_image_height_class ?? null) == 'post-main-img-tighter') selected @endif value="post-main-img-tighter">{{ __('Tighter') }}</option>
                                <option @if (($config->tpl_post_image_height_class ?? null) == 'post-main-img-slim') selected @endif value="post-main-img-slim">{{ __('Slim') }}</option>
                            </select>
                        </div>

                    </div>
                </div>


                <div class="col-md-6 col-12">

                    <div class="card bg-light p-3 pb-0 mb-3">
                        <div class="fw-bold fs-5 mb-2">{{ __('Categories list') }}</div>

                        <div class="form-group col-12">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_hide_categs_list'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_hide_categs_list" name="tpl_posts_hide_categs_list" @if ($config->tpl_posts_hide_categs_list ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_hide_categs_list">{{ __('Hide categories list from posts main page') }}</label>
                            </div>
                            <div class="text-muted small">{{ __('Categories list are displayed on posts main page, above the posts items.') }}</div>
                        </div>

                        <div class="form-group col-md-6 col-lg-4 col-12">
                            <label>{{ __('Categories list layout') }}</label>
                            <select name="tpl_posts_categs_list_layout" class="form-select">
                                <option @if (($config->tpl_posts_categs_list_layout ?? null) == 'links') selected @endif value="links">{{ __('Links only') }}</option>
                                <option @if (($config->tpl_posts_categs_list_layout ?? null) == 'boxes') selected @endif value="boxes">{{ __('Links inside boxes') }}</option>
                            </select>
                        </div>

                        <div class="form-group col-12">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_categs_list_show_counter'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_categs_list_show_counter" name="tpl_posts_categs_list_show_counter" @if ($config->tpl_posts_categs_list_show_counter ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_categs_list_show_counter">{{ __('Show counter (number of posts of the category)') }}</label>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <div class="form-check form-switch mb-0">
                                <input type='hidden' value='' name='tpl_posts_categs_list_hide_if_empty'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_categs_list_hide_if_empty" name="tpl_posts_categs_list_hide_if_empty" @if ($config->tpl_posts_categs_list_hide_if_empty ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_categs_list_hide_if_empty">{{ __('Do not show the categories with zero posts.') }}</label>
                            </div>
                        </div>

                    </div>

                    <div class="card bg-light p-3 mb-3">


                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_container_fluid'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_container_fluid" name="tpl_posts_container_fluid" @if ($config->tpl_posts_container_fluid ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_container_fluid">{{ __('Use fluid width (full width) for posts pages') }}</label>
                            </div>
                        </div>

                        <div class="fw-bold fs-5">{{ __('Posts layouts') }}</div>

                        <div class="text-muted mb-3">
                            {{ __('You can add content sections (above or below the main content) or sidebars using layouts.') }}
                            <a target="_blank" href="{{ route('admin.template.layouts') }}"><b>{{ __('Manage layouts') }}</b></a>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('Posts main page layout') }}</label>
                                    <select name="tpl_posts_home_layout_id" class="form-select">
                                        <option value="">- {{ __('Default (full width)') }} -</option>
                                        @foreach ($layouts as $layout)
                                            <option value="{{ $layout->id }}" @if (($config->tpl_posts_home_layout_id ?? null) == $layout->id) selected @endif>
                                                {{ $layout->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('Posts categories pages layout') }}</label>
                                    <select name="tpl_posts_categ_layout_id" class="form-select">
                                        <option value="">- {{ __('Default (full width)') }} -</option>
                                        @foreach ($layouts as $layout)
                                            <option value="{{ $layout->id }}" @if (($config->tpl_posts_categ_layout_id ?? null) == $layout->id) selected @endif>
                                                {{ $layout->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>{{ __('Post details page layout') }}</label>
                                    <select name="tpl_posts_post_layout_id" class="form-select">
                                        <option value="">- {{ __('Default (full width)') }} -</option>
                                        @foreach ($layouts as $layout)
                                            <option value="{{ $layout->id }}" @if (($config->tpl_posts_post_layout_id ?? null) == $layout->id) selected @endif>
                                                {{ $layout->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="card bg-light p-3 pb-0 mb-3">
                        <div class="fw-bold fs-5 mb-2">{{ __('Search') }}</div>

                        <div class="form-group col-12">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_posts_show_top_search_bar'>
                                <input class="form-check-input" type="checkbox" id="tpl_posts_show_top_search_bar" name="tpl_posts_show_top_search_bar" @if ($config->tpl_posts_show_top_search_bar ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_posts_show_top_search_bar">{{ __('Show search form below the navigation menu') }}</label>
                            </div>
                            <div class="text-muted small">{{ __('Search form will serch in posts articles.') }}</div>
                        </div>

                    </div>

                </div>

            </div>


            <button type="submit" class="btn btn-primary mt-3">{{ __('Update template') }}</button>

        </form>

    </div>
    <!-- end card-body -->

</div>
