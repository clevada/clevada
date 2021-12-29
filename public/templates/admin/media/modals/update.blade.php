<?php
debug_backtrace() || die('Direct access not permitted'); ?>

<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="update-{{ $item->id }}" aria-hidden="true" id="update-{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data" action="{{ route('admin.media.show', ['id' => $item->id]) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="col-12">
                        <div class="form-group">
                            <label for="formFile_{{ $item->id }}">{{ __('Change image') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="file" id="formFile_{{ $item->id }}" name="image">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
