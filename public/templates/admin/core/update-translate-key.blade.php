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
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ __('Close') }}</span></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Translate key') }}</label>
                                <input type="text" class="form-control" name="lang_key" required value="{{ $lang_key->lang_key }}" />
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