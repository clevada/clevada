<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="add-account-note" aria-hidden="true" id="add-account-note">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="add-account-note">{{ __('Add internal note') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Internal note') }}</label>
                                <textarea class="form-control editor" name="note" rows="6" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Upload file') }} ({{ __('optional') }})</label>
                                <input type="file" class="form-control" name="file">
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sticky" name="sticky">
                                    <label class="form-check-label" for="sticky">{{ __('Sticky note') }}</label>
                                </div>
                            </div>                         
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Add internal note') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
