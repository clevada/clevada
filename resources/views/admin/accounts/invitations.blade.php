<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Accounts invitations') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                <h4 class="card-title">
                    {{ __('Invitations') }} ({{ $invitations->total() ?? 0 }})
                </h4>
            </div>

            <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    <div class="dropdown float-end ms-3">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-plus-circle"></i> {{ __('Add internal user') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="min-width: 200px;">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#invite-internal-user-by-email">{{ __('Invite user by email') }}</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#invite-internal-user-by-link">{{ __('Invite user by link') }}</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#invite-internal-user-bulk">{{ __('Bulk invitation') }}</a></li>
                        </ul>
                    </div>

                    @include('admin.accounts.modals.invite-internal-user-by-email')
                    @include('admin.accounts.modals.invite-internal-user-by-link')
                    @include('admin.accounts.modals.invite-internal-user-bulk')
                </div>
            </div>

        </div>

    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'created')
                    @if (Session::get('counter') == 0)
                        {{ __('No invitation has been sent') }}
                    @elseif (Session::get('counter') == 1)
                        {{ __('Invitation was sent') }}
                    @else
                        {{ Session::get('counter') }} {{ __('invitations sent') }}
                    @endif
                @endif
                @if ($message == 'deleted')
                    {{ __('Invitation deleted') }}
                @endif
                @if ($message == 'resent')
                    {{ __('Invitation resent') }}
                @endif
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate_email')
                    {{ __('Error. There is another invitation or existing account with this email') }}
                @endif
                @if ($message == 'duplicate_code')
                    {{ __('Error. This code was used in the past') }}
                @endif
            </div>
        @endif

        <section>
            <form action="{{ route('admin.accounts.invitations') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                <div class="col-12">
                    <input type="text" name="search_terms" placeholder="Search user" class="form-control @if ($search_terms) is-valid @endif" value="{{ $search_terms ?? null }}" />
                </div>

                <div class="col-12">
                    <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                    <a title="{{ __('Reset') }}" class="btn btn-light" href="{{ route('admin.accounts.invitations') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

            </form>
        </section>

        <div class="mb-3"></div>

        <div class="table-responsive-md">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="180">{{ __('Invitation method') }}</th>
                        <th>{{ __('Details') }}</th>
                        <th width="180">{{ __('Role') }}</th>
                        <th width="140">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($invitations as $invitation)
                        <tr @if ($invitation->user_id) class="table-light" @endif>

                            <td>
                                @if ($invitation->method == 'email')
                                    <i class="bi bi-envelope"></i> {{ __('Email invitation') }}
                                @endif
                                @if ($invitation->method == 'link')
                                    <i class="bi bi-link-45deg"></i> {{ __('Invite via link') }}
                                @endif
                            </td>

                            <td>
                                @if ($invitation->user_id)
                                    <span class="float-end ms-2 badge bg-success">{{ __('Invitation accepted') }}</span>
                                @endif

                                @if ($invitation->method == 'email')
                                    <div class="fw-bold">
                                        @if (!$invitation->user_id)
                                            {{ $invitation->name }}
                                        @else
                                            <a href="{{ route('admin.accounts.show', ['id' => $invitation->user_id]) }}">{{ $invitation->name }}</a>
                                        @endif
                                    </div>
                                    {{ $invitation->email }}
                                @endif

                                @if ($invitation->method == 'link')
                                    <span class="fw-bold">{{ __('Invitation link') }}:</span>
                                    <input type="text" class="form-control mb-2" value="{{ route('home') }}/action/invite?token={{ $invitation->code }}">
                                @endif

                                <div class="small text-muted">
                                    {{ __('Sent at') }}: {{ date_locale($invitation->sent_at, 'datetime') }} |
                                    {{ __('Opened at') }}: @if ($invitation->open_at)
                                        {{ date_locale($invitation->open_at, 'datetime') }}
                                    @else
                                        <span class="text-danger">{{ __('never') }}</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if ($invitation->role == 'internal')
                                    <div class="fw-bold text-success"><i class="bi bi-person-workspace"></i> {{ __('Internal user') }}</div>
                                @endif
                                @if ($invitation->role == 'admin')
                                    <div class="fw-bold text-danger"><i class="bi bi-person-workspace"></i> {{ __('Administrator') }}</div>
                                @endif
                                @if ($invitation->role == 'external')
                                    <i class="bi bi-box-arrow-up-right"></i> {{ __('External user') }}
                                @endif
                            </td>

                            <td>
                                <div class="d-grid gap-2">

                                    @if (!$invitation->user_id)
                                        @if ($invitation->method == 'email')
                                            <a class="btn btn-primary btn-sm" href="#" data-bs-toggle="modal" data-bs-target=".confirm-resend-{{ $invitation->id }}">{{ __('Resend') }}</a>
                                        @endif

                                        <div class="modal fade confirm-resend-{{ $invitation->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmResendLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmResendLabel">{{ __('Confirm resend') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to resend invitation for this internal user?') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.accounts.resend_invitation', ['id' => $invitation->id]) }}">
                                                            {{ csrf_field() }}
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                            <button type="submit" class="btn btn-primary">{{ __('Yes. Resend') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $invitation->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                    <div class="modal fade confirm-{{ $invitation->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="fw-bold mb-2">{{ __('Are you sure you want to delete this invitation?') }}</div>
                                                    @if ($invitation->user_id)
                                                        {{ __('Note: This user accepted invitation and create an account. The user account will be not deleted.') }}
                                                    @else
                                                        {{ __("Note: This user didn't create an account using this invitation. If you delete the invitation, user can not use this invitation to create an account.") }}
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.accounts.delete_invitation', ['id' => $invitation->id]) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                        <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{ $invitations->appends(['search_terms' => $search_terms])->links() }}

    </div>

</div>
