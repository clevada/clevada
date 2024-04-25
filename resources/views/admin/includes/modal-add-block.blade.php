<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="addBlockLabel" aria-hidden="true" id="addBlock">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            @php
            if(($module ?? null) == 'pages') $action = route('admin.pages.content.new', ['id' => $page->id]);
            else if(($module ?? null) == 'posts') $action = route('admin.posts.content.new', ['id' => $post->id]);
            @endphp

            <form method="post" action="{{ $action ?? null }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="addBlockLabel">{{ __('Add block') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if(! ($module ?? null))
                    <div class="form-group col-lg-4 col-md-6">
                        <label class="form-label" for="blockLabel">{{ __('Block label') }}</label>
                        <input class="form-control" type="text" id="blockLabel" name="label" required>                
                    </div>
                    @endif

                    <p><b>{{ __('Click to add a block') }}</b>. {{ __('You can manage block content and settings at the next step') }}</p>

                    <div class="row">
                        @foreach (get_block_types() as $type)
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6 col-12 mb-4">
                                <input type="radio" name="type_id" class="radio input-hidden" id="block_{{ $type->id }}" value="{{ $type->id }}" required />
                                <label for="block_{{ $type->id }}">
                                    <div class='text-center'>
                                        <div class="fs-1">{!! $type->icon !!}</div>
                                        <div class="mb-2">
                                            {{ $type->label }}
                                        </div>                                       
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>
                
                <div class="modal-footer">
                    <input id="hidden_area_block" type="hidden" name="column" value="">
                    <input type="hidden" name="module" value="{{ $module ?? null }}">
                    <input type="hidden" name="content_id" value="{{ $content_id ?? null }}">
                    <button type="submit" class="btn btn-primary">{{ __('Add block') }}</button>
                </div>

            </form>

        </div>

    </div>

</div>
