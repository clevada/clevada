<link rel="stylesheet" href="{{ clevada_asset('assets/vendors/prism/prism.css') }}">
<link rel="stylesheet" href="{{ clevada_asset('assets/vendors/prism/prism-live.css') }}">

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

            @php 
            if(($is_footer_block ?? null) == 1) 
            $action = route('admin.template.footer.block', ['id' => $block->id]);            
            else
            $action = route('admin.blocks.show', ['id' => $block->id]);
            @endphp 

            <form id="updateBlock" method="post" action="{{ $action }}">
                @csrf
                @method('PUT')
                            
                @foreach ($langs as $lang)

                    <h5 class="mb-3">{{ __('Block content') }} @if (count(sys_langs()) > 1 && $block_module != 'posts')-
                            {{ $lang->name }} @if ($lang->is_default)
                                ({{ __('default language') }})@endif
                        @endif
                    </h5>

                    <textarea name="content_{{ $lang->id }}" class="prism-live line-numbers language-html fill">{{ $lang->block_content }}</textarea>

                    <div class="mb-4"></div>

                    @php
                    if($block_module == 'posts') break;
                    @endphp
                    
                    @if (count(sys_langs()) > 1)<hr>@endif

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

<script src="{{ clevada_asset('assets/vendors/prism/bliss.shy.min.js') }}"></script>
<script src="{{ clevada_asset('assets/vendors/prism/prism.js') }}"></script>
<script src="{{ clevada_asset('assets/vendors/prism/prism-line-numbers.js') }}"></script>
<script src="{{ clevada_asset('assets/vendors/prism/prism-live.js') }}"></script>
<script src="{{ clevada_asset('assets/vendors/prism/prism-live-markup.js') }}"></script>
<script src="{{ clevada_asset('assets/vendors/prism/prism-live-css.js') }}"></script>
<script src="{{ clevada_asset('assets/vendors/prism/prism-live-javascript.js') }}"></script>
