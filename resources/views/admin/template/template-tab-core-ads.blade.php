<div class="fw-bold mb-3 fs-5">{{ __('Homepage ads') }}</div>

<div class="form-group mb-3 mt-2">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='ads_homepage_top'>
        <input class="form-check-input" type="checkbox" id="ads_homepage_top" name="ads_homepage_top" @if ($templateConfig->ads_homepage_top ?? null) checked @endif>
        <label class="form-check-label" for="ads_homepage_top">{{ __('Show ads on top section') }}</label>
    </div>
    <div class="form-text">{{ __('Top section is below the navigation menu. Note: if hero section is enabled, ads are displayed below the hero.') }}</div>
</div>

<script>
    $('#ads_homepage_top').change(function() {
        select = $(this).prop('checked');
        if (select)
            document.getElementById('hidden_div_ads_home_top').style.display = 'block';
        else
            document.getElementById('hidden_div_ads_home_top').style.display = 'none';
    })
</script>

<div id="hidden_div_ads_home_top" style="display: @if ($templateConfig->ads_homepage_top ?? null) block @else none @endif">

    <div class="form-group col-md-4 col-lg-3 col-xl-2">
        <label>{{ __('Select ad') }} [<a target="_blank" href="{{ route('admin.ads.index') }}"><b>{{ __('Manage ads') }}</b></a>]</label>
        @if (count($ads) == 0)
            <div class="text-danger">{{ __("You don't have any ad. Go to manage ads to create a new ad") }}</div>
        @endif
        <select class="form-select" name="ads_homepage_top_ad_id">
            <option value="">-- {{ __('select') }} --</option>
            @foreach ($ads as $ad)
                <option @if (($templateConfig->ads_homepage_top_ad_id ?? null) == $ad->id) selected @endif value="{{ $ad->id }}">{{ $ad->label }} @if ($ad->type == 'image')
                        ({{ __('image') }})
                        @endif @if ($ad->type == 'code')
                            ({{ __('code') }})
                        @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-0 mt-3">
        <div class="form-check form-switch">
            <input type='hidden' value='' name='ads_homepage_top_use_custom_bg'>
            <input class="form-check-input" type="checkbox" id="ads_homepage_top_use_custom_bg" name="ads_homepage_top_use_custom_bg" @if ($templateConfig->ads_homepage_top_use_custom_bg ?? null) checked @endif>
            <label class="form-check-label" for="ads_homepage_top_use_custom_bg">{{ __('Use custom background color for this section') }}</label>
        </div>
    </div>

    <script>
        $('#ads_homepage_top_use_custom_bg').change(function() {
            select = $(this).prop('checked');
            if (select)
                document.getElementById('hidden_div_ads_homepage_top_color').style.display = 'block';
            else
                document.getElementById('hidden_div_ads_homepage_top_color').style.display = 'none';
        })
    </script>

    <div id="hidden_div_ads_homepage_top_color" style="display: @if ($templateConfig->ads_homepage_top_use_custom_bg ?? null) block @else none @endif" class="mt-2">
        <div class="form-group">
            <input class="form-control form-control-color" id="ads_homepage_top_custom_bg" name="ads_homepage_top_custom_bg" value="{{ $templateConfig->ads_homepage_top_custom_bg ?? '#ffffff' }}">
            <label>{{ __('Background color') }}</label>
            <script>
                $('#ads_homepage_top_custom_bg').spectrum({
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



<div class="form-group mb-3 mt-2">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='ads_homepage_bottom'>
        <input class="form-check-input" type="checkbox" id="ads_homepage_bottom" name="ads_homepage_bottom" @if ($templateConfig->ads_homepage_bottom ?? null) checked @endif>
        <label class="form-check-label" for="ads_homepage_bottom">{{ __('Show ads on bottom section') }}</label>
    </div>
    <div class="form-text">{{ __('Bottom section is above the footer.') }}</div>
</div>

<script>
    $('#ads_homepage_bottom').change(function() {
        select = $(this).prop('checked');
        if (select)
            document.getElementById('hidden_div_ads_home_bottom').style.display = 'block';
        else
            document.getElementById('hidden_div_ads_home_bottom').style.display = 'none';
    })
</script>

<div id="hidden_div_ads_home_bottom" style="display: @if ($templateConfig->ads_homepage_bottom ?? null) block @else none @endif">

    <div class="form-group col-md-4 col-lg-3 col-xl-2">
        <label>{{ __('Select ad') }} [<a target="_blank" href="{{ route('admin.ads.index') }}"><b>{{ __('Manage ads') }}</b></a>]</label>
        @if (count($ads) == 0)
            <div class="text-danger">{{ __("You don't have any ad. Go to manage ads to create a new ad") }}</div>
        @endif
        <select class="form-select" name="ads_homepage_bottom_ad_id">
            <option value="">-- {{ __('select') }} --</option>
            @foreach ($ads as $ad)
                <option @if (($templateConfig->ads_homepage_bottom_ad_id ?? null) == $ad->id) selected @endif value="{{ $ad->id }}">{{ $ad->label }} @if ($ad->type == 'image')
                        ({{ __('image') }})
                        @endif @if ($ad->type == 'code')
                            ({{ __('code') }})
                        @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-0 mt-3">
        <div class="form-check form-switch">
            <input type='hidden' value='' name='ads_homepage_bottom_use_custom_bg'>
            <input class="form-check-input" type="checkbox" id="ads_homepage_bottom_use_custom_bg" name="ads_homepage_bottom_use_custom_bg" @if ($templateConfig->ads_homepage_bottom_use_custom_bg ?? null) checked @endif>
            <label class="form-check-label" for="ads_homepage_bottom_use_custom_bg">{{ __('Use custom background color for this section') }}</label>
        </div>
    </div>

    <script>
        $('#ads_homepage_bottom_use_custom_bg').change(function() {
            select = $(this).prop('checked');
            if (select)
                document.getElementById('hidden_div_ads_homepage_bottom_color').style.display = 'block';
            else
                document.getElementById('hidden_div_ads_homepage_bottom_color').style.display = 'none';
        })
    </script>

    <div id="hidden_div_ads_homepage_bottom_color" style="display: @if ($templateConfig->ads_homepage_bottom_use_custom_bg ?? null) block @else none @endif" class="mt-2">
        <div class="form-group">
            <input class="form-control form-control-color" id="ads_homepage_bottom_custom_bg" name="ads_homepage_bottom_custom_bg" value="{{ $templateConfig->ads_homepage_bottom_custom_bg ?? '#ffffff' }}">
            <label>{{ __('Background color') }}</label>
            <script>
                $('#ads_homepage_bottom_custom_bg').spectrum({
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



<div class="fw-bold mb-3 fs-5">{{ __('Posts ads (posts categories, posts tags and search results)') }}</div>

<div class="form-group mb-3 mt-2">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='ads_posts_top'>
        <input class="form-check-input" type="checkbox" id="ads_posts_top" name="ads_posts_top" @if ($templateConfig->ads_posts_top ?? null) checked @endif>
        <label class="form-check-label" for="ads_posts_top">{{ __('Show ads on top section') }}</label>
    </div>
    <div class="form-text">{{ __('Top section is below the navigation menu. Note: if category hero section is enabled, ads are displayed below the hero.') }}</div>
</div>

<script>
    $('#ads_posts_top').change(function() {
        select = $(this).prop('checked');
        if (select)
            document.getElementById('hidden_div_ads_posts_top').style.display = 'block';
        else
            document.getElementById('hidden_div_ads_posts_top').style.display = 'none';
    })
</script>

<div id="hidden_div_ads_posts_top" style="display: @if ($templateConfig->ads_posts_top ?? null) block @else none @endif">

    <div class="form-group col-md-4 col-lg-3 col-xl-2">
        <label>{{ __('Select ad') }} [<a target="_blank" href="{{ route('admin.ads.index') }}"><b>{{ __('Manage ads') }}</b></a>]</label>
        @if (count($ads) == 0)
            <div class="text-danger">{{ __("You don't have any ad. Go to manage ads to create a new ad") }}</div>
        @endif
        <select class="form-select" name="ads_posts_top_ad_id">
            <option value="">-- {{ __('select') }} --</option>
            @foreach ($ads as $ad)
                <option @if (($templateConfig->ads_posts_top_ad_id ?? null) == $ad->id) selected @endif value="{{ $ad->id }}">{{ $ad->label }} @if ($ad->type == 'image')
                        ({{ __('image') }})
                        @endif @if ($ad->type == 'code')
                            ({{ __('code') }})
                        @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-0 mt-3">
        <div class="form-check form-switch">
            <input type='hidden' value='' name='ads_posts_top_use_custom_bg'>
            <input class="form-check-input" type="checkbox" id="ads_posts_top_use_custom_bg" name="ads_posts_top_use_custom_bg" @if ($templateConfig->ads_posts_top_use_custom_bg ?? null) checked @endif>
            <label class="form-check-label" for="ads_posts_top_use_custom_bg">{{ __('Use custom background color for this section') }}</label>
        </div>
    </div>

    <script>
        $('#ads_posts_top_use_custom_bg').change(function() {
            select = $(this).prop('checked');
            if (select)
                document.getElementById('hidden_div_ads_posts_top_color').style.display = 'block';
            else
                document.getElementById('hidden_div_ads_posts_top_color').style.display = 'none';
        })
    </script>

    <div id="hidden_div_ads_posts_top_color" style="display: @if ($templateConfig->ads_posts_top_use_custom_bg ?? null) block @else none @endif" class="mt-2">
        <div class="form-group">
            <input class="form-control form-control-color" id="ads_posts_top_custom_bg" name="ads_posts_top_custom_bg" value="{{ $templateConfig->ads_posts_top_custom_bg ?? '#ffffff' }}">
            <label>{{ __('Background color') }}</label>
            <script>
                $('#ads_posts_top_custom_bg').spectrum({
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


<div class="form-group mb-3 mt-2">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='ads_posts_bottom'>
        <input class="form-check-input" type="checkbox" id="ads_posts_bottom" name="ads_posts_bottom" @if ($templateConfig->ads_posts_bottom ?? null) checked @endif>
        <label class="form-check-label" for="ads_posts_bottom">{{ __('Show ads on bottom section') }}</label>
    </div>
    <div class="form-text">{{ __('Bottom section is above the footer.') }}</div>
</div>

<script>
    $('#ads_posts_bottom').change(function() {
        select = $(this).prop('checked');
        if (select)
            document.getElementById('hidden_div_ads_posts_bottom').style.display = 'block';
        else
            document.getElementById('hidden_div_ads_posts_bottom').style.display = 'none';
    })
</script>

<div id="hidden_div_ads_posts_bottom" style="display: @if ($templateConfig->ads_posts_bottom ?? null) block @else none @endif">

    <div class="form-group col-md-4 col-lg-3 col-xl-2">
        <label>{{ __('Select ad') }} [<a target="_blank" href="{{ route('admin.ads.index') }}"><b>{{ __('Manage ads') }}</b></a>]</label>
        @if (count($ads) == 0)
            <div class="text-danger">{{ __("You don't have any ad. Go to manage ads to create a new ad") }}</div>
        @endif
        <select class="form-select" name="ads_posts_bottom_ad_id">
            <option value="">-- {{ __('select') }} --</option>
            @foreach ($ads as $ad)
                <option @if (($templateConfig->ads_posts_bottom_ad_id ?? null) == $ad->id) selected @endif value="{{ $ad->id }}">{{ $ad->label }} @if ($ad->type == 'image')
                        ({{ __('image') }})
                        @endif @if ($ad->type == 'code')
                            ({{ __('code') }})
                        @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-0 mt-3">
        <div class="form-check form-switch">
            <input type='hidden' value='' name='ads_posts_bottom_use_custom_bg'>
            <input class="form-check-input" type="checkbox" id="ads_posts_bottom_use_custom_bg" name="ads_posts_bottom_use_custom_bg" @if ($templateConfig->ads_posts_bottom_use_custom_bg ?? null) checked @endif>
            <label class="form-check-label" for="ads_posts_bottom_use_custom_bg">{{ __('Use custom background color for this section') }}</label>
        </div>
    </div>

    <script>
        $('#ads_posts_bottom_use_custom_bg').change(function() {
            select = $(this).prop('checked');
            if (select)
                document.getElementById('hidden_div_ads_posts_bottom_color').style.display = 'block';
            else
                document.getElementById('hidden_div_ads_posts_bottom_color').style.display = 'none';
        })
    </script>

    <div id="hidden_div_ads_posts_bottom_color" style="display: @if ($templateConfig->ads_posts_bottom_use_custom_bg ?? null) block @else none @endif" class="mt-2">
        <div class="form-group">
            <input class="form-control form-control-color" id="ads_posts_bottom_custom_bg" name="ads_posts_bottom_custom_bg" value="{{ $templateConfig->ads_posts_bottom_custom_bg ?? '#ffffff' }}">
            <label>{{ __('Background color') }}</label>
            <script>
                $('#ads_posts_bottom_custom_bg').spectrum({
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



<div class="fw-bold mb-3 fs-5">{{ __('Post content page ads') }}</div>

<div class="text-muted mb-3">
    <i class="bi bi-info-circle"></i> {{ __('Note: you can add ads inside posts or pages content by using ads blocks when adding block contents.') }}
</div>

<div class="form-group mb-3 mt-2">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='ads_post_top'>
        <input class="form-check-input" type="checkbox" id="ads_post_top" name="ads_post_top" @if ($templateConfig->ads_post_top ?? null) checked @endif>
        <label class="form-check-label" for="ads_post_top">{{ __('Show ads on top section') }}</label>
    </div>
    <div class="form-text">{{ __('Top section is below the navigation menu.') }}</div>
</div>

<script>
    $('#ads_post_top').change(function() {
        select = $(this).prop('checked');
        if (select)
            document.getElementById('hidden_div_ads_post_top').style.display = 'block';
        else
            document.getElementById('hidden_div_ads_post_top').style.display = 'none';
    })
</script>

<div id="hidden_div_ads_post_top" style="display: @if ($templateConfig->ads_post_top ?? null) block @else none @endif">

    <div class="form-group col-md-4 col-lg-3 col-xl-2">
        <label>{{ __('Select ad') }} [<a target="_blank" href="{{ route('admin.ads.index') }}"><b>{{ __('Manage ads') }}</b></a>]</label>
        @if (count($ads) == 0)
            <div class="text-danger">{{ __("You don't have any ad. Go to manage ads to create a new ad") }}</div>
        @endif
        <select class="form-select" name="ads_post_top_ad_id">
            <option value="">-- {{ __('select') }} --</option>
            @foreach ($ads as $ad)
                <option @if (($templateConfig->ads_post_top_ad_id ?? null) == $ad->id) selected @endif value="{{ $ad->id }}">{{ $ad->label }} @if ($ad->type == 'image')
                        ({{ __('image') }})
                        @endif @if ($ad->type == 'code')
                            ({{ __('code') }})
                        @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-0 mt-3">
        <div class="form-check form-switch">
            <input type='hidden' value='' name='ads_post_top_use_custom_bg'>
            <input class="form-check-input" type="checkbox" id="ads_post_top_use_custom_bg" name="ads_post_top_use_custom_bg" @if ($templateConfig->ads_post_top_use_custom_bg ?? null) checked @endif>
            <label class="form-check-label" for="ads_post_top_use_custom_bg">{{ __('Use custom background color for this section') }}</label>
        </div>
    </div>

    <script>
        $('#ads_post_top_use_custom_bg').change(function() {
            select = $(this).prop('checked');
            if (select)
                document.getElementById('hidden_div_ads_post_top_color').style.display = 'block';
            else
                document.getElementById('hidden_div_ads_post_top_color').style.display = 'none';
        })
    </script>

    <div id="hidden_div_ads_post_top_color" style="display: @if ($templateConfig->ads_post_top_use_custom_bg ?? null) block @else none @endif" class="mt-2">
        <div class="form-group">
            <input class="form-control form-control-color" id="ads_post_top_custom_bg" name="ads_post_top_custom_bg" value="{{ $templateConfig->ads_post_top_custom_bg ?? '#ffffff' }}">
            <label>{{ __('Background color') }}</label>
            <script>
                $('#ads_post_top_custom_bg').spectrum({
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


<div class="form-group mb-3 mt-2">
    <div class="form-check form-switch">
        <input type='hidden' value='' name='ads_post_below_content'>
        <input class="form-check-input" type="checkbox" id="ads_post_below_content" name="ads_post_below_content" @if ($templateConfig->ads_post_below_content ?? null) checked @endif>
        <label class="form-check-label" for="ads_post_below_content">{{ __('Show ads below the post content') }}</label>
    </div>
</div>

<script>
    $('#ads_post_below_content').change(function() {
        select = $(this).prop('checked');
        if (select)
            document.getElementById('hidden_div_ads_post_below_content').style.display = 'block';
        else
            document.getElementById('hidden_div_ads_post_below_content').style.display = 'none';
    })
</script>

<div id="hidden_div_ads_post_below_content" style="display: @if ($templateConfig->ads_post_below_content ?? null) block @else none @endif">

    <div class="form-group col-md-4 col-lg-3 col-xl-2">
        <label>{{ __('Select ad') }} [<a target="_blank" href="{{ route('admin.ads.index') }}"><b>{{ __('Manage ads') }}</b></a>]</label>
        @if (count($ads) == 0)
            <div class="text-danger">{{ __("You don't have any ad. Go to manage ads to create a new ad") }}</div>
        @endif
        <select class="form-select" name="ads_post_below_content_ad_id">
            <option value="">-- {{ __('select') }} --</option>
            @foreach ($ads as $ad)
                <option @if (($templateConfig->ads_post_below_content_ad_id ?? null) == $ad->id) selected @endif value="{{ $ad->id }}">{{ $ad->label }} @if ($ad->type == 'image')
                        ({{ __('image') }})
                        @endif @if ($ad->type == 'code')
                            ({{ __('code') }})
                        @endif
                </option>
            @endforeach
        </select>
    </div>

</div>
