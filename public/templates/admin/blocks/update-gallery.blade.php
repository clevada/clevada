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

                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="shaddow" name="shaddow" @if ($block_extra['shaddow'] ?? null) checked @endif>
                            <label class="form-check-label" for="shaddow">{{ __('Add shaddow to images') }}</label>
                        </div>
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

                    <div id="hidden_div_color" style="display: @if (isset($block_extra['bg_color'])) block @else none @endif" class="mt-4">
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

                    <div class="form-group col-md-4 mb-0 mt-3">
                        <label>{{ __('Select columns (number of images per row)') }}</label>
                        <select class="form-select" name="cols">
                            <option @if (($block_extra['cols'] ?? null) == 2) selected @endif value="2">2</option>
                            <option @if (($block_extra['cols'] ?? null) == 3) selected @endif value="3">3</option>
                            <option @if (($block_extra['cols'] ?? null) == 4 || is_null($block_extra['cols'] ?? null)) selected @endif value="4">4</option>
                            <option @if (($block_extra['cols'] ?? null) == 6) selected @endif value="6">6</option>
                        </select>
                        <div class="form-text">{{ __('This is the maximum number of images per row for larger displays. For smaller displays, the columns are resized automatically.') }}</div>
                    </div>

                    @include('admin.includes.offcanvas-block-settings')
                    
                </div>

                <h5 class="mb-3">{{ __('Block content') }}:</h5>

                @foreach ($langs as $lang)

                    @if (count(sys_langs()) > 1 && $block_module != 'posts')
                        <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                    @endif

                    @php
                        $header_array = unserialize($lang->block_header);
                    @endphp

                    <div class="card p-3 bg-light mb-4">
                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="add_header_{{ $lang->id }}" name="add_header_{{ $lang->id }}" @if ($header_array['add_header'] ?? null) checked @endif>
                                <label class="form-check-label" for="add_header_{{ $lang->id }}">{{ __('Add header content') }}</label>
                            </div>
                        </div>

                        <script>
                            $('#add_header_{{ $lang->id }}').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div_header_{{ $lang->id }}').style.display = 'block';
                                else
                                    document.getElementById('hidden_div_header_{{ $lang->id }}').style.display = 'none';
                            })
                        </script>

                        <div id="hidden_div_header_{{ $lang->id }}" style="display: @if ($header_array['add_header'] ?? null) block @else none @endif" class="mt-2">                          
                            <div class="form-group">
                                <label>{{ __('Header title') }}</label>
                                <input class="form-control" name="header_title_{{ $lang->id }}" value="{{ $header_array['title'] ?? null }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Header content') }}</label>
                                <textarea class="form-control trumbowyg" name="header_content_{{ $lang->id }}">{{ $header_array['content'] ?? null }}</textarea>
                            </div>
                        </div>
                    </div>


                    @php
                        $content_array = unserialize($lang->block_content);
                    @endphp

                    @if ($content_array)
                        @for ($i = 0; $i < count($content_array); $i++)
                            <div class="card p-3 bg-light mb-4">
                                <div class="row">

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                                        <input class="form-control" type="file" id="formFile" name="image_{{ $lang->id }}[]" multiple>

                                        @if ($content_array[$i]['image'] ?? null)
                                            <a target="_blank" href="{{ image($content_array[$i]['image']) }}"><img style="max-width: 300px; max-height: 100px;"
                                                    src="{{ image($content_array[$i]['image']) }}" class="img-fluid mt-2"></a>
                                            <input type="hidden" name="existing_image_{{ $lang->id }}[]" value="{{ $content_array[$i]['image'] ?? null }}">
                                        @endif
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6">
                                        <label>{{ __('Title (used as "alt" tag)') }} ({{ __('required') }})</label>
                                        <input type="text" class="form-control" name="title_{{ $lang->id }}[]" value="{{ $content_array[$i]['title'] ?? null }}">
                                        <div class="form-text text-danger">{{ __("Image is not added if you don't add a title") }}</div>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6">
                                        <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                                        <input class="form-control" type="text" name="caption_{{ $lang->id }}[]" value="{{ $content_array[$i]['caption'] ?? null }}">
                                    </div>

                                    <div class="form-group col-md-2 col-sm-6">
                                        <label>{{ __('Position') }}</label>
                                        <input type="text" class="form-control" name="position_{{ $lang->id }}[]" value="{{ $content_array[$i]['position'] ?? null }}">
                                    </div>

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>{{ __('URL') }} ({{ __('optional') }})</label>
                                        <input type="text" class="form-control" name="url_{{ $lang->id }}[]" value="{{ $content_array[$i]['url'] ?? null }}">
                                        <div class="form-text text-info">{{ __("If you add URL, the link is opened, instead images gallery player.") }}</div>
                                    </div>

                                </div>
                            </div>

                            <div class="mb-4"></div>
                        @endfor
                    @endif

                    <div class="mb-3 mt-3">
                        <button type="button" class="btn btn-light addButton_{{ $lang->id }}"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
                    </div>

                    <!-- The template for adding new item -->
                    <div class="form-group hide" id="ItemTemplate_{{ $lang->id }}">
                        <div class="row">

                            <div class="form-group col-md-4 col-sm-6">
                                <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                                <input class="form-control" type="file" id="formFile" name="image_{{ $lang->id }}[]" multiple>
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>{{ __('Title (used as "alt" tag)') }} ({{ __('required') }})</label>
                                <input type="text" class="form-control" name="title_{{ $lang->id }}[]"/>
                                <div class="form-text text-danger">{{ __("Image is not added if you don't add a title") }}</div>
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="text" name="caption_{{ $lang->id }}[]">
                            </div>

                            <div class="form-group col-md-2 col-sm-6">
                                <label>{{ __('Position') }}</label>
                                <input type="text" class="form-control" name="position_{{ $lang->id }}[]" />
                            </div>

                            <div class="form-group col-md-4 col-sm-6">
                                <label>{{ __('URL') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="url_{{ $lang->id }}[]">
                                <div class="form-text text-info">{{ __("If you add URL, the link is opened, instead images gallery player.") }}</div>
                            </div>
                        </div>
                        <div class="mb-4"></div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            urlIndex_{{ $lang->id }} = 0;
                            $('#updateBlock')
                                // Add button click handler
                                .on('click', '.addButton_{{ $lang->id }}', function() {
                                    urlIndex_{{ $lang->id }}++;
                                    var $template = $('#ItemTemplate_{{ $lang->id }}'),
                                        $clone = $template
                                        .clone()
                                        .removeClass('hide')
                                        .removeAttr('id')
                                        .attr('data-block-index', urlIndex_{{ $lang->id }})
                                        .insertBefore($template);

                                    // Update the name attributes
                                    $clone
                                        .find('[name="title_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].title_{{ $lang->id }}').end()
                                        .find('[name="image_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].image_{{ $lang->id }}').end()
                                        .find('[name="caption_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].caption_{{ $lang->id }}').end()
                                        .find('[name="position_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].position_{{ $lang->id }}').end()
                                        .find('[name="url_{{ $lang->id }}"]').attr('name', 'updateBlock[' + urlIndex_{{ $lang->id }} + '].url_{{ $lang->id }}').end();
                                })

                        });
                    </script>

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
