<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="modal-create-translate-key" aria-hidden="true" id="modal-create-translate-key">
    <div class="modal-dialog">
        <div class="modal-content">

        <form method="post" action="{{ route('admin.translates.create_key') }}">
                @csrf	

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-create-translate-key">{{ __('Add new translate key') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Translate key') }}</label>
                                <textarea rows="3" class="form-control" name="lang_key" required></textarea>
                            </div>
                        </div>                       
                                              
                    </div>                

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Add new translate key') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>