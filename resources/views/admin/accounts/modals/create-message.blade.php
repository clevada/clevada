<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="create-message" aria-hidden="true" id="create-message">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.account.messages.create', ['id' => $account->id]) }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="create_user_message">{{ __('Send new message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Subject') }}</label>
                                <input name="subject" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Message') }}</label>
                                <textarea name="message" class="form-control editor"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Priority') }}</label>
                                <select name="priority" class="form-control">
                                    <option value="0" selected>{{ __('Normal') }}</option>
                                    <option value="1">{{ __('Important') }}</option>
                                    <option value="2">{{ __('Urgent') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Upload file') }} ({{ __('optional') }})</label> <br />
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="validatedCustomFile" name="file">
                                    <label class="custom-file-label" for="validatedCustomFile">{{ __('Choose file') }}...</label>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Send new message') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>