<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" id="create-template">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">{{ __('Create template') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Template name') }}</label>
                                <input class="form-control" name="label" type="text" required />
                            </div>
                        </div>        
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>{{ __('Template source') }}</label>
                                <select class="form-select" name="source">
                                    <option value="">{{ __('Blank template') }}</option>
                                    @foreach ($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                           

                        <div class="form-text">
                            {{ __('If you choose an existing template source, all settings, blocks content and styles will be imported from selected template') }}.
                            {{ __('If you select blank template, you will start from scratch to build a new template') }}
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch" name="is_default">
                                    <label class="form-check-label" for="customSwitch">{{ __('Default template') }}</label>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create template') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
