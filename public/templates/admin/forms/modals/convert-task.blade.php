<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" id="convert-task">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.forms.create-task', ['id' => $message->id]) }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">{{ __('Convert into task') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Task title') }}</label>
                                <input class="form-control" name="title" type="text" required value="{{ __('Form') }}: {{ $message->subject }}" />
                            </div>

                            <div class="form-group">
                                <label>{{ __('Task description') }}</label>
                                <textarea class="form-control" name="description" rows="3">{{ __('Task generated from form message') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Priority') }} </label>
                                <select class="form-select" name="priority">
                                    <option value="3">{{ __('Low') }}</option>
                                    <option value="2" selected>{{ __('Normal') }}</option>
                                    <option value="1">{{ __('High') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Due date') }} </label>
                                <input class="form-select" type="date" name="due_date">                                    
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="SwitchLink" name="allow_access">
                                    <label class="form-check-label" for="SwitchLink">{{ __('Create access link') }}</label>
                                </div>
                                <div class="form-text">
                                    {{ __('Create an access URL and share with destinatar (client). Destinatar will have access to some task details and see task progress.') }}
                                </div>
                            </div>
                        </div>

                        @if($message->email)
                        <div class="col-12">
                            <div class="form-group mb-0 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="SwitchEmail" name="send_email">
                                    <label class="form-check-label" for="SwitchEmail">{{ __('Send email with details to destinatar') }}</label>
                                </div>
                                <div class="form-text">{{ __('Send email to destinatar with task details and link access.') }}
                                    <br>
                                    {{ __('Destinatar email') }}: <b>{{ $message->email }}</b>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Convert into task') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
