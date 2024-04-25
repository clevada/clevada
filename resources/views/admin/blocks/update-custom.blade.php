<link rel="stylesheet" href="{{ config('app.cdn') }}/vendor/prism/prism.css">
<link rel="stylesheet" href="{{ config('app.cdn') }}/vendor/prism/prism-live.css">

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


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <h4 class="card-title">@include('admin.includes.block_type_label', ['type' => $block->type]) - {{ __('Manage block content') }}</h4>
            </div>

        </div>

    </div>


    <div class="card-body">

        <form id="updateBlock" method="post">
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
                        <input class="form-check-input" type="checkbox" id="use_custom_style" name="use_custom_style" @if ($block_extra['style_id'] ?? null) checked @endif>
                        <label class="form-check-label" for="use_custom_style">{{ __('Use custom style for this section') }}</label>
                    </div>
                </div>

                <script>
                    $('#use_custom_style').change(function() {
                        select = $(this).prop('checked');
                        if (select)
                            document.getElementById('hidden_div_style').style.display = 'block';
                        else
                            document.getElementById('hidden_div_style').style.display = 'none';
                    })
                </script>

                <div id="hidden_div_style" style="display: @if (isset($block_extra['style_id'])) block @else none @endif" class="mt-2">
                    <div class="form-group col-xl-3 col-lg-4 col-md-6 mb-0">
                        <label>{{ __('Select custom style') }} [<a class="fw-bold" target="_blank" href="{{ route('admin.template.styles') }}">{{ __('manage custom styles') }}</a>]</label>
                        <select class="form-select" id="style_id" name="style_id" value="@if (isset($block_extra['style_id'])) {{ $block_extra['style_id'] }} @else #fbf7f0 @endif">
                            <option value="">-- {{ __('select') }} --</option>
                            @foreach ($styles as $style)
                                <option @if (($block_extra['style_id'] ?? null) == $style->id) selected @endif value="{{ $style->id }}">{{ $style->label }}</option>
                            @endforeach
                        </select>
                        @if (count($styles) == 0)
                            <div class="small text-info mt-1">{{ __("You don't have custom styles created") }}</div>
                        @endif
                    </div>
                </div>


            </div>

            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            <div class="alert alert-info">
                {{ __('Warning: HTML / CSS / JavaScript and tamplate functions code allowed. Bootstrap 5 code is allowed.') }}
            </div>

            @foreach ($content_langs as $lang)
                @if (count($langs) > 1 && $block_module != 'posts')
                    <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                @endif

                <textarea name="content_{{ $lang->id }}" class="prism-live line-numbers language-html fill">{{ $lang->block_content }}</textarea>

                <div class="mb-4"></div>

                @php
                    if ($block_module == 'posts') {
                        break;
                    }
                @endphp

                @if (count($langs) > 1 && !$loop->last)
                    <hr>
                @endif
            @endforeach

            <div class="form-group">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>


<script src="{{ config('app.cdn') }}/vendor/prism/bliss.shy.min.js"></script>
<script src="{{ config('app.cdn') }}/vendor/prism/prism.js"></script>
<script src="{{ config('app.cdn') }}/vendor/prism/prism-line-numbers.js"></script>
<script src="{{ config('app.cdn') }}/vendor/prism/prism-live.js"></script>
<script src="{{ config('app.cdn') }}/vendor/prism/prism-live-markup.js"></script>
<script src="{{ config('app.cdn') }}/vendor/prism/prism-live-css.js"></script>
<script src="{{ config('app.cdn') }}/vendor/prism/prism-live-javascript.js"></script>
