<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel{{ $form->id }}" aria-hidden="true" id="update-form-{{ $form->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.forms.config.show', ['id' => $form->id]) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel{{ $form->id }}">{{ __('Update form') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" value="{{ $form->label }}" required />
                                <div class="form-text">{{ __('Input a label to identify this form. Label is not visible in website') }}</div>
                                <div class="form-text">{{ __('You can add form custom fields at the next step') }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="SwitchreCAPTCHA_{{ $form->id }}" name="recaptcha" @if ($form->recaptcha) checked @endif>
                                    <label class="form-check-label" for="SwitchreCAPTCHA_{{ $form->id }}">{{ __('Enable Google reCAPTCHA antispam') }} </label>
                                    <div class="form-text"><a href="{{ route('admin.config.integration') }}">{{ __('Manage reCAPTCHA keys') }}</a></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch_{{ $form->id }}" name="active" @if ($form->active) checked @endif>
                                    <label class="form-check-label" for="customSwitch_{{ $form->id }}">{{ __('Active') }}</label>
                                </div>
                            </div>
                        </div>
                       
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update form') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
