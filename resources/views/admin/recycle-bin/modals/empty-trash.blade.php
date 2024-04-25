<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="empty-trashLabel" aria-hidden="true" id="empty-trash">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.recycle_bin.empty', ['module' => $module ?? 'all']) }}">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title" id="empty-trashLabel">{{ __('Empty trash') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="fw-bold mb-2">{{ __('Are you sure you want to empty trash?') }}</div>

                    <i class="bi bi-info-circle"></i> {{ __('All messages from trash will be permanantly deleted.') }}
                </div>

                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary me-2">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Empty trash') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
