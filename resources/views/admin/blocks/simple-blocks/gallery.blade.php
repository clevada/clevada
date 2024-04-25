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



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <h4 class="card-title">{{ __('Manage block content') }} ({{ __('Images gallery') }})</h4>
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
                <div class="form-group mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shaddow" name="shaddow" @if ($block_extra['shaddow'] ?? null) checked @endif>
                        <label class="form-check-label" for="shaddow">{{ __('Add shaddow to images') }}</label>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rounded" name="rounded" @if ($block_extra['rounded'] ?? null) checked @endif>
                        <label class="form-check-label" for="rounded">{{ __('Add rounded corner to images') }}</label>
                    </div>
                </div>

                <div class="form-group col-md-4 mb-0">
                    <label>{{ __('Select columns (number of images per row)') }}</label>
                    <select class="form-select" name="cols">
                        <option @if (($block_extra['cols'] ?? null) == 2) selected @endif value="2">2</option>
                        <option @if (($block_extra['cols'] ?? null) == 3) selected @endif value="3">3</option>
                        <option @if (($block_extra['cols'] ?? null) == 4 || is_null($block_extra['cols'] ?? null)) selected @endif value="4">4</option>
                        <option @if (($block_extra['cols'] ?? null) == 6) selected @endif value="6">6</option>
                    </select>
                    <div class="form-text">{{ __('This is the maximum number of images per row for larger displays. For smaller displays, the columns are resized automatically.') }}</div>
                </div>

            </div>

            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            @php
                $header_array = unserialize($block->header);
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

            @if ($content_array)
                @for ($i = 0; $i < count($content_array); $i++)
                    <div class="card p-3 bg-light mb-4">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                                <input class="form-control" type="file" id="formFile" name="image[]" multiple>

                                @if ($content_array[$i]['image'] ?? null)
                                    <a target="_blank" href="{{ image($content_array[$i]['image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($content_array[$i]['image']) }}"
                                            class="img-fluid mt-2"></a>
                                    <input type="hidden" name="existing_image[]" value="{{ $content_array[$i]['image'] ?? null }}">
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('Title (used as "alt" tag)') }} ({{ __('required') }})</label>
                                <input type="text" class="form-control" name="title[]" value="{{ $content_array[$i]['title'] ?? null }}">
                                <div class="form-text text-danger">{{ __("Image is not added if you don't add a title") }}</div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="text" name="caption[]" value="{{ $content_array[$i]['caption'] ?? null }}">
                                <div class="text-muted small">{{ __('If set, a caption text will be displayed at the bottom of the image') }}</div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('URL') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="url[]" value="{{ $content_array[$i]['url'] ?? null }}">
                                <div class="text-muted small">{{ __('If set, you will be redirected to URL when you click on image') }}</div>
                            </div>

                            <div class="form-group col-md-2 col-sm-6">
                                <label>{{ __('Position') }}</label>
                                <input type="text" class="form-control" name="position[]" value="{{ $content_array[$i]['position'] ?? null }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4"></div>
                @endfor
            @endif

            <div class="mb-3 mt-3">
                <button type="button" class="btn btn-light addButton"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
            </div>

            <!-- The template for adding new item -->
            <div class="form-group hide" id="ItemTemplate">
                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                        <input class="form-control" type="file" id="formFile" name="image[]" multiple>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('Title (used as "alt" tag)') }} ({{ __('required') }})</label>
                        <input type="text" class="form-control" name="title[]" />
                        <div class="form-text text-danger">{{ __("Image is not added if you don't add a title") }}</div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                        <input class="form-control" type="text" name="caption[]">
                        <div class="text-muted small">{{ __('If set, a caption text will be displayed at the bottom of the image') }}</div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ __('URL') }} ({{ __('optional') }})</label>
                        <input type="text" class="form-control" name="url[]">
                        <div class="text-muted small">{{ __('If set, you will be redirected to URL when you click on image') }}</div>
                    </div>

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('Position') }}</label>
                        <input type="text" class="form-control" name="position[]" />
                    </div>
                </div>
                <div class="mb-4"></div>
            </div>

            <script>
                $(document).ready(function() {
                    urlIndex = 0;
                    $('#updateBlock')
                        // Add button click handler
                        .on('click', '.addButton', function() {
                            urlIndex++;
                            var $template = $('#ItemTemplate'),
                                $clone = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-block-index', urlIndex)
                                .insertBefore($template);

                            // Update the name attributes
                            $clone
                                .find('[name="title"]').attr('name', 'updateBlock[' + urlIndex + '].title').end()
                                .find('[name="image"]').attr('name', 'updateBlock[' + urlIndex + '].image').end()
                                .find('[name="caption"]').attr('name', 'updateBlock[' + urlIndex + '].caption').end()
                                .find('[name="position"]').attr('name', 'updateBlock[' + urlIndex + '].position').end()
                                .find('[name="url"]').attr('name', 'updateBlock[' + urlIndex + '].url').end();
                        })

                });
            </script>


            <div class="form-group">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
