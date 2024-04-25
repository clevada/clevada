<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="inviteBulkEmailLabel" aria-hidden="true" id="invite-internal-user-bulk">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" id="inviteInternalUserBulk" action="{{ route('admin.accounts.send_invitation') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="inviteBulkEmailLabel">{{ __('Invite multiple users') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row bg-light mb-3 pt-3">
                        <div class="fw-bold mb-3">
                            <i class="bi bi-info-circle"></i> <a href="#">{{ __('Accounts roles help') }}</a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Select role') }}</label>
                            <select name="role" class="form-select" required>
                                <option value="">- {{ __('select') }} -</option>
                                <option value="contributor">{{ __('Contributor') }}</option>
                                <option value="author">{{ __('Author') }}</option>
                            </select>
                            <div class="text-muted small mt-1">{{ __('Only contributors or authors can be invite in bulk.') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Enter email addresses of persons you want to invite. Separate multiple persons by comma or new line') }}</label>
                            <textarea class="form-control" name="persons" rows="10"></textarea>
                            <div class="text-muted small form-text">{{ __('Example: person1@yahoo.com, person2@gmail.com, .... (or each email on a new line)') }}</div>

                            <div class="text-muted form-text"><i class="bi bi-info-circle"></i> {{ __('Duplicate emails will be ignored. Existing emails will be ignored.') }}</div>
                            <div class="text-info form-text"><i class="bi bi-exclamation-circle"></i> {{ __('Maximum 100 valid emails are processed. If you have more persons to add, you must repeat the process.') }}
                            </div>
                        </div>
                    </div>
                    {{--
                    <div class="form-group col-md-6">
                        <label class="form-label">{{ __('Email language') }}</label>
                        <select class="form-select" name="mail_lang">
                            @foreach (config('nura.langs') as $lang)
                                <option value="{{ $lang['code']}}">{{ $lang['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    --}}
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="role" value="internal">
                    <input type="hidden" name="method" value="bulk">
                    <button type="submit" class="btn btn-primary">{{ __('Invite') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
