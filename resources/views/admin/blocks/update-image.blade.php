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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. This block exists') }}
                @endif
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

                <div class="form-group mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shadow" name="shadow" @if ($block_extra['shadow'] ?? null) checked @endif>
                        <label class="form-check-label" for="shadow">{{ __('Add shadow to image') }}</label>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rounded" name="rounded" @if ($block_extra['rounded'] ?? null) checked @endif>
                        <label class="form-check-label" for="rounded">{{ __('Add rounded corners to image') }}</label>
                    </div>
                </div>
            </div>

            <h5 class="mb-3">{{ __('Block content') }}:</h5>


            @php
                $header_array = unserialize($block->header ?? null);
            @endphp

            <div class="card p-3 bg-light mb-4">
                <div class="form-group mb-0">
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
            </div>

            @php
                $content_array = unserialize($block->content ?? null);
            @endphp

            <div class="row">
                <div class="form-group col-md-3 col-sm-6">
                    <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                    <input class="form-control" type="file" id="formFile" name="image">
                    <div class="text-muted small">{{ __('Image file. Maximum 5 MB.') }}</div>
                    @if ($content_array['image'] ?? null)
                        <div class="mt-3"></div>
                        <a target="_blank" href="{{ image($content_array['image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($content_array['image']) }}" class="img-fluid"></a>
                        <input type="hidden" name="existing_image" value="{{ $content_array['image'] ?? null }}">
                    @endif
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label>{{ __('Title (used as "title" and "alt" tag)') }}</label>
                    <input class="form-control" type="text" name="title" value="{{ $content_array['title'] ?? null }}">
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                    <input class="form-control" type="text" name="caption" value="{{ $content_array['caption'] ?? null }}">
                    <div class="text-muted small">{{ __('If set, a caption text will be displayed at the bottom of the image') }}</div>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label>{{ __('URL') }} ({{ __('optional') }})</label>
                    <div class="input-group">
                        <span class="input-group-text" id="addonUrl">https://</span>
                        <input class="form-control" type="text" name="url" value="{{ $content_array['url'] ?? null }}" aria-describedby="addonUrl">                        
                    </div>
                    <div class="text-muted small">{{ __('If set, you will be redirected to URL when you click on image') }}</div>
                </div>
            </div>

            <div class="form-group mt-3">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
