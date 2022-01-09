<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLayoutBlockLabel" aria-hidden="true" id="createLayoutBlock">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.docs.content.new', ['id' => $doc->id]) }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createLayoutBlockLabel">{{ __('Add block content') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <p>{{ __('Click to add a content block') }}</p>

                    <div class="row">
                        @foreach ($block_types as $block)
                            <div class="col-md-3 col-sm-6 col-12 mb-4">
                                <input type="radio" name="block_type_id" class="radio input-hidden" id="block_{{ $block->id }}" value="{{ $block->id }}" required onclick="change_block_option()" />
                                <label for="block_{{ $block->id }}">
                                    <div class='text-center'>
                                        <div class="fs-2">{!! $block->icon !!}</div>
                                        <div class="mb-2 fs-5">
                                            {{ $block->label }}
                                        </div>
                                        <div>
                                            <div class="form-text">{{ $block->description }}</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>                

                </div>

                <div class="modal-footer">
                    <input id="hidden_area" type="hidden" name="column" value="">
                    <button type="submit" class="btn btn-primary">{{ __('Add block') }}</button>
                </div>

            </form>

        </div>

    </div>

</div>

