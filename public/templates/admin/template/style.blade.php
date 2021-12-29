<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.template.styles') }}">{{ __('Styles') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $style->label }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 mb-3">
                    @include('admin.template.layouts.menu-template')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Update style') }}: {{ $style->label }}</h4>
                </div>

            </div>

        </div>

        <div class="card-body">

            <form method="post">
                @csrf
                @method('PUT')

                <div class="form-group mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="use_custom_bg" name="use_custom_bg" @if ($style->bg_color ?? null) checked @endif>
                        <label class="form-check-label" for="use_custom_bg">{{ __('Custom background color') }}</label>
                    </div>
                    <div class="form-text">{{ __('If disabled, default website background color is used.') }}</div>
                </div>

                <script>
                    $('#use_custom_bg').change(function() {
                        select = $(this).prop('checked');
                        if (select)
                            document.getElementById('hidden_div_bg_color_select').style.display = 'block';
                        else
                            document.getElementById('hidden_div_bg_color_select').style.display = 'none';
                    })
                </script>

                <div id="hidden_div_bg_color_select" style="display: @if (isset($style->bg_color)) block @else none @endif" class="mb-2">
                    <div class="form-group">
                        <input class="form-control form-control-color" id="bg_color" name="bg_color" value="@if (isset($style->bg_color)) {{ $style->bg_color }} @else #fbf7f0 @endif">
                        <label>{{ __('Background color') }}</label>
                        <script>
                            $('#bg_color').spectrum({
                                type: "color",
                                hideAfterPaletteSelect: true,
                                showInput: false,
                                showInitial: true,
                                showAlpha: false,
                                showButtons: true,
                                allowEmpty: false,
                            });
                        </script>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 form-group mt-2">
                        <label class="form-label">{{ __('Padding top') }}</label>
                        <select name="padding_top" class="form-select">
                            <option @if (($style->padding_top ?? null) == 'pt-0') selected @endif value="pt-0">{{ __('No space') }}</option>
                            <option @if (($style->padding_top ?? null) == 'pt-1') selected @endif value="pt-1">10px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-2') selected @endif value="pt-2">20px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-3') selected @endif value="pt-3">30px</option>
                            <option @if (!isset($style->padding_top) || ($style->padding_top ?? null) == 'pt-4') selected @endif value="pt-4">40px ({{ __('default') }})</option>
                            <option @if (($style->padding_top ?? null) == 'pt-5') selected @endif value="pt-5">50px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-10') selected @endif value="pt-10">100px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-15') selected @endif value="pt-15">150px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-20') selected @endif value="pt-20">200px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-30') selected @endif value="pt-30">300px</option>
                        </select>
                        <div class="form-text">{{ __('Section top space') }}</div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 form-group mt-2">
                        <label class="form-label">{{ __('Padding bottom') }}</label>
                        <select name="padding_bottom" class="form-select">
                            <option @if (($style->padding_top ?? null) == 'pt-0') selected @endif value="pt-0">{{ __('No space') }}</option>
                            <option @if (($style->padding_top ?? null) == 'pt-1') selected @endif value="pt-1">10px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-2') selected @endif value="pt-2">20px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-3') selected @endif value="pt-3">30px</option>
                            <option @if (!isset($style->padding_top) || ($style->padding_top ?? null) == 'pt-4') selected @endif value="pt-4">40px ({{ __('default') }})</option>
                            <option @if (($style->padding_top ?? null) == 'pt-5') selected @endif value="pt-5">50px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-10') selected @endif value="pt-10">100px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-15') selected @endif value="pt-15">150px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-20') selected @endif value="pt-20">200px</option>
                            <option @if (($style->padding_top ?? null) == 'pt-30') selected @endif value="pt-30">300px</option>
                        </select>
                        <div class="form-text">{{ __('Section bottom space') }}</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 form-group mt-2">
                        @php
                            $title_font_size = $style->title_font_size ?? config('defaults.title_size');
                        @endphp

                        <label>{{ __('Title font size') }}</label>
                        <select class="form-select" name="title_size">
                            @foreach (template_font_sizes() as $selected_font_size_title)
                                <option @if ($title_font_size == $selected_font_size_title->value) selected @endif value="{{ $selected_font_size_title->value }}">{{ $selected_font_size_title->name }}</option>
                            @endforeach
                        </select>
                    </div>                 

                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 form-group mt-2">
                        @php
                            $text_font_size = $style->text_font_size ?? config('defaults.font_size');
                        @endphp

                        <label>{{ __('Text font size') }}</label>
                        <select class="form-select" name="text_size">
                            @foreach (template_font_sizes() as $selected_font_size_title)
                                <option @if ($text_font_size == $selected_font_size_title->value) selected @endif value="{{ $selected_font_size_title->value }}">{{ $selected_font_size_title->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 form-group mt-2">
                        @php
                            $helper_font_size = $style->title_font_size ?? config('defaults.font_size');
                        @endphp

                        <label>{{ __('Helper font size') }}</label>
                        <select class="form-select" name="helper_size">
                            @foreach (template_font_sizes() as $selected_font_size_subtitle)
                                <option @if ($helper_font_size == $selected_font_size_subtitle->value) selected @endif value="{{ $selected_font_size_subtitle->value }}">{{ $selected_font_size_subtitle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12 form-group mt-2">
                        <input class="form-control form-control-color" id="title_color" name="title_color" value="{{ $style->title_color ?? config('defaults.headings_color') }}">
                        <label>{{ __('Title font color') }}</label>
                        <script>
                            $('#title_color').spectrum({
                                type: "color",
                                hideAfterPaletteSelect: true,
                                showInput: true,
                                showInitial: true,
                                showAlpha: false,
                                showButtons: true,
                                allowEmpty: false,
                            });
                        </script>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-4 col-12 form-group mt-2">
                        <input class="form-control form-control-color" id="text_color" name="text_color" value="{{ $style->text_color ?? config('defaults.font_color') }}">
                        <label>{{ __('Text font color') }}</label>
                        <script>
                            $('#text_color').spectrum({
                                type: "color",
                                hideAfterPaletteSelect: true,
                                showInput: true,
                                showInitial: true,
                                showAlpha: false,
                                showButtons: true,
                                allowEmpty: false,
                            });
                        </script>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-4 col-12 form-group mt-2">
                        <input class="form-control form-control-color" id="helper_color" name="helper_color" value="{{ $style->helper_color ?? config('defaults.font_color_light') }}">
                        <label>{{ __('Helper font color') }}</label>
                        <script>
                            $('#helper_color').spectrum({
                                type: "color",
                                hideAfterPaletteSelect: true,
                                showInput: true,
                                showInitial: true,
                                showAlpha: false,
                                showButtons: true,
                                allowEmpty: false,
                            });
                        </script>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-12 col-md-4">
                        <div class="form-group">
                            <label>{{ __('Links color') }}</label><br>
                            <input id="link_color" name="link_color" value="{{ $style->link_color ?? config('defaults.link_color') }}">
                            <script>
                                $('#link_color').spectrum({
                                    type: "color",
                                    hideAfterPaletteSelect: true,
                                    showInput: true,
                                    showInitial: true,
                                    showAlpha: false,
                                    showButtons: true,
                                    allowEmpty: false,
                                });
                            </script>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-12 col-md-4">
                        <div class="form-group">
                            <label>{{ __('Links color on hover') }}</label><br>
                            <input id="link_color_hover" name="link_color_hover" value="{{ $style->link_color_hover ?? config('defaults.link_color_hover') }}">
                            <script>
                                $('#link_color_hover').spectrum({
                                    type: "color",
                                    hideAfterPaletteSelect: true,
                                    showInput: true,
                                    showInitial: true,
                                    showAlpha: false,
                                    showButtons: true,
                                    allowEmpty: false,
                                });
                            </script>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-12 col-md-4">
                        <div class="form-group">
                            <label>{{ __('Links underline color') }}</label><br>
                            <input id="link_color_underline" name="link_color_underline" value="{{ $style->link_color_underline ?? config('defaults.link_color_hover') }}">
                            <script>
                                $('#link_color_underline').spectrum({
                                    type: "color",
                                    hideAfterPaletteSelect: true,
                                    showInput: true,
                                    showInitial: true,
                                    showAlpha: false,
                                    showButtons: true,
                                    allowEmpty: false,
                                });
                            </script>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-12 col-md-6">
                        <div class="form-group">
                            <label>{{ __('Links decoration') }}</label>
                            <select class="form-select" name="link_decoration">
                                <option @if (($style->link_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                                <option @if (($style->link_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-12 col-md-6 ">
                        <div class="form-group">
                            <label>{{ __('Decoration on hover') }}</label>
                            <select class="form-select" name="link_hover_decoration">
                                <option @if (($style->link_hover_decoration ?? null) == 'none') selected @endif value="none">{{ __('None') }}</option>
                                <option @if (($style->link_hover_decoration ?? null) == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">{{ __('Update style') }}</button>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
