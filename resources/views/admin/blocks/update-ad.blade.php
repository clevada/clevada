@include('admin.includes.color-picker')

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

                <div class="form-group col-md-4 col-lg-3 col-xl-2">
                    <label>{{ __('Select ad') }} [<a target="_blank" href="{{ route('admin.ads.index') }}"><b>{{ __('Manage ads') }}</b></a>]</label>
                    @if (count($ads) == 0)
                        <div class="text-danger">{{ __("You don't have any ad. Go to manage ads to create a new ad") }}</div>
                    @endif
                    <select class="form-select" name="ad_id">
                        <option value="">-- {{ __('select') }} --</option>
                        @foreach ($ads as $ad)
                            <option @if (($block_extra['ad_id'] ?? null) == $ad->id) selected @endif value="{{ $ad->id }}">{{ $ad->label }} @if ($ad->type == 'image')
                                    ({{ __('image') }})
                                    @endif @if ($ad->type == 'code')
                                        ({{ __('code') }})
                                    @endif
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

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
