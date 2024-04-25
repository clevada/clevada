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

                <div class="col-12 col-md-6 col-lg-7">

                    <div class="card bg-light p-3 mb-3">

                        <h5 class="fw-bold">{{ __('Main navbar settings') }}</h5>
                        <small class="mb-3">{{ __('This navbar contain navigation links') }}</small>

                        <div class="row">
                            <div class="col-12 col-xl-3 col-lg-6 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-check-label">{{ __('Spacing') }}</label>
                                    <select name="tpl_navbar_links_margin" class="form-select">
                                        <option value="">{{ __('Default spacing') }}</option>
                                        <option @if (($config->tpl_navbar_links_margin ?? null) == 'ms-2') selected @endif value="ms-2">{{ __('Add small spacing') }}</option>
                                        <option @if (($config->tpl_navbar_links_margin ?? null) == 'ms-3') selected @endif value="ms-3">{{ __('Add medium spacing') }}</option>
                                        <option @if (($config->tpl_navbar_links_margin ?? null) == 'ms-4') selected @endif value="ms-4">{{ __('Add large spacing') }}</option>
                                        <option @if (($config->tpl_navbar_links_margin ?? null) == 'ms-5') selected @endif value="ms-5">{{ __('Add extra large spacing') }}</option>
                                    </select>
                                    <div class="small text-muted">{{ __('Spacing between navigation links') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_navbar_shaddow'>
                                <input class="form-check-input" type="checkbox" id="tpl_navbar_shaddow" name="tpl_navbar_shaddow" @if ($config->tpl_navbar_shaddow ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_navbar_shaddow">{{ __('Add shaddow under main navigation') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_navbar_hide_logo'>
                                <input class="form-check-input" type="checkbox" id="tpl_navbar_hide_logo" name="tpl_navbar_hide_logo" @if ($config->tpl_navbar_hide_logo ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_navbar_hide_logo">{{ __('Hide logo') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_navbar_sticky'>
                                <input class="form-check-input" type="checkbox" id="tpl_navbar_sticky" name="tpl_navbar_sticky" @if ($config->tpl_navbar_sticky ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_navbar_sticky">{{ __('Sticky navigation') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_navbar_hide_auth'>
                                <input class="form-check-input" type="checkbox" id="tpl_navbar_hide_auth" name="tpl_navbar_hide_auth" @if ($config->tpl_navbar_hide_auth ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_navbar_hide_auth">{{ __('Hide authentification (login page)') }}</label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_navbar_hide_langs'>
                                <input class="form-check-input" type="checkbox" id="tpl_navbar_hide_langs" name="tpl_navbar_hide_langs" @if ($config->tpl_navbar_hide_langs ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_navbar_hide_langs">{{ __('Hide language selector (if multiple languages are available)') }}</label>
                            </div>
                        </div>

                        <hr>

                        <h5 class="fw-bold">{{ __('Notification top bar') }}</h5>
                        <small class="mb-3">{{ __('Add a top bar with text content. This can be useful if you have an important announcement that is visible at the top of the site.') }}</small>

                        <div class="form-group mb-3">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_notification_navbar_show'>
                                <input class="form-check-input" type="checkbox" id="tpl_notification_navbar_show" name="tpl_notification_navbar_show" @if ($config->tpl_notification_navbar_show ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_notification_navbar_show">{{ __('Show notiffication bar') }}</label>
                            </div>
                        </div>

                        <script>
                            $('#tpl_notification_navbar_show').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_notif').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_notif').style.display = 'none';
                            })
                        </script>

                        <div id="hidden_div_notif" style="display: @if ($config->tpl_notification_navbar_show ?? null) block @else none @endif">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{ __('Content') }}</label>
                                        <textarea class="form-control" name="tpl_notification_navbar_content" rows="3">{{ $config->tpl_notification_navbar_content ?? null }}</textarea>
                                        <div class="text-muted small">{{ __('HTML code is allowed.') }}</div>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4 col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Content align') }}</label>
                                        <select class="form-select" name="navbar2_content_align">
                                            <option @if (($config->tpl_notification_navbar_content_align ?? null) == 'text-start') selected @endif value="text-start">{{ __('Left') }}</option>
                                            <option @if (($config->tpl_notification_navbar_content_align ?? null) == 'text-center') selected @endif value="text-center">{{ __('Center') }}</option>
                                            <option @if (($config->tpl_notification_navbar_content_align ?? null) == 'text-end') selected @endif value="text-end">{{ __('Right') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4 col-lg-6 col-md-6">
                                    <label>{{ __('Select style') }} [<a href="{{ route('admin.template.styles') }}">{{ __('Manage styles') }}</a>]</label>
                                    <select name="tpl_notification_navbar_style_id" class="form-select">
                                        @foreach ($styles as $style)
                                            <option @if (($config->tpl_notification_navbar_style_id ?? null) == $style->id) selected @endif value="{{ $style->id }}">{{ $style->label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-muted small">{{ __('Style for notification bar (fonts, sizes, colors, background...)') }}</div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='tpl_notification_navbar_sticky'>
                                    <input class="form-check-input" type="checkbox" id="tpl_notification_navbar_sticky" name="tpl_notification_navbar_sticky" @if ($config->tpl_notification_navbar_sticky ?? null) checked @endif>
                                    <label class="form-check-label" for="tpl_notification_navbar_sticky">{{ __('Sticky bar') }}</label>
                                </div>
                                <div class="form-text">{{ __('Note: notification bar can be sticky only if main navbar navigation is not sticky') }}</div>
                            </div>

                        </div>


                        {{--
                        <hr>

                        <h5 class="fw-bold">{{ __('Show additional custom navigation (developers only)') }}</h5>
                        <small class="mb-3">{{ __('Custom navigation menu is above the main navigation bar. You can input custom HTML code.') }}</small>

                        <div class="form-group mb-3">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='tpl_custom_navbar_show'>
                                <input class="form-check-input" type="checkbox" id="tpl_custom_navbar_show" name="tpl_custom_navbar_show" @if ($config->tpl_custom_navbar_show ?? null) checked @endif>
                                <label class="form-check-label" for="tpl_custom_navbar_show">{{ __('Show notiffication bar') }}</label>
                            </div>
                        </div>

                        <script>
                            $('#tpl_custom_navbar_show').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_custom_nav').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_custom_nav').style.display = 'none';
                            })
                        </script>

                        <div id="hidden_div_custom_nav" style="display: @if ($config->tpl_custom_navbar_show ?? null) block @else none @endif">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{ __('Content') }}</label>
                                        <textarea class="form-control" name="tpl_custom_navbar_content" rows="2">{{ $config->tpl_custom_navbar_content ?? null }}</textarea>
                                        <div class="text-muted small">{{ __('HTML code is allowed.') }}</div>
                                    </div>
                                </div>                                

                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <label>{{ __('Select style') }} [<a href="{{ route('admin.template.styles') }}">{{ __('Manage styles') }}</a>]</label>
                                    <select name="tpl_custom_navbar_style_id" class="form-select">
                                        @foreach ($styles as $style)
                                            <option @if (($config->tpl_custom_navbar_style_id ?? null) == $style->id) selected @endif value="{{ $style->id }}">{{ $style->label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-muted small">{{ __('Style for custom navigation (fonts, sizes, colors, background...)') }}</div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='tpl_custom_navbar_sticky'>
                                    <input class="form-check-input" type="checkbox" id="tpl_custom_navbar_sticky" name="tpl_custom_navbar_sticky" @if ($config->tpl_custom_navbar_sticky ?? null) checked @endif>
                                    <label class="form-check-label" for="tpl_custom_navbar_sticky">{{ __('Sticky custom navigation') }}</label>
                                </div>
                                <div class="form-text">{{ __('Note: Custom navigation bar can be sticky only if main navbar navigation is not sticky') }}</div>
                            </div>

                        </div>
                        --}}

                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-5">

                    <div class="card bg-light p-3 pb-0 mb-3">

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input type="hidden" name="tpl_container_fluid" value="">
                                <input class="form-check-input" type="checkbox" id="fluidSwitch" name="tpl_container_fluid" @if ($config->tpl_container_fluid ?? null) checked @endif>
                                <label class="form-check-label" for="fluidSwitch">{{ __('Use fluid width (full width)') }}</label>
                            </div>
                            <div class="form-text">{{ __('Use fluid container for a full width container, spanning the entire width of the display.') }}</div>
                        </div>

                    </div>

                </div>

            </div>

            <button type="submit" class="btn btn-primary mt-3">{{ __('Update template') }}</button>
        </form>

    </div>
    <!-- end card-body -->

</div>
