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

                    @include('admin.includes.offcanvas-block-settings')
                </div>

                <h5 class="mb-3">{{ __('Block content') }}:</h5>

                @foreach ($langs as $lang)
                    @if (count(sys_langs()) > 1 && $block_module != 'posts')
                        <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                    @endif

                    <div class="form-group">
                        <textarea class="trumbowyg" name="content_{{ $lang->id }}">{{ $lang->block_content }}</textarea>
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
