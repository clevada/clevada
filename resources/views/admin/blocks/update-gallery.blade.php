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

                <div class="form-group mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shadow" name="shadow" @if ($block_extra['shadow'] ?? null) checked @endif>
                        <label class="form-check-label" for="shadow">{{ __('Add shadow to images') }}</label>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rounded" name="rounded" @if ($block_extra['rounded'] ?? null) checked @endif>
                        <label class="form-check-label" for="rounded">{{ __('Add rounded corners to image') }}</label>
                    </div>
                </div>

                <div id="hidden_div_cols" style="display: @if (isset($block_extra['masonry_layout'])) none @else block @endif" class="mt-1">
                    <div class="form-group col-md-4 col-xl-3">
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

                <div class="form-group mb-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="masonry_layout" name="masonry_layout" @if ($block_extra['masonry_layout'] ?? null) checked @endif>
                        <label class="form-check-label" for="masonry_layout">{{ __('Use Masonry to arange images') }}</label>
                    </div>
                    <div class="text-muted">{{ __('It works by placing elements in optimal position based on available vertical space.') }}</div>
                    <div class="text-muted">{{ __('This option works fine if you have many images (multiple lines). Note: caption text is not displayed') }}</div>
                </div>

                <script>
                    $('#masonry_layout').change(function() {
                        select = $(this).prop('checked');
                        if (select) {
                            document.getElementById('hidden_div_masonry').style.display = 'block';
                            document.getElementById('hidden_div_cols').style.display = 'none';
                        } else {
                            document.getElementById('hidden_div_masonry').style.display = 'none';
                            document.getElementById('hidden_div_cols').style.display = 'block';
                        }
                    })
                </script>

                <div id="hidden_div_masonry" style="display: @if (isset($block_extra['masonry_layout'])) block @else none @endif" class="mt-1">
                    <div class="form-group col-md-4 col-xl-3">
                        <label>{{ __('Select columns (number of images per row)') }}</label>
                        <select class="form-select" name="masonry_cols">
                            <option @if (($block_extra['masonry_cols'] ?? null) == 3) selected @endif value="3">3</option>
                            <option @if (($block_extra['masonry_cols'] ?? null) == 4 || is_null($block_extra['masonry_cols'] ?? null)) selected @endif value="4">4</option>
                            <option @if (($block_extra['masonry_cols'] ?? null) == 5) selected @endif value="5">5</option>
                            <option @if (($block_extra['masonry_cols'] ?? null) == 6) selected @endif value="6">6</option>
                        </select>
                        <div class="form-text">{{ __('This is the maximum number of images per row for larger displays. For smaller displays, the columns are resized automatically.') }}</div>
                    </div>

                    <div class="form-group col-md-4 col-xl-3">
                        <label>{{ __('Gutter') }}</label>
                        <select class="form-select" name="masonry_gutter">
                            <option @if (($block_extra['masonry_gutter'] ?? null) == 0 || is_null($block_extra['masonry_gutter'] ?? null)) selected @endif value="0">{{ __('No margin') }}</option>
                            <option @if (($block_extra['masonry_gutter'] ?? null) == 10) selected @endif value="10">{{ __('Small margin') }}</option>
                            <option @if (($block_extra['masonry_gutter'] ?? null) == 30) selected @endif value="30">{{ __('Large margin') }}</option>
                        </select>
                        <div class="form-text">{{ __('Gutter is the margin between images.') }}</div>
                    </div>
                </div>

                <div class="form-group mt-4 mb-0">
                    <input type="hidden" name="type" value="{{ $block->type }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
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


            @php
                $content_array = unserialize($block->content);
            @endphp

            @if ($content_array)
                @for ($i = 0; $i < count($content_array); $i++)
                    <div class="card p-3 bg-light mb-4">
                        <div class="row">

                            <div class="form-group col-md-4 col-sm-6">
                                <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                                <input class="form-control" type="file" id="formFile" name="image[]" multiple>
                                <div class="text-muted small">{{ __('Image file. Maximum 2.5 MB.') }}</div>

                                @if ($content_array[$i]['image'] ?? null)
                                    <a target="_blank" href="{{ image($content_array[$i]['image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($content_array[$i]['image']) }}"
                                            class="img-fluid mt-2"></a>
                                    <input type="hidden" name="existing_image[]" value="{{ $content_array[$i]['image'] ?? null }}">
                                    <div class="text-muted small">{{ __('Image file. Maximum 2.5 MB.') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>{{ __('Title (used as "alt" tag)') }}</label>
                                <input type="text" class="form-control" name="title[]" value="{{ $content_array[$i]['title'] ?? null }}">
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="text" name="caption[]" value="{{ $content_array[$i]['caption'] ?? null }}">
                            </div>

                            <div class="form-group col-md-2 col-sm-6">
                                <label>{{ __('Position') }}</label>
                                <input type="number" min="1" step="1" class="form-control" name="position[]" value="{{ $content_array[$i]['position'] ?? 1 }}">
                            </div>

                            <div class="form-group col-md-4 col-sm-6">
                                <label>{{ __('Link') }} ({{ __('optional') }})</label>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">https://</span>
                                    <input type="text" class="form-control" name="url[]" value="{{ $content_array[$i]['url'] ?? null }}">
                                </div>
                                <div class="form-text text-muted small">{{ __('If you add URL, you will be redirected to URL when click on image.') }}</div>
                            </div>

                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="delete_image_file_code_{{ $i }}" value="{{ $content_array[$i]['image'] ?? null }}">
                                    <input class="form-check-input" type="checkbox" id="delete_image_{{ $i }}" name="delete_image_{{ $i }}">
                                    <label class="form-check-label" for="delete_image_{{ $i }}">{{ __('Delete image') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4"></div>
                @endfor
            @endif


            <!-- The template for adding new item -->
            <div class="form-group hide" id="ItemTemplate">
                <div class="row">

                    <div class="form-group col-md-4 col-sm-6">
                        <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                        <input class="form-control" type="file" id="formFile" name="image[]" multiple>
                    </div>

                    <div class="form-group col-md-3 col-sm-6">
                        <label>{{ __('Title (used as "alt" tag)') }}</label>
                        <input type="text" class="form-control" name="title[]" />
                    </div>

                    <div class="form-group col-md-3 col-sm-6">
                        <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                        <input class="form-control" type="text" name="caption[]">
                    </div>

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('Position') }}</label>
                        <input type="number" min="1" step="1" class="form-control" name="position[]" />
                    </div>

                    <div class="form-group col-md-4 col-sm-6">
                        <label>{{ __('Link') }} ({{ __('optional') }})</label>
                        <div class="input-group mb-1">
                            <span class="input-group-text">https://</span>
                            <input type="text" class="form-control" name="url[]">
                        </div>
                        <div class="form-text text-muted small">{{ __('If you add URL, you will be redirected to URL when click on image.') }}</div>
                    </div>
                </div>
                <div class="mb-4"></div>
            </div>

            <div class="mb-3 mt-3">
                <button type="button" class="btn btn-gear addButton"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
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
                <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
