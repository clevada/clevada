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

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        @if ($message == 'updated')
            {{ __('Updated') }}
        @endif
        @if ($message == 'created')
            {{ __('Block added. You can add content below.') }}
        @endif
    </div>
@endif

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

                <div class="form-group col-xl-2 col-lg-3">
                    <labeL>{{ __('Map height (in pixels)') }}</labeL>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-describedby="width-addon" name="height" value="{{ $block_extra['height'] ?? null }}">
                        <span class="input-group-text" id="width-addon">{{ __('pixels') }}</span>
                    </div>
                    <div class="form-text">
                        {{ __('Example: 400') }}
                    </div>
                </div>

                <div class="form-group col-xl-2 col-lg-3">
                    <labeL>{{ __('Zoom') }}</labeL>
                    <div class="input-group">
                        <input type="text" class="form-control" name="zoom" value="{{ $block_extra['zoom'] ?? '16' }}">
                    </div>
                    <div class="form-text">
                        {{ __('Numeric value from 10 (minimum zoom) to 20 (maximum zoom). Default: 16') }}
                    </div>
                </div>

                <div class="form-group mb-0">
                    <labeL>{{ __('Address') }}</labeL>
                    <input class="form-control" type="text" name="address" value="{{ $block_extra['address'] ?? null }}">
                </div>
                <div class="form-text">
                    {{ __('Map will be centered automatic based on this address. Use complete address (country, region, city, street, code). Example: "Spain, Valencia, Av. de les Balears, 59".') }}
                </div>

            </div>


            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            @php
                $header_array = unserialize($block->header);
            @endphp

            <div class="form-group mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="add_header" name="add_header" @if ($header_array['add_header'] ?? null) checked @endif>
                    <label class="form-check-label" for="add_header">{{ __('Add header content') }}</label>
                </div>
            </div>

            <script>
                $('#add_header').change(function() {
                    select = $(this).prop('checked');
                    if (select)
                        document.getElementById('hidden_div_header').style.display = 'block';
                    else
                        document.getElementById('hidden_div_header').style.display = 'none';
                })
            </script>

            <div id="hidden_div_header" style="display: @if ($header_array['add_header'] ?? null) block @else none @endif" class="mt-2">
                <div class="form-group">
                    <label>{{ __('Header title') }}</label>
                    <input class="form-control" name="header_title" value="{{ $header_array['title'] ?? null }}">
                </div>
                <div class="form-group">
                    <label>{{ __('Header content') }}</label>
                    <textarea class="form-control trumbowyg" name="header_content">{{ $header_array['content'] ?? null }}</textarea>
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
