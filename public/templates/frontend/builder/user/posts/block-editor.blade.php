@include('frontend.builder.user.posts.includes.trumbowyg-assets')

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <div class="user-header">
                    <i class="bi bi-pencil-square"></i> {{ __('Edit block') }} ({{ $block->type_label }})
                </div>
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
                <div class="form-group mb-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="hide" name="hide" @if ($block->hide ?? null) checked @endif>
                        <label class="form-check-label" for="hide">{{ __('Hide block') }}</label>
                    </div>
                    <div class="form-text">{{ __('Hidden blocks are not displayed on website') }}</div>
                </div>
            </div>

            <h5 class="mb-3">{{ __('Block content') }}:</h5>

            @foreach ($langs as $lang)
                @if (count(sys_langs()) > 1 && $block_module != 'posts')
                    <h5 class="mb-3">{!! flag($lang->code) !!} {{ $lang->name }}</h5>
                @endif

                <div class="form-group">
                    <textarea class="trumbowyg" name="content_{{ $lang->id }}">{{ $lang->block_content }}</textarea>
                </div>

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
                <button type="submit" class="btn btn-primary">{{ __('Update block') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
