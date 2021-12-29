@include('admin.includes.import-fonts')

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
                        <h5 class="fw-bold">{{ __('Users navigation menu') }}</h5>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3 col-xl-2">

                                <div class="form-group">
                                    <input id="users_nav_bg_color" name="users_nav_bg_color" value="{{ get_template_value($template->id, 'users_nav_bg_color') ?? config('defaults.nav_bg_color') }}">
                                    <label>{{ __('Navigation background color') }}</label>
                                    <script>
                                        $('#users_nav_bg_color').spectrum({
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
                                    <input id="users_nav_font_color" name="users_nav_font_color" value="{{ get_template_value($template->id, 'users_nav_font_color') ?? config('defaults.nav_font_color') }}">
                                    <label>{{ __('Navigation font color') }}</label>
                                    <script>
                                        $('#users_nav_font_color').spectrum({
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

                                    <input id="users_nav_bg_color_hover" name="users_nav_bg_color_hover" value="{{ get_template_value($template->id, 'users_nav_bg_color_hover') ?? config('defaults.nav_link_bg_hover') }}">
                                    <label>{{ __('Background color on hover') }}</label>
                                    <script>
                                        $('#users_nav_bg_color_hover').spectrum({
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

                                    <input id="users_nav_font_color_hover" name="users_nav_font_color_hover" value="{{ get_template_value($template->id, 'users_nav_font_color_hover') ?? config('defaults.nav_link_color_hover') }}">
                                    <label>{{ __('Font color on hover') }}</label>
                                    <script>
                                        $('#users_nav_font_color_hover').spectrum({
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
                                    <input id="users_nav_active_bg_color" name="users_nav_active_bg_color" value="{{ get_template_value($template->id, 'users_nav_active_bg_color') ?? config('defaults.nav_active_bg_color') }}">
                                    <label>{{ __('Active menu background color') }}</label>
                                    <script>
                                        $('#users_nav_active_bg_color').spectrum({
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

                                    <input id="users_nav_active_font_color" name="users_nav_active_font_color" value="{{ get_template_value($template->id, 'users_nav_active_font_color') ?? config('defaults.nav_active_font_color') }}">
                                    <label>{{ __('Active menu font color') }}</label>
                                    <script>
                                        $('#users_nav_active_font_color').spectrum({
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
