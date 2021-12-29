<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel{{ $module->id }}" aria-hidden="true" id="update-module-{{ $module->id }}">
    <div class="modal-dialog @if($module->have_frontend == 1) modal-lg @endif">
        <div class="modal-content">

            <form action="{{ route('admin.config.modules', ['id' => $module->id]) }}" method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel{{ $module->id }}">{{ __('Module status') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>{{ __('Module status') }}</label>
                                <select name="status" class="form-select">
                                    <option @if ($module->status == 'active') selected @endif value="active">{{ __('Active') }}</option>
                                    <option @if ($module->status == 'inactive') selected @endif value="inactive">{{ __('Inactive') }}</option>
                                    <option @if ($module->status == 'disabled') selected @endif value="disabled">{{ __('Disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @if($module->have_frontend == 1)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('SEO meta title') }}</label>
                                <input name="meta_title" class="form-control" value="{{ $module->meta_title }}">                                    
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('SEO meta description') }}</label>
                                <textarea rows="3" name="meta_description" class="form-control">{{ $module->meta_description }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>