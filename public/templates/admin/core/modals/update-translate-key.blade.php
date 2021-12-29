<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="modal-update-translate-key-{{ $lang_key->id }}" aria-hidden="true" id="modal-update-translate-key-{{ $lang_key->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

        <form method="post" action="{{ route('admin.translates.update_key', ['locale' => $locale]) }}">
                @csrf	

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-update-translate-key-{{ $lang_key->id }}">{{ __('Update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Translate key') }}</label>
                                <textarea rows="3" class="form-control" name="lang_key" required>{{ $lang_key->lang_key }}</textarea>
                            </div>
                        </div>                        
                                              
                    </div>                

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" value="{{ $lang_key->id }}">
                    <input type="hidden" name="lang_id" value="{{ $lang->id }}">              
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>