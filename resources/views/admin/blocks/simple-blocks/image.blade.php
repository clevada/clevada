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
                <h4 class="card-title">{{ __('Manage block content') }} ({{ __('Image') }})</h4>
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

        @if ($message = Session::get('success'))
            <div class="alert alert-info">
                @if ($message == 'image_deleted')
                    {{ __('Image deleted') }}
                @endif
            </div>
        @endif

        @php
            if (($is_footer_block ?? null) == 1) {
                $action = route('admin.template.footer.block', ['id' => $block->id]);
            } else {
                $action = route('admin.blocks.show', ['id' => $block->id]);
            }
        @endphp

        <form id="updateBlock" method="post" enctype="multipart/form-data" action="{{ $action }}">
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

                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="hide" name="hide" @if ($block->hide ?? null) checked @endif>
                        <label class="form-check-label" for="hide">{{ __('Hide block') }}</label>
                    </div>
                    <div class="form-text">{{ __('Hidden blocks are not displayed on website') }}</div>
                </div>
            </div>

            <h5 class="mb-3">{{ __('Block content') }}:</h5>


            @php
                $content_array = unserialize($block->content ?? null);
            @endphp

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                    <input class="form-control" type="file" id="formFile" name="image">
                    @if ($content_array['image'] ?? null)
                        <div class="mt-3"></div>
                        <a target="_blank" href="{{ image($content_array['image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($content_array['image']) }}"
                                class="img-fluid"></a>
                        <input type="hidden" name="existing_image" value="{{ $content_array['image'] ?? null }}">
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label>{{ __('Title (used as "title" and "alt" tag)') }}</label>
                    <input class="form-control" type="text" name="title" value="{{ $content_array['title'] ?? null }}">
                </div>

                <div class="form-group col-md-6">
                    <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                    <input class="form-control" type="text" name="caption" value="{{ $content_array['caption'] ?? null }}">
                    <div class="text-muted small">{{ __('If set, a caption text will be displayed at the bottom of the image') }}</div>
                </div>

                <div class="form-group col-md-6">
                    <label>{{ __('URL') }} ({{ __('optional') }})</label>
                    <input class="form-control" type="text" name="url" value="{{ $content_array['url'] ?? null }}">
                    <div class="text-muted small">{{ __('If set, you will be redirected to URL when you click on image') }}</div>
                </div>
            </div>

            <div class="form-group">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
