<form method="post">
    @csrf
    @method('PUT')
   

    <div class="row gx-5">

        <div class="col-md-6">

            <div class="fw-bold fs-5 mb-2">{{ __('Posts listing style (main page and categories)') }}</div>

            <div class="row">
                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <label>{{ __('Display article date') }}</label>
                    <select name="posts_listing_show_date" class="form-select">
                        <option @if (($templateConfig->posts_listing_show_date ?? null) == 'date') selected @endif value="date">{{ __('Date only') }}</option>
                        <option @if (($templateConfig->posts_listing_show_date ?? null) == 'datetime') selected @endif value="datetime">{{ __('Date and time') }}</option>
                        <option @if (($templateConfig->posts_listing_show_date ?? null) == '') selected @endif value="">{{ __('Do not show') }}</option>
                    </select>
                </div>

                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <label>{{ __('Display article author') }}</label>
                    <select name="posts_listing_show_author" class="form-select">
                        <option @if (($templateConfig->posts_listing_show_author ?? null) == 'name') selected @endif value="name">{{ __('Author name only') }}</option>
                        <option @if (($templateConfig->posts_listing_show_author ?? null) == 'name_avatar') selected @endif value="name_avatar">{{ __('Author name and avatar') }}</option>
                        <option @if (($templateConfig->posts_listing_show_author ?? null) == 'no') selected @endif value="no">{{ __('Do not show') }}</option>
                    </select>
                </div>

                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                    <label>{{ __('Article summary') }}</label>
                    <select name="posts_listing_summary" class="form-select">
                        <option @if (($templateConfig->posts_listing_summary ?? null) == '') selected @endif value="">{{ __('Do not show') }}</option>
                        <option @if (($templateConfig->posts_listing_summary ?? null) == 'text-clamp-2') selected @endif value="text-clamp-2">{{ __('Maximum 2 rows') }}</option>
                        <option @if (($templateConfig->posts_listing_summary ?? null) == 'text-clamp-3') selected @endif value="text-clamp-3">{{ __('Maximum 3 rows') }}</option>
                        <option @if (($templateConfig->posts_listing_summary ?? null) == 'text-clamp-4') selected @endif value="text-clamp-4">{{ __('Maximum 4 rows') }}</option>
                        <option @if (($templateConfig->posts_listing_summary ?? null) == 'text-clamp-5') selected @endif value="text-clamp-5">{{ __('Maximum 5 rows') }}</option>
                        <option @if (($templateConfig->posts_listing_summary ?? null) == 'text-clamp-6') selected @endif value="text-clamp-6">{{ __('Maximum 6 rows') }}</option>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='posts_listing_image_shadow'>
                    <input class="form-check-input" type="checkbox" id="posts_listing_image_shadow" name="posts_listing_image_shadow" @if ($templateConfig->posts_listing_image_shadow ?? null) checked @endif>
                    <label class="form-check-label" for="posts_listing_image_shadow">{{ __('Add shaddow for main image') }}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='posts_listing_image_rounded'>
                    <input class="form-check-input" type="checkbox" id="posts_listing_image_rounded" name="posts_listing_image_rounded" @if ($templateConfig->posts_listing_image_rounded ?? null) checked @endif>
                    <label class="form-check-label" for="posts_listing_image_rounded">{{ __('Rounded border for main image') }}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='posts_listing_image_zoom'>
                    <input class="form-check-input" type="checkbox" id="posts_listing_image_zoom" name="posts_listing_image_zoom" @if ($templateConfig->posts_listing_image_zoom ?? null) checked @endif>
                    <label class="form-check-label" for="posts_listing_image_zoom">{{ __('Add zoom effect on mouse over image') }}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='posts_listing_show_time_read'>
                    <input class="form-check-input" type="checkbox" id="posts_listing_show_time_read" name="posts_listing_show_time_read" @if ($templateConfig->posts_listing_show_time_read ?? null) checked @endif>
                    <label class="form-check-label" for="posts_listing_show_time_read">{{ __('Show time to read') }}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='posts_listing_show_likes_count'>
                    <input class="form-check-input" type="checkbox" id="posts_listing_show_likes_count" name="posts_listing_show_likes_count" @if ($templateConfig->posts_listing_show_likes_count ?? null) checked @endif>
                    <label class="form-check-label" for="posts_listing_show_likes_count">{{ __('Show likes counter') }}</label>
                </div>
            </div>

            <div class="form-group mb-0">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='posts_listing_show_views_count'>
                    <input class="form-check-input" type="checkbox" id="posts_listing_show_views_count" name="posts_listing_show_views_count" @if ($templateConfig->posts_listing_show_views_count ?? null) checked @endif>
                    <label class="form-check-label" for="posts_listing_show_views_count">{{ __('Show views counter') }}</label>
                </div>
            </div>

        </div>


        <div class="col-md-6">

            <div class="fw-bold fs-5 mb-2">{{ __('Post details page') }}</div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='post_hide_image'>
                    <input class="form-check-input" type="checkbox" id="post_hide_image" name="post_hide_image" @if ($templateConfig->post_hide_image ?? null) checked @endif>
                    <label class="form-check-label" for="post_hide_image">{{ __('Hide main image') }}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='post_image_shadow'>
                    <input class="form-check-input" type="checkbox" id="post_image_shadow" name="post_image_shadow" @if ($templateConfig->post_image_shadow ?? null) checked @endif>
                    <label class="form-check-label" for="post_image_shadow">{{ __('Add shadow for main image') }}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='post_head_align_center'>
                    <input class="form-check-input" type="checkbox" id="post_head_align_center" name="post_head_align_center" @if ($templateConfig->post_head_align_center ?? null) checked @endif>
                    <label class="form-check-label" for="post_head_align_center">{{ __('Align center article header (title, meta and main image)') }}</label>
                </div>
            </div>

            <div class="form-group mb-0">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='post_hide_breadcrumb'>
                    <input class="form-check-input" type="checkbox" id="post_hide_breadcrumb" name="post_hide_breadcrumb" @if ($templateConfig->post_hide_breadcrumb ?? null) checked @endif>
                    <label class="form-check-label" for="post_hide_breadcrumb">{{ __('Hide breadcrumb navigation') }}</label>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='post_image_force_full_width'>
                    <input class="form-check-input" type="checkbox" id="post_image_force_full_width" name="post_image_force_full_width" @if ($templateConfig->post_image_force_full_width ?? null) checked @endif>
                    <label class="form-check-label" for="post_image_force_full_width">{{ __('Force main image full width') }}</label>
                    <div class="text-muted small">{{ __('If image is smaller, force image to be full width') }}</div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type='hidden' value='' name='post_image_rounded'>
                    <input class="form-check-input" type="checkbox" id="post_image_rounded" name="post_image_rounded" @if ($templateConfig->post_image_rounded ?? null) checked @endif>
                    <label class="form-check-label" for="post_image_rounded">{{ __('Rounded border for main image') }}</label>
                </div>
            </div>

            <div class="form-group col-md-6 col-12">
                <label>{{ __('Main image height') }}</label>
                <select name="post_image_height_class" class="form-select">
                    <option @if (($templateConfig->post_image_height_class ?? null) == '') selected @endif value="">{{ __('Original') }}</option>
                    <option @if (($templateConfig->post_image_height_class ?? null) == 'post-main-img-tight') selected @endif value="post-main-img-tight">{{ __('Tight') }}</option>
                    <option @if (($templateConfig->post_image_height_class ?? null) == 'post-main-img-tighter') selected @endif value="post-main-img-tighter">{{ __('Tighter') }}</option>
                    <option @if (($templateConfig->post_image_height_class ?? null) == 'post-main-img-slim') selected @endif value="post-main-img-slim">{{ __('Slim') }}</option>
                </select>
            </div>
        </div>

    </div>


    <hr>

    <button type="submit" class="btn btn-primary mt-2">{{ __('Update') }}</button>
</form>
