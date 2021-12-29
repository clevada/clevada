<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="update-report-{{ $report->id }}" aria-hidden="true" id="update-report-{{ $report->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.forum.reports.post.update', ['id' => $report->id]) }}" method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Manage report') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ __('Close') }}</span></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Give a warning (visible to author)') }} - {{ __('optional') }}</label>
                                <textarea class="form-control" name="warning" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Deny author to create new topics') }}</label>
                                <select name="deny_topic_create_days" class="form-control">
                                    <option selected value="">{{ __('No') }}</option>
                                    <option value="3">3 {{ __('days') }}</option>
                                    <option value="15">15 {{ __('days') }}</option>
                                    <option value="30">30 {{ __('days') }}</option>
                                    <option value="60">60 {{ __('days') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Deny author to create posts') }}</label>
                                <select name="deny_post_create_days" class="form-control">
                                    <option selected value="">{{ __('No') }}</option>
                                    <option value="3">3 {{ __('days') }}</option>
                                    <option value="15">15 {{ __('days') }}</option>
                                    <option value="30">30 {{ __('days') }}</option>
                                    <option value="60">60 {{ __('days') }}</option>
                                </select>
                            </div>                           

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="checkbox_delete_post" type="checkbox" name="delete_post" class="custom-control-input">
                                    <label for="checkbox_delete_post" class="custom-control-label text-danger"> {{ __('Delete post') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Add internal notes for this user (visible to staff only)') }} - {{ __('optional') }}</label>
                                <textarea class="form-control" name="internal_notes" rows="3"></textarea>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>