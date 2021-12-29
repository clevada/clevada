<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="Update{{ $task->id }}" aria-hidden="true" id="reply-task-{{ $task->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.tasks.reply', ['id' => $task->id]) }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="Update{{ $task->id }}">{{ __('Add task activity') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">


                    <div class="form-group">
                        <label>{{ __('Message') }}</label>
                        <textarea class="form-control" name="message" rows="6" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="ReplyFile" class="form-label">{{ __('Attach file') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="file" id="ReplyFile" name="file">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Progress percent') }}</label>                                
                                <div class="input-group mb-3">                                    
                                    <input type="number" class="form-control" aria-describedby="basic-addon1" name="progress" value="{{ $task->progress }}">
                                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-percent"></i></span>
                                  </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="SwitchImportant" name="is_important">
                            <label class="form-check-label" for="SwitchImportant">{{ __('Flag as important') }}</label>
                        </div>
                        <div class="form-text">{{ __('If enabled, this activity is flagged as important') }}</div>
                    </div>

                    <div class="form-group mt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="SwitchInternal" name="visible_for_client">
                            <label class="form-check-label" for="SwitchInternal">{{ __('This activity is visible for client') }}</label>
                        </div>
                        <div class="form-text">{{ __('If enabled, this activity (message and attachment) is displayed in client page') }}</div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Add task activity') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
