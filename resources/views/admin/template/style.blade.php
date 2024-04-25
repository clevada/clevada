@include('tenant.admin.template.includes.import-fonts')
@include('tenant.admin.includes.color-picker')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template') }}">{{ __('Template') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template.styles') }}">{{ __('Styles') }}</a></li>
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

                <div class="col-12">
                    @include('tenant.admin.template.includes.menu-template')
                </div>

            </div>

        </div>


        <div class="card-body">

            <div class="mt-2 mb-2 fs-5">{{ __('Edit style') }}: <b>{{ $style->label }}</b></div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated')
                        <h4 class="alert-heading">{{ __('Updated') }}</h4>
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Info: If you don\'t see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.') }}
                    @endif
                    @if ($message == 'created')
                        <h4 class="alert-heading">{{ __('Created') }}</h4>                    
                    @endif
                </div>
            @endif

            @php
                if ($style->link_decoration == 'none') {
                    $text_decoration = 'none';
                } else {
                    $text_decoration = 'underline';
                }
                if ($style->link_hover_decoration == 'none') {
                    $text_decoration_hover = 'none';
                } else {
                    $text_decoration_hover = 'underline';
                }
            @endphp

            <style>
                .style_{{ $style->id }} {
                    font-size: {{ $style->text_size ?? config('defaults.font_size') }};
                    color: {{ $style->text_color ?? config('defaults.font_color') }};
                    font-weight: {{ $style->text_font_weight ?? 'normal' }};
                    font-family: {!! $style->font_family !!};
                    background-color: {{ $style->bg_color ?? 'inherit' }};
                    @if($style->bg_color) padding: 10px; @endif
                }
                .style_{{ $style->id }} h1 {
                    font-size: {{ $style->h1_size ?? config('defaults.h1_size') }};
                    font-weight: {{ $style->headings_font_weight ?? 'normal' }};                    
                }
                .style_{{ $style->id }} h2 {
                    font-size: {{ $style->h2_size ?? config('defaults.h2_size') }};
                    font-weight: {{ $style->headings_font_weight ?? 'normal' }};                    
                }
                .style_{{ $style->id }} h3 {
                    font-size: {{ $style->h3_size ?? config('defaults.h3_size') }};
                    font-weight: {{ $style->headings_font_weight ?? 'normal' }};                    
                }
                .style_{{ $style->id }} .light {
                    font-size: 0.8em;
                    color: {{ $style->light_color ?? config('defaults.font_color_light') }};                   
                }
                .style_{{ $style->id }} a{
                    color: {{ $style->link_color ?? config('defaults.link_color') }};
                    font-weight: {{ $style->link_font_weight ?? 'normal' }};
                    font-family: {!! $style->font_family !!};
                    text-decoration: {{ $text_decoration }} {{ $style->link_underline_color }};
                    text-decoration-thickness: {{ $style->link_underline_thickness ?? 'auto' }};
                    text-underline-offset: {{ $style->link_underline_offset}};
                    
                    @if ($style->link_decoration == 'double') text-decoration-style: double; @endif
                    @if ($style->link_decoration == 'dashed') text-decoration-style: dashed; @endif
                    @if ($style->link_decoration == 'dotted') text-decoration-style: dotted; @endif
                    @if ($style->link_decoration == 'wavy') text-decoration-style: wavy; @endif
                }

                .style_{{ $style->id }} a:hover {
                    color: {{ $style->link_hover_color ?? config('defaults.link_color') }};
                    font-weight: {{ $style->link_font_weight ?? 'normal' }};
                    font-family: {!! $style->font_family !!};
                    text-decoration: {{ $text_decoration_hover }} {{ $style->link_underline_color_hover }};
                    text-decoration-thickness: {{ $style->link_underline_thickness ?? 'auto' }};
                    text-underline-offset: {{ $style->link_underline_offset}};

                    @if ($style->link_hover_decoration == 'double') text-decoration-style: double; @endif
                    @if ($style->link_hover_decoration == 'dashed') text-decoration-style: dashed; @endif
                    @if ($style->link_hover_decoration == 'dotted') text-decoration-style: dotted; @endif
                    @if ($style->link_hover_decoration == 'wavy') text-decoration-style: wavy; @endif
                }
            </style>

            <b>{{ __('Preview') }}:</b> 
            <div class="style_{{ $style->id }}">
                <h1>This is Heading 1 title</h1>
                <h2>This is Heading 2 sub-title</h2>
                <h3>This is Heading 3 sub-sub-title</h3>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vehicula dui nisl. <a href="#">Quisque ullamcorper orci enim</a>, sed porta diam bibendum in. Pellentesque in aliquet diam, a porta magna.
                Almost before <a href="#">we knew it</a>, we had left the ground. onec malesuada at elit facilisis lobortis. 
                Curabitur scelerisque ornare urna vel vulputate. Cras <a href="#">pellentesque eros ut tellus scelerisque</a>, at iaculis felis vehicula. Quisque pulvinar sem quis turpis scelerisque aliquam. </a>
                <div class="light mt-1">This is a light text color.</div>
            </div>

            <div class="form-text text-muted small mt-3"><i class="bi bi-info-circle"></i> {{ __('Update style to preview using new changes') }}</div>

            <hr>

            <form method="post">
                @csrf
                @method('PUT')

               
                <div class="form-group mb-4">
                    <div class="col-12 col-lg-3 col-md-4">
                        <div class="form-group">
                            <label>{{ __('Label') }}</label>
                            <input class="form-control" name="label" type="text" required value="{{ $style->label }}" />
                        </div>
                    </div>
                </div>

               
                <div class="row">
                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <input id="text_color" name="text_color" value="{{ $style->text_color ?? config('defaults.font_color') }}">
                            <label>{{ __('Main text color') }}</label>
                            <div class="mt-1 small"> {{ strtoupper($style->text_color) ?? config('defaults.font_color') }}</div>
                            <script>
                                $('#text_color').spectrum({
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

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <input id="link_color" name="link_color" value="{{ $style->link_color ?? config('defaults.link_color') }}">
                            <label>{{ __('Link color') }}</label>
                            <div class="mt-1 small"> {{ strtoupper($style->link_color) ?? config('defaults.link_color') }}</div>
                            <script>
                                $('#link_color').spectrum({
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

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <input id="link_hover_color" name="link_hover_color" value="{{ $style->link_hover_color ?? config('defaults.link_color_hover') }}">
                            <label>{{ __('Link color on mouse hover') }}</label>
                            <div class="mt-1 small"> {{ strtoupper($style->link_hover_color) ?? config('defaults.link_color_hover') }}</div>
                            <script>
                                $('#link_hover_color').spectrum({
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

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <input id="link_underline_color" name="link_underline_color" value="{{ $style->link_underline_color ?? config('defaults.link_color_underline') }}">
                            <label>{{ __('Underline color') }}</label>
                            <div class="mt-1 small"> {{ strtoupper($style->link_underline_color) ?? config('defaults.link_color_underline') }}</div>
                            <script>
                                $('#link_underline_color').spectrum({
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

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <input id="link_underline_color_hover" name="link_underline_color_hover" value="{{ $style->link_underline_color_hover ?? config('defaults.link_color_underline_hover') }}">
                            <label>{{ __('Underline color on hover') }}</label>
                            <div class="mt-1 small"> {{ strtoupper($style->link_underline_color_hover) ?? config('defaults.link_color_underline_hover') }}</div>
                            <script>
                                $('#link_underline_color_hover').spectrum({
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

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <input id="light_color" name="light_color" value="{{ $style->light_color ?? config('defaults.font_color_light') }}">
                            <label>{{ __('Light text color') }}</label>
                            <div class="mt-1 small"> {{ strtoupper($style->light_color) ?? config('defaults.font_color_light') }}</div>
                            <script>
                                $('#light_color').spectrum({
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
                    
                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="use_custom_bg" name="use_custom_bg" @if ($style->bg_color ?? null) checked @endif>
                                <label class="form-check-label" for="use_custom_bg">@if($style->is_default==1){{ __('Body background color') }}@else{{ __('Use custom background color') }}@endif</label>                        
                            </div>
                            <div class="form-text">
                                @if($style->is_default==1){{ __('Website background color') }}@else{{ __('This is the color of the section row (full width) who use this style. If disabled, default section background color will be used.') }}@endif
                            </div>
                        </div>
                    </div>

                    <script>
                        $('#use_custom_bg').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div_color').style.display = 'block';
                            else
                                document.getElementById('hidden_div_color').style.display = 'none';
                        })
                    </script>
    
                    <div class="col-sm-4 col-md-3 col-12">
                        <div id="hidden_div_color" style="display: @if ($style->bg_color ?? null) block @else none @endif" class="mt-1">
                            <div class="form-group mb-4">
                                <input id="bg_color" name="bg_color" value="{{ $style->bg_color ?? '#ffffff' }}">
                                <label>{{ __('Background color') }}</label>
                                <div class="mt-1 small"> {{ strtoupper($style->bg_color) ?? '#ffffff' }}</div>
                                <script>
                                    $('#bg_color').spectrum({
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

                <div class="row">
                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Text font size') }}</label>
                            <select class="form-select" name="text_size">
                                @foreach ($font_sizes as $font_size)
                                    <option @if ($style->text_size == $font_size->value) selected @endif @if ((!$style->text_size) && $font_size->value == '1em') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Title (Heading 1) font size') }}</label>
                            <select class="form-select" name="h1_size">
                                @foreach ($font_sizes as $font_size)
                                    <option @if ($style->h1_size == $font_size->value) selected @endif @if ((!$style->h1_size) && $font_size->value == '2em') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Sub-title (Heading 2) font size') }}</label>
                            <select class="form-select" name="h2_size">
                                @foreach ($font_sizes as $font_size)
                                    <option @if ($style->h2_size == $font_size->value) selected @endif @if ((!$style->h2_size) && $font_size->value == '1.5em') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Sub-sub-title (Heading 3) font size') }}</label>
                            <select class="form-select" name="h3_size">
                                @foreach ($font_sizes as $font_size)
                                    <option @if ($style->h3_size == $font_size->value) selected @endif @if ((!$style->h3_size) && $font_size->value == '1.2em') selected @endif value="{{ $font_size->value }}">{{ $font_size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Link decoration') }}</label>
                            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_decoration">
                                <option @if ($style->link_decoration == 'none') selected @endif value="none">{{ __('None') }}</option>
                                <option @if ($style->link_decoration == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                <option @if ($style->link_decoration == 'double') selected @endif value="double">{{ __('Double line') }}</option>
                                <option @if ($style->link_decoration == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                                <option @if ($style->link_decoration == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                                <option @if ($style->link_decoration == 'wavy') selected @endif value="wavy">{{ __('Wavy') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Link decoration on hover') }}</label>
                            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_hover_decoration">
                                <option @if ($style->link_hover_decoration == 'none') selected @endif value="none">{{ __('None') }}</option>
                                <option @if ($style->link_hover_decoration == 'underline') selected @endif value="underline">{{ __('Underline') }}</option>
                                <option @if ($style->link_hover_decoration == 'double') selected @endif value="double">{{ __('Double line') }}</option>
                                <option @if ($style->link_hover_decoration == 'dotted') selected @endif value="dotted">{{ __('Dotted') }}</option>
                                <option @if ($style->link_hover_decoration == 'dashed') selected @endif value="dashed">{{ __('Dashed') }}</option>
                                <option @if ($style->link_hover_decoration == 'wavy') selected @endif value="wavy">{{ __('Wavy') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Font weight (links)') }}</label>
                            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_font_weight">
                                <option @if ($style->link_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                <option @if ($style->link_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Font weight (main text)') }}</label>
                            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="text_font_weight">
                                <option @if ($style->text_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                <option @if ($style->text_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Font weight (Heading 1, Heading 2. Heading 3)') }}</label>
                            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="headings_font_weight">
                                <option @if ($style->headings_font_weight == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                <option @if ($style->headings_font_weight == 'bold') selected @endif value="bold">{{ __('Bold') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Underline line thickness') }}</label>
                            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_underline_thickness">
                                <option @if ($style->link_underline_thickness == 'auto') selected @endif value="auto">{{ __('Normal') }}</option>
                                <option @if ($style->link_underline_thickness == '3px') selected @endif value="3px">{{ __('Bold') }}</option>
                                <option @if ($style->link_underline_thickness == '6px') selected @endif value="6px">{{ __('Bolder') }}</option>                            
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-3 col-12">
                        <div class="form-group mb-4">
                            <label>{{ __('Underline offset') }}</label>
                            <select class="form-select col-md-6 col-lg-4 col-xl-3" name="link_underline_offset">
                                <option @if ($style->link_underline_offset == 'auto') selected @endif value="auto">{{ __('Normal (no offset)') }}</option>
                                <option @if ($style->link_underline_offset == '0.17em') selected @endif value="0.17em">{{ __('Small offset') }}</option>
                                <option @if ($style->link_underline_offset == '0.35em') selected @endif value="0.35em">{{ __('Medium offset') }}</option>                            
                                <option @if ($style->link_underline_offset == '0.6em') selected @endif value="0.6em">{{ __('Big offset') }}</option>                            
                            </select>
                        </div>
                    </div>            

                    <div class="col-sm-6 col-md-6 col-12">
                        <div class="form-group">                          
                            <label>{{ __('Font family') }}</label>
                            <select class="form-select" name="font_family">
                                @foreach ($fonts as $font)
                                    <option @if ($style->font_family == $font->value) selected @endif value="{{ $font->value }}" style="font-size: 1.6em; font-family: {{ $font->value }};">[{{ $font->name }}]
                                        Almost before we knew it, we had left the ground.</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="col-md-6 col-12">
                    <div class="d-grid gap-2 my-3">
                        <a class="btn btn-light fw-bold" data-bs-toggle="collapse" href="#collapseSettings" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <span class="float-start">{{ __('More settings') }} <i class="bi bi-chevron-down"></i></span>
                        </a>
                    </div>

                    <div class="collapse" id="collapseSettings">                

                        <div class="alert alert-light text-danger fw-bold"><i class="bi bi-info-circle"></i> {{ __('Do not use this settings for full width sections, like website body, top menu, footer, full width blocks....') }}</div>

                        <div class="col-md-6 col-12">
                            <div class="form-group mb-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="use_bg_color_hover" name="use_bg_color_hover" @if ($style->bg_color_hover ?? null) checked @endif>
                                    <label class="form-check-label" for="use_bg_color_hover">{{ __('Use custom background color on mouse hover') }}</label>                        
                                </div>                        
                            </div>
                        </div>

                        <script>
                            $('#use_bg_color_hover').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_bg_color_hover').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_bg_color_hover').style.display = 'none';
                            })
                        </script>

                        <div class="col-md-6 col-12">
                            <div id="hidden_div_bg_color_hover" style="display: @if ($style->bg_color_hover ?? null) block @else none @endif" class="mt-1">
                                <div class="form-group mb-4">
                                    <input id="bg_color_hover" name="bg_color_hover" value="{{ $style->bg_color_hover ?? '#ffffff' }}">
                                    <label>{{ __('Background color') }}</label>
                                    <div class="mt-1 small"> {{ strtoupper($style->bg_color) ?? '#ffffff' }}</div>
                                    <script>
                                        $('#bg_color_hover').spectrum({
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

                        
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="shaddow" name="shaddow" @if ($style->shaddow ?? null) checked @endif>
                                    <label class="form-check-label" for="shaddow">{{ __('Add shaddow around the section') }}</label>
                                </div>
                            </div>
                        </div>

                        <script>
                            $('#shaddow').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_shaddow').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_shaddow').style.display = 'none';
                            })
                        </script>

                        <div class="col-md-6 col-12">
                            <div id="hidden_div_shaddow" style="display: @if ($style->shaddow ?? null) block @else none @endif" class="mt-1">
                                <div class="form-group mb-4">
                                    <label>{{ __('Shaddow style') }}</label>
                                    <select class="form-select" name="shaddow_style">
                                        <option @if ($style->shadow == 'small') selected @endif value="small">{{ __('Small') }}</option>
                                        <option @if ($style->shadow == 'normal') selected @endif value="normal">{{ __('Normal') }}</option>
                                        <option @if ($style->shadow == 'large') selected @endif value="large">{{ __('Large') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-4">
                                <label>{{ __('Border rounded') }}</label>
                                <select class="form-select" name="rounded">
                                    <option @if ($style->rounded == '0') selected @endif value="0">{{ __('No radius') }}</option>
                                    <option @if ($style->rounded == '0.25rem') selected @endif value="0.25rem">{{ __('Small') }}</option>
                                    <option @if ($style->rounded == '0.38rem') selected @endif value="0.38rem">{{ __('Medium') }}</option>
                                    <option @if ($style->rounded == '0.5rem') selected @endif value="0.5rem">{{ __('Large') }}</option>
                                    <option @if ($style->rounded == '1rem') selected @endif value="1rem">{{ __('Extra large') }}</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>    
                
                <hr>

                <button type="submit" class="btn btn-primary">{{ __('Update style') }}</button>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
