<?php
debug_backtrace() || die('Direct access not permitted'); ?>

<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true" id="create">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Upload new image') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="formFile">{{ __('Choose image') }}</label>
                        <input class="form-control" type="file" id="formFile" name="image">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Upload new image') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
