<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="modalAdminLabel" aria-hidden="true" id="create-admin">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data" id="createAdminForm" action="{{ route('admin.accounts.send_invitation') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">{{ __('Invite administrator') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row bg-light mb-3 pt-3">
                        <div class="fw-bold mb-2 text-danger">
                            <i class="bi bi-info-circle"></i>
                            {{ __('You will send an invitation by email. This person can create an adminsnistrator account. Administrators have full access to all modules and configurations. Be careful to invite only trusted persons as administrators') }}.
                        </div>
                    </div>


                    <div class="row">
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
                    <input type="hidden" name="role" value="admin">
                    <input type="hidden" name="method" value="email">
                    <button type="submit" class="btn btn-primary">{{ __('Invite administrator') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
