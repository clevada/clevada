<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="inviteLinkInternalUser" aria-hidden="true" id="invite-internal-user-by-link">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.accounts.send_invitation') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="inviteLinkInternalUser">{{ __('Send invite by link') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row bg-light mb-3 pt-3">
                        <div class="fw-bold mb-3">
                            <i class="bi bi-info-circle"></i> <a href="#">{{ __('Accounts roles help') }}</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-lg-4">
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
                            <div class="form-group mb-3">
                                <label>{{ __('Invitation link') }}</label>
                                <div class="input-group col-md-8 mb-2">
                                    @php
                                        use Illuminate\Support\Str;
                                        $inviteCode = Str::random(36);
                                        $inviteLink = route('home') . '/action/invite?token=' . $inviteCode;
                                    @endphp

                                    <input type="text" name="inviteLink" value="{{ $inviteLink }}" class="form-control" id="inviteLink" aria-label="link" />
                                    <div id="clipboardCopycreatedLinkButton2" class="input-group-text" title="{{ __('Click to copy') }}" style="cursor: pointer" onclick="copyInviteLinkToClipboard()">
                                        <i class="bi bi-clipboard me-1"></i> {{ __('Copy link') }}
                                    </div>
                                </div>

                                <div class="text-muted small"><i class="bi bi-info-circle"></i> {{ __('Link can be used one time only. Invite link expire in 3 days.') }}</div>

                                <div id="clipboardcreatedLinkCopied" class="mt-1 mb-2 fw-bold text-success" style="display: none">{{ __('Link copied. Click on "Create invitation link" to save this invitation link.') }}
                                </div>

                            </div>
                        </div>
                    </div>

                    <script>
                        function copyInviteLinkToClipboard() {

                            const element = document.querySelector('#inviteLink');
                            element.select();
                            element.setSelectionRange(0, 99999);
                            document.execCommand('copy');

                            var copiedDiv = document.getElementById('clipboardcreatedLinkCopied');
                            copiedDiv.style.display = 'block';
                        }
                    </script>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="role" value="internal">
                    <input type="hidden" name="method" value="link">
                    <input type="hidden" name="inviteCode" value="{{ $inviteCode }}">
                    <button type="submit" class="btn btn-primary">{{ __('Create invitation link') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
