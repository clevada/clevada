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

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This block exists') }} @endif
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-info">
                    @if ($message == 'image_deleted') {{ __('Image deleted') }} @endif
                </div>
            @endif
            
            @php 
            if(($is_footer_block ?? null) == 1) 
            $action = route('admin.template.footer.block', ['id' => $block->id]);            
            else
            $action = route('admin.blocks.show', ['id' => $block->id]);
            @endphp 

            <form id="updateBlock" method="post" enctype="multipart/form-data" action="{{ $action }}">
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

                    <div class="row">
                        <div class="form-group col-md-3 col-sm-6">
                            <label for="formFile" class="form-label">{{ __('Change image') }}</label>
                            <input class="form-control" type="file" id="formFile" name="image_{{ $lang->id }}">
                            @if ($content_array['image'] ?? null)
                                <div class="mt-3"></div>
                                <a target="_blank" href="{{ image($content_array['image']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($content_array['image']) }}" class="img-fluid"></a>
                                <input type="hidden" name="existing_image_{{ $lang->id }}" value="{{ $content_array['image'] ?? null }}">
                            @endif
                        </div>

                        <div class="form-group col-md-3 col-sm-6">
                            <label>{{ __('Title (used as "title" and "alt" tag)') }}</label>
                            <input class="form-control" type="text" name="title_{{ $lang->id }}" value="{{ $content_array['title'] ?? null }}">
                        </div>

                        <div class="form-group col-md-3 col-sm-6">
                            <label>{{ __('Caption') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="text" name="caption_{{ $lang->id }}" value="{{ $content_array['caption'] ?? null }}">                            
                        </div>

                        <div class="form-group col-md-3 col-sm-6">
                            <label>{{ __('URL') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="text" name="url_{{ $lang->id }}" value="{{ $content_array['url'] ?? null }}">
                        </div>
                    </div>                   

                    <div class="mb-4"></div>

                    @if (count(sys_langs()) > 1 && ! $loop->last)<hr>@endif
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
