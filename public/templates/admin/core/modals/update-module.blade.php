<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel{{ $module->id }}" aria-hidden="true" id="update-module-{{ $module->id }}">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="updateLabel{{ $module->id }}">{{ __('Module status') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="alert alert-light">
                    <b>{{ __('Active modules') }}</b>: {{ __('Module is enabled for visitors and registered users') }}.
                    <br>
                    <b>{{ __('Inactive modules') }}</b>: {{ __('Administrators and staff (with module permission) have access to manage module and add content, but module is disabled on website') }}.
                    <br>
                    <b>{{ __('Disabled modules') }}</b>: {{ __('Module is disabled and it is not displayed in admin or staff area') }}.                  
                </div>

                <form action="{{ route('admin.config.general', ['id' => $module->id]) }}" method="post">
                    @csrf

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

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
