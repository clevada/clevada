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

                    <div class="col-12">

                        <div class="card bg-light p-3 mb-3">
                            <h5>{{ __('Primary footer') }}</h5>

                            <div class="row">

                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Footer layout') }}</label><br>
                                        <select class="form-select" name="footer_columns">
                                            <option @if (get_template_value($template->id, 'footer_columns') == '1') selected @endif value="1">{{ __('One column') }}</option>
                                            <option @if (get_template_value($template->id, 'footer_columns') == '2') selected @endif value="2">{{ __('Two columns') }}</option>
                                            <option @if (get_template_value($template->id, 'footer_columns') == '3') selected @endif value="3">{{ __('Three columns') }}</option>
                                            <option @if (get_template_value($template->id, 'footer_columns') == '4') selected @endif value="4">{{ __('Four columns') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-2 col-lg-3 col-md-12">
                                    <div class="form-group">
                                        @php
                                            $footer_font_size = get_template_value($template->id, 'footer_font_size') ?? config('defaults.font_size');
                                        @endphp
                                        <label>{{ __('Font size') }}</label>
                                        <select class="form-select" name="footer_font_size">
                                            @foreach (template_font_sizes() as $size)
                                                <option @if ($footer_font_size == $size->value) selected @endif value="{{ $size->value }}">{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration') }}</label>
                                        <select class="form-select" name="footer_link_decoration">
                                            <option @if (get_template_value($template->id, 'footer_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'footer_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{ __('Links decoration on hover') }}</label>
                                        <select class="form-select" name="footer_link_hover_decoration">
                                            <option @if (get_template_value($template->id, 'footer_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                            <option @if (get_template_value($template->id, 'footer_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class='mt-3'>

                                <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Background color') }}</label><br>
                                        <input class="form-control form-control-color" name="footer_bg_color" id="footer_bg_color"
                                            value="{{ get_template_value($template->id, 'footer_bg_color') ?? '#444444' }}">
                                        <script>
                                            $('#footer_bg_color').spectrum({
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
                                        <label>{{ __('Text color') }}</label><br>
                                        <input class="form-control form-control-color" name="footer_font_color" id="footer_font_color"
                                            value="{{ get_template_value($template->id, 'footer_font_color') ?? '#f3f6f4' }}">
                                        <script>
                                            $('#footer_font_color').spectrum({
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
                                        <label>{{ __('Links color') }}</label><br>
                                        <input id="footer_link_color" name="footer_link_color" value="{{ get_template_value($template->id, 'footer_link_color') ?? config('defaults.nav_link_color') }}">
                                        <script>
                                            $('#footer_link_color').spectrum({
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
                                        <input id="footer_link_color_hover" name="footer_link_color_hover"
                                            value="{{ get_template_value($template->id, 'footer_link_color_hover') ?? config('defaults.nav_link_color_hover') }}">
                                        <script>
                                            $('#footer_link_color_hover').spectrum({
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
                                        <input id="footer_link_color_underline" name="footer_link_color_underline"
                                            value="{{ get_template_value($template->id, 'footer_link_color_underline') ?? config('defaults.nav_link_color_hover') }}">
                                        <script>
                                            $('#footer_link_color_underline').spectrum({
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
                            <h5>{{ __('Secondary footer') }}</h5>
                            <small class="mb-3">{{ __('This footer is below main footer') }}</small>

                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='footer2_show'>
                                    <input class="form-check-input" type="checkbox" id="footer2_show" name="footer2_show" @if (get_template_value($template->id, 'footer2_show')) checked @endif>
                                    <label class="form-check-label" for="footer2_show">{{ __('Show secondary footer') }}</label>
                                </div>
                            </div>

                            <script>
                                $('#footer2_show').change(function() {
                                    select = $(this).prop('checked');
                                    if (select)
                                        document.getElementById('hidden_div_footer2').style.display = 'block';
                                    else
                                        document.getElementById('hidden_div_footer2').style.display = 'none';
                                })
                            </script>

                            <div id="hidden_div_footer2" style="display: @if (get_template_value($template->id, 'footer2_show')) block @else none @endif">
                                <div class="row">

                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Footer layout') }}</label><br>
                                            <select class="form-select" name="footer2_columns">
                                                <option @if (get_template_value($template->id, 'footer2_columns') == '1') selected @endif value="1">{{ __('One column') }}</option>
                                                <option @if (get_template_value($template->id, 'footer2_columns') == '2') selected @endif value="2">{{ __('Two columns') }}</option>
                                                <option @if (get_template_value($template->id, 'footer2_columns') == '3') selected @endif value="3">{{ __('Three columns') }}</option>
                                                <option @if (get_template_value($template->id, 'footer2_columns') == '4') selected @endif value="4">{{ __('Four columns') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-xl-2 col-lg-3 col-md-12">
                                        <div class="form-group">
                                            @php
                                                $footer2_font_size = get_template_value($template->id, 'footer2_font_size') ?? config('defaults.font_size');
                                            @endphp
                                            <label>{{ __('Font size') }}</label>
                                            <select class="form-select" name="footer2_font_size">
                                                @foreach (template_font_sizes() as $size)
                                                    <option @if ($footer2_font_size == $size->value) selected @endif value="{{ $size->value }}">{{ $size->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="form-group">
                                            <label>{{ __('Links decoration') }}</label>
                                            <select class="form-select" name="footer2_link_decoration">
                                                <option @if (get_template_value($template->id, 'footer2_link_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                                <option @if (get_template_value($template->id, 'footer2_link_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                                        <div class="form-group">
                                            <label>{{ __('Links decoration on hover') }}</label>
                                            <select class="form-select" name="footer2_link_hover_decoration">
                                                <option @if (get_template_value($template->id, 'footer2_link_hover_decoration') == 'none') selected @endif value="none">{{ __('None') }}</option>
                                                <option @if (get_template_value($template->id, 'footer2_link_hover_decoration') == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr class='mt-3'>

                                    <div class="col-12 col-xl-2 col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Background color') }}</label><br>
                                            <input class="form-control form-control-color" name="footer2_bg_color" id="footer2_bg_color"
                                                value="{{ get_template_value($template->id, 'footer2_bg_color') ?? '#444444' }}">
                                            <script>
                                                $('#footer2_bg_color').spectrum({
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
                                            <label>{{ __('Text color') }}</label><br>
                                            <input class="form-control form-control-color" name="footer2_font_color" id="footer2_font_color"
                                                value="{{ get_template_value($template->id, 'footer2_font_color') ?? '#f3f6f4' }}">
                                            <script>
                                                $('#footer2_font_color').spectrum({
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
                                            <label>{{ __('Links color') }}</label><br>
                                            <input id="footer2_link_color" name="footer2_link_color"
                                                value="{{ get_template_value($template->id, 'footer2_link_color') ?? config('defaults.nav_link_color') }}">
                                            <script>
                                                $('#footer2_link_color').spectrum({
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
                                            <input id="footer2_link_color_hover" name="footer2_link_color_hover"
                                                value="{{ get_template_value($template->id, 'footer2_link_color_hover') ?? config('defaults.nav_link_color_hover') }}">
                                            <script>
                                                $('#footer2_link_color_hover').spectrum({
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
                                            <input id="footer2_link_color_underline" name="footer2_link_color_underline"
                                                value="{{ get_template_value($template->id, 'footer2_link_color_underline') ?? config('defaults.nav_link_color_hover') }}">
                                            <script>
                                                $('#footer2_link_color_underline').spectrum({
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

                    </div>

                </div>

                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button type="submit" class="btn btn-primary mt-2">{{ __('Update') }}</button>

            </form>

            <hr>

            <div class="text-muted mt-4">{{ __('After you set footer layout, you can manage content') }}</div>
            <a class="btn btn-secondary mt-3" href="{{ route('admin.template.footer.content', ['template_id' => $template->id, 'footer' => 'primary']) }}">{{ __('Manage main footer content') }}</a>
            @if (get_template_value($template->id, 'footer2_show'))<a class="btn btn-secondary mt-3 ms-3" href="{{ route('admin.template.footer.content', ['template_id' => $template->id, 'footer' => 'secondary']) }}">{{ __('Manage secondary footer content') }}</a>@endif



        </div>
        <!-- end card-body -->

    </div>

</section>
