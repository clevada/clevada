@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.templates') }}">{{ __('Templates') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage footer block') }}</li>
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
                    <h4 class="card-title">{{ __('Edit block') }} ({{ $block->type_label }})</h4>
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
                                <input class="form-control" type="text" name="header_title_{{ $lang->id }}" value="{{ $header_array['title'] ?? null }}">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Header description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control trumbowyg" rows="2" name="header_content_{{ $lang->id }}">{{ $header_array['content'] ?? null }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea class="trumbowyg" name="content_{{ $lang->id }}">{{ $lang->block_content }}</textarea>
                    </div>

                    <div class="mb-4"></div>

                    @if (count(sys_langs()) > 1 && !$loop->last)<hr>@endif

                @endforeach

                <div class="form-group">
                    <input type="hidden" name="type_id" value="{{ $block->type_id }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update block') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
