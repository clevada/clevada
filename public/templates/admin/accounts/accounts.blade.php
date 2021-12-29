@if ($openmodal == 1)
    <script>
        $(document).ready(function() {
            $('#create-account').modal('show');
        });
    </script>
@endif

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Accounts') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">
    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Accounts') }} ({{ $accounts->total() ?? 0 }})</h4>
                </div>

                @if (($config->registration_disabled ?? null) || ($config->registration_verify_email_disabled ?? null))
                    <div class="alert alert-danger font-weight-bold">
                        <b>{{ __('Warning') }}</b>.
                        @if ($config->registration_disabled ?? null) {{ __('Registration is disabled') }}. @endif
                        @if ($config->registration_verify_email_disabled ?? null) {{ __('Email verification for registration is disabled') }}.@endif

                        @if (check_access('accounts'))
                            <a href="{{ route('admin.config.registration') }}">{{ __('Change') }}</a>
                        @endif
                    </div>
                @endif

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    @if (check_access('accounts', 'manager'))
                        <div class="float-end">
                            <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#create-account"><i class="bi bi-plus-circle"></i> {{ __('New account') }}</a>
                            @include('admin.accounts.modals.create-account')

                            @if (logged_user()->role == 'admin')
                                <div class="dropdown float-end ms-3">
                                    <button class="btn btn-secondary  dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="min-width: 200px;">
                                        <li><a class="dropdown-item" href="{{ route('admin.config.registration') }}">{{ __('Registration') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.accounts.tags') }}">{{ __('Manage tags') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.accounts.permissions') }}">{{ __('Internal permissions') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.accounts.deleted') }}">{{ __('Deleted accounts') }}</a></li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
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
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This email exist') }} @endif
                </div>
            @endif

            <section>
                <form action="{{ route('admin.accounts') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="Search user" class="form-control @if ($search_terms) is-valid @endif" value="{{ $search_terms ?? null }}" />
                    </div>

                    <div class="col-12">
                        <select name="search_role_id" class="form-select  @if ($search_role_id) is-valid @endif">
                            <option value="">- {{ __('All roles') }} -</option>
                            @foreach ($roles as $role)
                                <option @if ($search_role_id == $role->id) selected="selected" @endif value="{{ $role->id }}">
                                    @switch($role->role)
                                        @case('admin')
                                            {{ __('Administrator') }}
                                        @break

                                        @case('user')
                                            {{ __('Registered user') }}
                                        @break

                                        @case('internal')
                                            {{ __('Internal') }}
                                        @break

                                        @case('vendor')
                                            {{ __('Vendor') }}
                                        @break

                                        @case('contact')
                                            {{ __('Contact (no account)') }}
                                        @break

                                        @default
                                            {{ $role->role }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="search_tag_id" class="form-select @if ($search_tag_id) is-valid @endif">
                            <option selected="selected" value="">- {{ __('Any tag') }} -</option>
                            @foreach ($tags as $tag)
                                <option @if ($search_tag_id == $tag->id) selected @endif value="{{ $tag->id }}"> {{ $tag->tag }}
                                    -
                                    @switch($tag->role)
                                        @case('admin')
                                            {{ __('Administrator') }}
                                        @break

                                        @case('user')
                                            {{ __('Registered user') }}
                                        @break

                                        @case('internal')
                                            {{ __('Internal') }}
                                        @break

                                        @case('vendor')
                                            {{ __('Vendor') }}
                                        @break

                                        @case('contact')
                                            {{ __('Contact (no account)') }}
                                        @break

                                        @default
                                            {{ $tag->role }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="search_active" class="form-select @if ($search_active) is-valid @endif">
                            <option selected="selected" value="">- {{ __('Any status') }} -</option>
                            <option @if ($search_active == '1') selected @endif value="1"> {{ __('Active accounts') }}</option>
                            <option @if ($search_active == '0') selected @endif value="0"> {{ __('Inactive accounts') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="search_email_verified" class="form-select @if ($search_email_verified) is-valid @endif">
                            <option selected="selected" value="">- {{ __('Any email status') }} -</option>
                            <option @if ($search_email_verified == '1') selected @endif value="1"> {{ __('Email verified accounts') }}</option>
                            <option @if ($search_email_verified == '0') selected @endif value="0"> {{ __('Email not verified accounts') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a title="{{ __('Reset') }}" class="btn btn-light" href="{{ route('admin.accounts') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>

                </form>
            </section>

            <div class="mb-3"></div>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            <th width="250">{{ __('Activity') }}</th>
                            <th width="180">{{ __('Role / permissions') }}</th>
                            <th width="140">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($accounts as $account)
                            <tr>
                                <td>
                                    @if ($account->active != 1) <span class="float-end ms-2"><button type="button" class="btn btn-danger btn-sm disabled">{{ __('Inactive') }}</button></span> @endif
                                    @if (!$account->email_verified_at) <span class="float-end ms-2"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Email not verified') }}</button></span> @endif
                                    @if ($account->is_deleted == 1) <span class="float-end ms-2"><button type="button" class="btn btn-danger btn-sm">{{ __('DELETED') }}</button></span> @endif

                                    @if ($account->avatar)
                                        <span class="float-start me-3"><img style="max-width:110px; height:auto;" src="{{ asset('uploads/' . $account->avatar) }}" /></span>
                                    @endif

                                    @php
                                        if ($account->last_activity) {
                                            $last_activity_minutes = round(abs(strtotime(now()) - strtotime($account->last_activity)) / 60, 2);
                                        }
                                    @endphp

                                    <h5><a href="{{ route('admin.accounts.show', ['id' => $account->id]) }}">{{ $account->name }}</a> @if ($account->last_activity && $last_activity_minutes < 10)<i title="Online" class="bi bi-circle-fill text-success fs-6"></i>@endif</h5>

                                    @if ($account->user_tags)
                                        <div class="mb-2 mt-2">
                                            @foreach ((array) explode(',', $account->user_tags) as $tag)
                                                <a href="{{ route('admin.accounts', ['search_tag_id' => explode('@', $tag)[0]]) }}"><span class="mr-2 small"
                                                        style="background-color: {{ explode('@', $tag)[2] ?? '#b7b7b7' }}; padding: 4px 6px; display: inline; color: #fff; width: 100%;">{{ explode('@', $tag)[1] ?? null }}</span></a>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{ $account->email }}<br>
                                    {{ __('ID') }}: {{ strtoupper($account->id) }} |
                                    {{ __('Code') }}: {{ strtoupper($account->code) ?? null }} |
                                    {{ __('Registered') }}: {{ date_locale($account->created_at, 'datetime') }} |
                                    {{ __('Last activity') }}: @if ($account->last_activity){{ date_locale($account->last_activity, 'datetime') }}@else <span class="text-danger">{{ __('never') }}</span>@endif

                                </td>

                                <td>
                                    @if ($account->role == 'user' || $account->role == 'contact')
                                        <div class="small">
                                            @if (($account->count_paid_invoices ?? null) > 0 || ($account->count_unpaid_invoices ?? null) > 0)
                                                <h5 class="mb-0">{{ __('Invoices') }}</h5>
                                                <a @if ($account->count_unpaid_invoices > 0) class="text-danger font-weight-bold" @endif href="{{ route('admin.invoices', ['search_user' => $account->email]) }}">{{ $account->count_unpaid_invoices }}
                                                    {{ __('unpaid invoices') }}</a> |
                                                <a href="{{ route('admin.invoices', ['search_user' => $account->email]) }}">{{ $account->count_paid_invoices }}
                                                    {{ __('paid invoices') }}</a>
                                                <div class="mb-3"></div>
                                            @endif
                                        </div>

                                        <div class="small">
                                            @if (($account->count_open_tickets ?? null) > 0 || ($account->count_closed_tickets ?? null) > 0)
                                                <h5 class="mb-0">{{ __('Support tickets') }}</h5>
                                                <a @if ($account->count_open_tickets > 0) class="text-danger font-weight-bold" @endif href="{{ route('admin.account.tickets', ['id' => $account->id]) }}">{{ $account->count_open_tickets }}
                                                    {{ __('open tickets') }}</a> |
                                                <a href="{{ route('admin.account.tickets', ['id' => $account->id]) }}">{{ $account->count_closed_tickets }} {{ __('closed tickets') }}</a>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <b>
                                    @switch($account->role)
                                        @case('admin')
                                            {{ __('Administrator') }}
                                        @break

                                        @case('user')
                                            {{ __('Registered user') }}
                                        @break

                                        @case('internal')
                                            {{ __('Internal') }}
                                        @break

                                        @case('vendor')
                                            {{ __('Vendor') }}
                                        @break

                                        @case('contact')
                                            {{ __('Contact (no account)') }}
                                        @break

                                        @default
                                            {{ $account->role }}
                                    @endswitch
                                    </b>

                                    @if ($account->role == 'user' && $account->posts_contributor)
                                        <div class="mt-2 small">{{ __('Posts contributor') }}
                                            @if ($account->posts_auto_approve == 1)<span class="text-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Posts are automatically approved') }}"><i class="bi bi-check-circle"></i></span>
                                            @else <span class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Posts must be approved before publish') }}"><i class="bi bi-exclamation-circle"></i></span>@endif
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-grid gap-2">

                                        <a class="btn btn-primary btn-sm" href="{{ route('admin.accounts.show', ['id' => $account->id]) }}">{{ __('Update') }}</a>

                                        @if (check_access('accounts', 'manager'))
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $account->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                            <div class="modal fade confirm-{{ $account->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this account?') }}
                                                            <div class="mt-3 text-info">
                                                                <i class="bi bi-exclamation-circle"></i>
                                                                {!! __('Note: <b>Account details and activity are not removed from database</b>. You can recover this accout from deleted accounts page. Account holder can not login into a deleted account.') !!}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.accounts.show', ['id' => $account->id, 'search_role_id' => $search_role_id]) }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                                <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $accounts->appends(['search_terms' => $search_terms, 'search_active' => $search_active, 'search_email_verified' => $search_email_verified, 'search_role_id' => $search_role_id, 'search_tag_id' => $search_tag_id])->links() }}

        </div>

    </div>

</section>
