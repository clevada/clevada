<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel{{ $section->id }}" aria-hidden="true" id="update-global-section-{{ $section->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.template.global_sections.show', ['id' => $section->id]) }}">
                @csrf                
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel{{ $section->id }}">{{ __('Update section') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" value="{{ $section->label }}" required />
                            </div>
                        </div>                        

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update section') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
