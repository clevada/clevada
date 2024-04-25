<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" id="create-style">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">{{ __('Create style') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" required />
                            </div>
                        </div>                        

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Style') }}</label>
                                <select class="form-select" name="source_style_id">
                                    <option selected value="">{{ __('Create blank style') }}</option>
                                    @foreach ($customStyles as $selected_style)
                                        <option value="{{ $selected_style->id }}">{{ __('Duplicate style') }}: {{ $selected_style->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create style') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
