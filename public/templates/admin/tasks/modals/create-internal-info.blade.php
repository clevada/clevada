<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="create-internal-info" aria-hidden="true" id="create-internal-info">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="create_ticket_internal_info">{{ __('Create internal info') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ __('Close') }}</span></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Message') }}</label>
                                <textarea class="form-control" name="message" rows="5" required></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Upload file') }} ({{ __('optional') }})</label> <br />
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="validatedCustomFile" name="file" aria-describedby="fileHelp">
                                    <label class="custom-file-label" for="validatedCustomFile">{{ __('Choose file') }}...</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create internal info') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>