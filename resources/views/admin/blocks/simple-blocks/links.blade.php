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

                <div class="form-group">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="hide" name="hide" @if ($block->hide ?? null) checked @endif>
                        <label class="form-check-label" for="hide">{{ __('Hide block') }}</label>
                    </div>
                    <div class="form-text">{{ __('Hidden blocks are not displayed on website') }}</div>
                </div>

                <div class="form-group col-md-4 col-xl-3 mb-3">
                    <label>{{ __('Links display style') }}</label>
                    <select class="form-select" name="display_style">
                        <option @if (($block_extra['display_style'] ?? null) == 'list') selected @endif value="list">{{ __('Ordered list (one link per line)') }}</option>
                        <option @if (($block_extra['display_style'] ?? null) == 'multiple') selected @endif value="multiple">{{ __('One after another') }}</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="new_tab" name="new_tab" @if ($block_extra['new_tab'] ?? null) checked @endif>
                        <label class="form-check-label" for="new_tab">{{ __('Open links in new tab') }}</label>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

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
                        <input class="form-control" type="text" name="header_title" value="{{ $header_array['title'] ?? null }}">
                    </div>

                    <div class="form-group">
                        <label>{{ __('Header description') }} ({{ __('optional') }})</label>
                        <textarea class="form-control trumbowyg" rows="2" name="header_content">{{ $header_array['content'] ?? null }}</textarea>
                    </div>
                </div>
            </div>

            @php
                $content_array = unserialize($block->content ?? null);
            @endphp

            @if ($content_array)
                @for ($i = 0; $i < count($content_array); $i++)
                    <div class="row">
                        <div class="col-md-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Link title') }}</label>
                                <input type="text" class="form-control" name="a_title[]" value="{{ $content_array[$i]['title'] ?? null }}">
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>{{ __('URL') }}</label>
                                <input type="text" class="form-control" name="a_url[]" value="{{ $content_array[$i]['url'] ?? null }}">
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Icon code') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="a_icon[]" value="{{ $content_array[$i]['icon'] ?? null }}">
                            </div>
                        </div>
                    </div>
                @endfor
            @endif

            <div class="mb-3">
                <button type="button" class="btn btn-light addButton"><i class="bi bi-plus-circle"></i> {{ __('Add item') }} </button>
            </div>

            <!-- The template for adding new item -->
            <div class="form-group hide" id="ItemTemplate">
                <div class="row">
                    <div class="col-md-5 col-sm-6 col-12">
                        <div class="form-group">
                            <label>{{ __('Link title') }}</label>
                            <input type="text" class="form-control" name="a_title[]" />
                        </div>
                    </div>

                    <div class="col-md-5 col-sm-6 col-12">
                        <div class="form-group">
                            <label>{{ __('URL') }}</label>
                            <input type="text" class="form-control" name="a_url[]" />
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="form-group">
                            <label>{{ __('Icon code') }} ({{ __('optional') }})</label>
                            <input type="text" class="form-control" name="a_icon[]" />
                        </div>
                    </div>
                </div>
                <div class="mb-3"></div>
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
                                .attr('data-proforma-index', urlIndex)
                                .insertBefore($template);

                            // Update the name attributes
                            $clone
                                .find('[name="a_title"]').attr('name', 'updateBlock[' + urlIndex + '].a_title').end()
                                .find('[name="a_url"]').attr('name', 'updateBlock[' + urlIndex + '].a_url').end()
                                .find('[name="a_icon"]').attr('name', 'updateBlock[' + urlIndex + '].a_icon').end();
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
