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

                <div class="form-group col-md-4 col-xl-3">
                    <label>{{ __('Select columns (number of testimonials per row)') }}</label>
                    <select class="form-select" name="cols">
                        <option @if (($block_extra['cols'] ?? null) == 2) selected @endif value="2">2</option>
                        <option @if (($block_extra['cols'] ?? null) == 3 || is_null($block_extra['cols'] ?? null)) selected @endif value="3">3</option>
                        <option @if (($block_extra['cols'] ?? null) == 4) selected @endif value="4">4</option>
                    </select>
                    <div class="form-text">{{ __('This is the maximum number of images per row for larger displays. For smaller displays, the columns are resized automatically.') }}</div>
                </div>

                <div class="form-group">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="use_image" name="use_image" @if ($block_extra['use_image'] ?? null) checked @endif>
                        <label class="form-check-label" for="use_image">{{ __('Use images (avatar)') }}</label>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="use_star_rating" name="use_star_rating" @if ($block_extra['use_star_rating'] ?? null) checked @endif>
                        <label class="form-check-label" for="use_star_rating">{{ __('Use star rating') }}</label>
                    </div>
                </div>            
            </div>

            <div class="form-group">
                <input type="hidden" name="type_id" value="{{ $block->type_id }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
            </div>

            <hr>

            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            @php
                $header_array = unserialize($block->header);
            @endphp


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
                    <textarea class="form-control" name="header_content">{{ $header_array['content'] ?? null }}</textarea>
                </div>
            </div>


            @php
                $content_array = unserialize($block->content);
            @endphp

            @if ($content_array)
                @for ($i = 0; $i < count($content_array); $i++)
                    <div class="card p-3 bg-light mb-4">
                        <div class="row">

                            <div class="form-group col-md-3 col-sm-6">
                                <label for="formFile" class="form-label">{{ __('Image') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="file" id="formFile" name="image[]" multiple>
                                <div class="text-muted small">{{ __('Image file. Maximum 2.5 MB.') }}</div>

                                @if ($content_array[$i]['image'] ?? null)
                                    <a target="_blank" href="{{ image($content_array[$i]['image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($content_array[$i]['image']) }}"
                                            class="img-fluid mt-2"></a>
                                    <input type="hidden" name="existing_image[{{ $i }}]" value="{{ $content_array[$i]['image'] ?? null }}">

                                    <div class="form-group mb-0">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="delete_image_file_code_{{ $i }}" value="{{ $content_array[$i]['image'] ?? null }}">
                                            <input class="form-check-input" type="checkbox" id="delete_image_{{ $i }}" name="delete_image_{{ $i }}">
                                            <label class="form-check-label" for="delete_image_{{ $i }}">{{ __('Delete image') }}</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>{{ __('Author name') }} ({{ __('required') }})</label>
                                <input type="text" class="form-control" name="name[]" value="{{ $content_array[$i]['name'] ?? null }}">
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>{{ __('Subtitle') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="subtitle[]" value="{{ $content_array[$i]['subtitle'] ?? null }}">
                            </div>

                            <div class="form-group col-md-3 col-sm-6">
                                <label>{{ __('Rating') }}</label>
                                <select class="form-select" name="rating[]">
                                    <option @if (($content_array[$i]['rating'] ?? null) == 5) selected @endif value="5">5</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 4.5) selected @endif value="4.5">4.5</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 4) selected @endif value="4">4</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 3.5) selected @endif value="3.5">3.5</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 3) selected @endif value="3">3</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 2.5) selected @endif value="2.5">2.5</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 2) selected @endif value="2">2</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 1.5) selected @endif value="1.5">1.5</option>
                                    <option @if (($content_array[$i]['rating'] ?? null) == 1) selected @endif value="1">1</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label>{{ __('Testimonial') }}</label>
                                <textarea class="form-control" type="text" rows="4" name="testimonial[]">{{ $content_array[$i]['testimonial'] ?? null }}</textarea>
                            </div>

                            <div class="form-group col-md-2 col-sm-6">
                                <label>{{ __('Position') }}</label>
                                <input type="number" min="1" step="1" class="form-control" name="position[]" value="{{ $content_array[$i]['position'] ?? null }}">
                            </div>

                        </div>
                    </div>

                    <div class="mb-4"></div>
                @endfor
            @endif

            <div class="mb-3 mt-3">
                <button type="button" class="btn btn-gear addButton"><i class="bi bi-plus-circle"></i> {{ __('Add testimonial') }} </button>
            </div>

            <!-- The template for adding new item -->
            <div class="form-group hide" id="ItemTemplate">
                <div class="row">
                    
                    <div class="form-group col-md-4 col-sm-6">
                        <label for="formFile" class="form-label">{{ __('Author avatar') }} ({{ __('optional') }})</label>
                        <input class="form-control" type="file" id="formFile" name="image[]" multiple>
                        <div class="text-muted small">{{ __('Image file. Maximum 2.5 MB.') }}</div>                       
                    </div>

                    <div class="form-group col-md-3 col-sm-6">
                        <label>{{ __('Author name') }} ({{ __('required') }})</label>
                        <input type="text" class="form-control" name="name[]" />
                    </div>

                    <div class="form-group col-md-3 col-sm-6">
                        <label>{{ __('Subtitle') }} ({{ __('optional') }})</label>
                        <input type="text" class="form-control" name="subtitle[]">
                    </div>

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('Rating') }}</label>
                        <select class="form-select" name="rating[]">
                            <option value="5">5</option>
                            <option value="4.5">4.5</option>
                            <option value="4">4</option>
                            <option value="3.5">3.5</option>
                            <option value="3">3</option>
                            <option value="2.5">2.5</option>
                            <option value="2">2</option>
                            <option value="1.5">1.5</option>
                            <option value="1">1</option>
                        </select>
                    </div>

                    <div class="form-group col-12">
                        <label>{{ __('Testimonial') }} ({{ __('optional') }})</label>
                        <textarea class="form-control" type="text" rows="4" name="testimonial[]"></textarea>
                    </div>

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('Position') }}</label>
                        <input type="number" min="1" step="1" class="form-control" name="position[]" />                        
                    </div>
                </div>
                <hr>
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
                                .find('[name="name"]').attr('name', 'updateBlock[' + urlIndex + '].name').end()
                                .find('[name="subtitle"]').attr('name', 'updateBlock[' + urlIndex + '].subtitle').end()
                                .find('[name="image"]').attr('name', 'updateBlock[' + urlIndex + '].image').end()
                                .find('[name="testimonial"]').attr('name', 'updateBlock[' + urlIndex + '].testimonial').end()
                                .find('[name="position"]').attr('name', 'updateBlock[' + urlIndex + '].position').end()
                                .find('[name="rating"]').attr('name', 'updateBlock[' + urlIndex + '].rating').end();
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
