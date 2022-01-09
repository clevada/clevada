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

                    <div class="form-group mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_custom_bg" name="use_custom_bg" @if ($block_extra['bg_color'] ?? null) checked @endif>
                            <label class="form-check-label" for="use_custom_bg">{{ __('Use custom background color for this section') }}</label>
                        </div>
                        <div class="form-text">{{ __('This is the color of the section row (full width). If disabled, default website background color will be used.') }}</div>
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

                    <div id="hidden_div_color" style="display: @if (isset($block_extra['bg_color'])) block @else none @endif" class="mt-2">
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

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="login_required" name="login_required" @if ($block_extra['login_required'] ?? null) checked @endif>
                            <label class="form-check-label" for="login_required">{{ __('Only logged users can download this file') }}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-6">
                            <label for="formFile" class="form-label">{{ __('Download file') }}</label>
                            <input class="form-control" type="file" id="formFile" name="file">
                            @if ($block_extra['file'] ?? null)
                            <div class='form-text text-success fw-bold'>{{ __('Leave empty to keep existing file') }}</div>
                                <div class="mt-2"></div>
                                <i class="bi bi-download"></i> <a target="_blank" href="{{ asset('uploads/' . $block_extra['file']) }}"><b>{{ $block_extra['file'] ?? null }}</b></a>
                                <input type="hidden" name="existing_file" value="{{ $block_extra['file'] ?? null }}">
                            @endif
                        </div>
                    
                        <div class="form-group col-md-3 col-sm-6">
                            <label for="formFile" class="form-label">{{ __('File version') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="text" name="version" value="{{ $block_extra['version'] ?? null }}">
                        </div>

                        <div class="form-group col-md-3 col-sm-6">
                            <label for="formFile" class="form-label">{{ __('Release date') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="date" name="release_date" value="{{ $block_extra['release_date'] ?? null }}">
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>{{ __('Download  button style') }}</label>
                            <select class="form-select" name="download_btn_style">
                                <option @if (($block_extra['download_btn_style'] ?? null) == 'btn1') selected @endif value="btn1">{{ __('Primary button') }}</option>
                                <option @if (($block_extra['download_btn_style'] ?? null) == 'btn2') selected @endif value="btn2">{{ __('Secondary button') }}</option>
                                <option @if (($block_extra['download_btn_style'] ?? null) == 'btn3') selected @endif value="btn3">{{ __('Tertiary button') }}</option>
                            </select>
                            <div class="form-text">{{ __('You can edit buttons look in template section') }}</div>
                        </div>
                    </div>

                </div>

                <h5 class="mb-3">{{ __('Block content') }}:</h5>

                @foreach ($langs as $lang)
                    @if (count(sys_langs()) > 1 && $block_module != 'posts')
                        <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                    @endif

                    @php
                    $content_array = unserialize($lang->block_content);
                    @endphp                  

                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea class="trumbowyg" name="content_{{ $lang->id }}">{{ $content_array['content'] ?? null }}</textarea>
                    </div>

                    <div class="mb-4"></div>

                    @php
                        if ($block_module == 'posts') break;                        
                    @endphp

                    @if (count(sys_langs()) > 1 && !$loop->last)<hr>@endif
                @endforeach

                <div class="form-group">
                    <input type="hidden" name="hash" value="{{ $block_extra['hash'] ?? null }}">

                    <input type="hidden" name="type_id" value="{{ $block->type_id }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>
           

        </div>
        <!-- end card-body -->

    </div>

</section>
