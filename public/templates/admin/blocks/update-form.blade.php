@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage block content') }}</li>
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
                    <h4 class="card-title">{{ __('Manage block content') }} ({{ $block->type_label }})</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form id="updateBlock" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php
                    $block_extra = unserialize($block->extra);
                @endphp

                <div class="card p-3 bg-light mb-4">
                    <div class="form-group col-lg-4 col-md-6">
                        <label class="form-label" for="blockLabel">{{ __('Label') }} ({{ __('optional') }})</label>
                        <input class="form-control" type="text" id="blockLabel" name="label" value="{{ $block->label }}">
                        <div class="form-text">{{ __('You can enter a label for this block to easily identify it from multiple blocks of the same type on a page') }}</div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="hide" name="hide" @if ($block->hide ?? null) checked @endif>
                            <label class="form-check-label" for="hide">{{ __('Hide block') }}</label>                        
                        </div>
                        <div class="form-text">{{ __('Hidden blocks are not displayed on website') }}</div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_custom_bg" name="use_custom_bg" @if ($block_extra['bg_color'] ?? null) checked @endif>
                            <label class="form-check-label" for="use_custom_bg">{{ __('Use custom background color for this section') }}</label>
                            <div class="form-text">{{ __('This is the color of the section row (full width). If disabled, default website background color will be used.') }}</div>
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

                    <div id="hidden_div_color" style="display: @if (isset($block_extra['bg_color'])) block @else none @endif" class="mt-1">
                        <div class="form-group">
                            <input class="form-control form-control-color" id="bg_color" name="bg_color" value="@if (isset($block_extra['bg_color'])) {{ $block_extra['bg_color'] }} @else #fbf7f0 @endif">
                            <label>{{ __('Background color') }}</label>
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

                    <hr>

                    <div class="form-group col-md-4 col-lg-3 col-xl-2">
                        <label>{{ __('Select form') }} [<a target="_blank" href="{{ route('admin.forms.config') }}"><b>{{ __('Manage forms') }}</b></a>]</label>
                        @if(count($forms) ==0)<div class="text-danger">{{ __("You don't have any form. Go to manage forms to create a new form") }}</div>@endif
                        <select class="form-select" name="form_id">
                            <option value="">-- {{ __('select') }} --</option>
                            @foreach ($forms as $form)
                                <option @if (($block_extra['form_id'] ?? null) == $form->id) selected @endif value="{{ $form->id }}">{{ $form->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-lg-3 col-xl-2 mb-0">
                        <label>{{ __('Submit button style') }}</label>
                        <select class="form-select" name="submit_btn_style">
                            <option @if (($block_extra['submit_btn_style'] ?? null) == 'btn1') selected @endif value="btn1">{{ __('Primary button') }}</option>
                            <option @if (($block_extra['submit_btn_style'] ?? null) == 'btn2') selected @endif value="btn2">{{ __('Secondary button') }}</option>
                            <option @if (($block_extra['submit_btn_style'] ?? null) == 'btn3') selected @endif value="btn3">{{ __('Tertiary button') }}</option>
                        </select>
                        <div class="form-text">{{ __('You can edit buttons look in template section') }}</div>
                    </div>

                    @include('admin.includes.offcanvas-block-settings')
                    
                </div>

                <h5 class="mb-3">{{ __('Block content') }}:</h5>

                @foreach ($langs as $lang)

                    @if (count(sys_langs()) > 1 && $block_module != 'posts')
                        <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                    @endif

                    @php
                        $header_array = unserialize($lang->block_header);
                        $content_array = unserialize($lang->block_content);
                    @endphp

                    <div class="card p-3 bg-light mb-4">
                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="add_header_{{ $lang->id }}" name="add_header_{{ $lang->id }}" @if ($header_array['add_header'] ?? null) checked @endif>
                                <label class="form-check-label" for="add_header_{{ $lang->id }}">{{ __('Add header content') }}</label>
                            </div>
                        </div>

                        <script>
                            $('#add_header_{{ $lang->id }}').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_header_{{ $lang->id }}').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_header_{{ $lang->id }}').style.display = 'none';
                            })
                        </script>

                        <div id="hidden_div_header_{{ $lang->id }}" style="display: @if ($header_array['add_header'] ?? null) block @else none @endif" class="mt-1">                          
                            <div class="form-group">
                                <label>{{ __('Header title') }}</label>
                                <input class="form-control" name="header_title_{{ $lang->id }}" value="{{ $header_array['title'] ?? null }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Header content') }}</label>
                                <textarea class="form-control trumbowyg" name="header_content_{{ $lang->id }}">{{ $header_array['content'] ?? null }}</textarea>
                            </div>
                        </div>

                        <div class="form-group col-md-3 col-sm-6 mt-2">
                            <label>{{ __('Submit button text') }}</label>
                            <input class="form-control" type="text" name="submit_btn_text_{{ $lang->id }}" value="{{ $content_array['submit_btn_text'] ?? __('Submit') }}">
                        </div>

                    </div>

                    <div class="mb-4"></div>

                    @php
                        if ($block_module == 'posts') {
                            break;
                        }
                    @endphp

                    @if (count(sys_langs()) > 1 && !$loop->last)<hr>@endif
                @endforeach

                <div class="form-group">
                    <input type="hidden" name="type_id" value="{{ $block->type_id }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
