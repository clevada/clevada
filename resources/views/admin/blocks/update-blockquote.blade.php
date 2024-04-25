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
        @if ($message == 'image_deleted')
            {{ __('Image deleted') }}
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

                <div class="form-group mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shadow" name="shadow" @if ($block_extra['shadow'] ?? null) checked @endif>
                        <label class="form-check-label" for="shadow">{{ __('Add shadow to blockquote') }}</label>
                    </div>
                </div>


                <div class="form-group mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="use_avatar" name="use_avatar" @if ($block_extra['use_avatar'] ?? null) checked @endif>
                        <label class="form-check-label" for="use_avatar">{{ __('Add avatar image') }}</label>
                    </div>
                </div>

                <script>
                    $('#use_avatar').change(function() {
                        select = $(this).prop('checked');
                        if (select)
                            document.getElementById('hidden_div_avatar').style.display = 'block';
                        else
                            document.getElementById('hidden_div_avatar').style.display = 'none';
                    })
                </script>

                <div id="hidden_div_avatar" style="display: @if ($block_extra['use_avatar'] ?? null) block @else none @endif">
                    <div class="form-group mb-3">
                        <label for="formFile" class="form-label">{{ __('Source avatar') }} ({{ __('optional') }})</label>
                        <input class="form-control" type="file" id="formFile" name="avatar">
                    </div>
                    @if ($block_extra['avatar'] ?? null)
                        <div class="mb-3">
                            <a target="_blank" href="{{ image($block_extra['avatar']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($block_extra['avatar']) }}" class="img-fluid"></a>
                        </div>
                    @endif
                </div>
              
            </div>


            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            @php
                $content_array = unserialize($block->content);
            @endphp

            <div class="form-group">
                <label>{{ __('Source') }} ({{ __('optional') }})</label>
                <input class="form-control" type="text" name="source" value="{{ $content_array['source'] ?? null }}">
            </div>

            <div class="form-group">
                <label>{{ __('Content') }}</label>
                <textarea class="form-control" name="content">{{ $content_array['content'] ?? null }}</textarea>
                <div class="form-text">{{ __('HTML tags allowed') }}</div>
            </div>


            <div class="form-group">
                <input type="hidden" name="existing_avatar" value="{{ $block_extra['avatar'] ?? null }}">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
            </div>
        </form>

    </div>
    <!-- end card-body -->
</div>
