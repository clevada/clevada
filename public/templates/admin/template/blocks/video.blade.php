<!-- Color picker -->
<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">

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

            <form method="post">
                @csrf
                @method('PUT')

                @php
                    $block_extra = unserialize($block->extra);
                @endphp

                @foreach ($langs as $lang)

                    <h5 class="mb-3">{{ __('Block content') }} 
                        @if (count(sys_langs()) > 1)- {{ $lang->name }} @if ($lang->is_default) ({{ __('default language') }})@endif @endif
                    </h5>

                    @php
                        $content_array = unserialize($lang->block_content);
                    @endphp

                    <div class="form-group">
                        <label>{{ __('Insert video embed code') }}</label>
                        <textarea class="form-control" rows="6" name="embed_{{ $lang->id }}">{{ $content_array['embed'] ?? null }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                        <input class="form-control" type="text" name="caption_{{ $lang->id }}" value="{{ $content_array['caption'] ?? null }}">
                    </div>

                    <div class="mb-4"></div>

                    @if (count(sys_langs()) > 1 && ! $loop->last)<hr>@endif

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
