@include('tenant.admin.includes.trumbowyg-assets')
@include('tenant.admin.includes.color-picker')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts') }}">{{ __('Posts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-3">
                @include('tenant.admin.posts.includes.menu-post')
            </div>

            <div class="col-12">
                <div class="float-end">
                    @if ($post->status == 'published')
                        <a target="_blank" href="{{ route('post', ['categ_slug' => $post->categ_slug, 'slug' => $post->slug]) }}" class="btn btn-sm btn-secondary"><i class="bi bi-box-arrow-up-right"></i>
                            {{ __('Preview') }}</a>
                    @endif
                </div>

                @if ($post->status != 'published')
                    <div class="fw-bold text-danger mt-1 mb-2">
                        <i class="bi bi-exclamation-circle"></i> {{ __('Post is not published. Go to post details to publish this post.') }}
                        <a href="{{ route('admin.posts.show', ['id' => $post->id]) }}">{{ __('Post details') }}</a>
                    </div>
                    <div class="clearfix"></div>
                @endif

                <div class="fw-bold fs-5">
                    {{ $post->title }}
                </div>

            </div>

            <div class="clearfix"></div>

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

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif


        <form method="post" enctype="multipart/form-data">
            @csrf
            @foreach ($sections as $section)
                <div class="fw-bold fs-5">{{ $section->label }}</div>

                @foreach ($section->specs as $spec)
                    <label>{{ $spec->label }}</label>

                    @if ($spec->type == 'textarea')
                        <textarea rows="4" name="{{ $spec->id }}" class="form-control">{{ unserialize($post->cf_array)[$section->label][$spec->label] ?? null }}</textarea>
                    @elseif($spec->type == 'bool')
                        <div class="col-md-4 col-lg-2 col-xl-1">
                            <select name="{{ $spec->id }}" class="form-select">
                                <option value="">-- {{ __('select') }} --</option>
                                <option @if ((unserialize($post->cf_array)[$section->label][$spec->label] ?? null) == 'yes') selected @endif value="yes">{{ __('Yes') }}</option>
                                <option @if ((unserialize($post->cf_array)[$section->label][$spec->label] ?? null) == 'no') selected @endif value="no">{{ __('No') }}</option>
                            </select>
                        </div>
                    @elseif($spec->type == 'multiple')
                        <div class="col-md-4 col-lg-3">
                            <select name="{{ $spec->id }}" class="form-select">
                                <option value="">-- {{ __('select') }} --</option>
                                @foreach (get_post_cf_options($section->id, $post->lang_id) as $option)
                                    <option @if ((unserialize($post->cf_array)[$section->label][$spec->label] ?? null) == $option->id) selected @endif value="{{ $option->id }}">{{ $option->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif ($spec->type == 'editor')
                        <textarea name="{{ $spec->id }}" class="form-control trumbowyg">{{ unserialize($post->cf_array)[$section->label][$spec->label] ?? null }}</textarea>
                    @elseif ($spec->type == 'color')                        
                        <div id="hidden_div_color_{{ $spec->id }}" style="display: @if ((unserialize($post->cf_array)[$section->label][$spec->label] ?? null) == 'multicolor') none @else block @endif" class="mt-2">
                            <div class="form-group">
                                <input class="form-control form-control-color" id="color_{{ $spec->id }}" name="{{ $spec->id }}"
                                    value="{{ unserialize($post->cf_array)[$section->label][$spec->label] ?? '#cccccc' }}">
                                <script>
                                    $('#color_{{ $spec->id }}').spectrum({
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

                        <div class="form-group mb-0 mt-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" value="multicolor" type="checkbox" id="multicolor_{{ $spec->id }}" name="{{ $spec->id }}" @if ((unserialize($post->cf_array)[$section->label][$spec->label] ?? null) == 'multicolor') checked @endif>
                                <label class="form-check-label" for="multicolor_{{ $spec->id }}">{{ __('Multicolor') }}</label>
                            </div>
                        </div>
                        <script>
                            $('#multicolor_{{ $spec->id }}').change(function() {
                                select = $(this).prop('checked');
                                if (select) {
                                    document.getElementById('hidden_div_color_{{ $spec->id }}').style.display = 'none';
                                    document.getElementById('color_{{ $spec->id }}').value = 'multicolor';
                                } else
                                    document.getElementById('hidden_div_color_{{ $spec->id }}').style.display = 'block';
                            })
                        </script>
                    @elseif($spec->type == 'url')
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="url-addon-{{ $spec->id }}">https://</span>
                            <input type="text" class="form-control" id="url-{{ $spec->id }}" aria-describedby="url-addon-{{ $spec->id }}" name="{{ $spec->id }}"
                                value="{{ unserialize($post->cf_array)[$section->label][$spec->label] ?? null }}">
                        </div>
                    @else
                        <input type="text" name="{{ $spec->id }}" class="form-control" value="{{ unserialize($post->cf_array)[$section->label][$spec->label] ?? null }}">
                    @endif

                    <div class="mb-3"></div>
                @endforeach

                <div class="mb-4"></div>
            @endforeach

            <hr>
            <button type="submit" class="btn btn-primary"> {{ __('Update') }}</button>
        </form>

    </div>
    <!-- end card-body -->

</div>
