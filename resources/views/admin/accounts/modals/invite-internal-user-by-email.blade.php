<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="inviteEmailLabel" aria-hidden="true" id="invite-internal-user-by-email">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" id="inviteInternalUser" action="{{ route('admin.accounts.send_invitation') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="inviteEmailLabel">{{ __('Send invite by email') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row bg-light mb-3 pt-3">
                        <div class="fw-bold mb-3">
                            <i class="bi bi-info-circle"></i> <a href="#">{{ __('Accounts roles help') }}</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Select role') }}</label>
                                <select name="role" class="form-select" required>
                                    <option value="">- {{ __('select') }} -</option>
                                    <option value="contributor">{{ __('Contributor') }}</option>
                                    <option value="author">{{ __('Author') }}</option>
                                    <option value="editor">{{ __('Editor') }}</option>
                                    <option disabled>_____________</option>
                                    <option value="developer">{{ __('Developer') }}</option>
                                    <option value="manager">{{ __('Manager') }}</option>
                                    <option value="admin">{{ __('Administrator') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Full name') }}</label>
                                <input class="form-control" name="name" type="text" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Valid email') }}</label>
                                <input class="form-control" name="email" type="email" required />
                                <div class="text-muted small">{{ __('Invitation will be sent to this email.') }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Additional notes') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="notes" rows="3"></textarea>
                                <div class="text-muted small form-text">{{ __('Notes are displayed in the invitation email') }}</div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="method" value="email">
                    <button type="submit" class="btn btn-primary">{{ __('Invite') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
