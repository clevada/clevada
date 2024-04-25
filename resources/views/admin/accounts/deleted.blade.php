<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}">{{ __('Accounts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Deleted accounts') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">
    <div class="card">

        <div class="card-header">

            <div class="row">
                <div class="col-12">
                    <h4 class="card-title">{{ __('Deleted accounts') }} ({{ $accounts->total() ?? 0 }})</h4>
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
                    @if ($message == 'deleted')
                        {{ __('Account permanently deleted') }}
                    @endif
                    @if ($message == 'restored')
                        {{ __('Account restored') }}
                    @endif
                </div>
            @endif

            <section>
                <form action="{{ route('admin.accounts.deleted') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="Search user" class="form-control @if ($search_terms) is-valid @endif" value="{{ $search_terms ?? '' }}" />
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a title="{{ __('Reset') }}" class="btn btn-light" href="{{ route('admin.accounts.deleted') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                            <th width="180">{{ __('Role') }}</th>
                            <th width="190">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($accounts as $account)
                            <tr>
                                <td>
                                    @if ($account->deleted_at)
                                        <span class="float-end ms-2"><button type="button" class="btn btn-danger btn-sm">{{ __('DELETED') }}</button></span>
                                    @endif

                                    @if ($account->avatar)
                                        <span class="float-start me-3"><img style="max-width:110px; height:auto;" src="{{ avatar($account->avatar) }}" /></span>
                                    @endif

                                    @php
                                        if ($account->last_activity_at) {
                                            $last_activity_minutes = round(abs(strtotime(now()) - strtotime($account->last_activity_at)) / 60, 2);
                                        }
                                    @endphp

                                    <h5><a href="{{ route('admin.accounts.show', ['id' => $account->id]) }}">{{ $account->name }}</a>
                                        @if ($account->last_activity_at && $last_activity_minutes < 10)
                                            <i title="Online" class="bi bi-circle-fill text-success fs-6"></i>
                                        @endif
                                    </h5>
                                    {{ $account->email }}<br>
                                    {{ __('ID') }}: {{ strtoupper($account->id) }} |
                                    {{ __('Registered') }}: {{ date_locale($account->created_at, 'datetime') }} <br>
                                    {{ __('Last activity') }}: @if ($account->last_activity_at)
                                        {{ date_locale($account->last_activity_at, 'datetime') }}
                                    @else
                                        {{ __('never') }}
                                    @endif
                                </td>

                                <td>
                                    @if ($account->role == 'user')
                                        <div class="small">
                                            @if (($account->count_paid_invoices ?? null) > 0 || ($account->count_unpaid_invoices ?? null) > 0)
                                                <h5 class="mb-0">{{ __('Invoices') }}</h5>
                                                <a @if ($account->count_unpaid_invoices > 0) class="text-danger font-weight-bold" @endif
                                                    href="{{ route('admin.invoices', ['search_user' => $account->email]) }}">{{ $account->count_unpaid_invoices }}
                                                    {{ __('unpaid invoices') }}</a> |
                                                <a href="{{ route('admin.invoices', ['search_user' => $account->email]) }}">{{ $account->count_paid_invoices }} {{ __('paid invoices') }}</a>
                                                <div class="mb-3"></div>
                                            @endif
                                        </div>

                                        <div class="small">
                                            @if (($account->count_open_tickets ?? null) > 0 || ($account->count_closed_tickets ?? null) > 0)
                                                <h5 class="mb-0">{{ __('Support tickets') }}</h5>
                                                <a @if ($account->count_open_tickets > 0) class="text-danger font-weight-bold" @endif
                                                    href="{{ route('admin.account.tickets', ['id' => $account->id]) }}">{{ $account->count_open_tickets }}
                                                    {{ __('open tickets') }}</a> |
                                                <a href="{{ route('admin.account.tickets', ['id' => $account->id]) }}">{{ $account->count_closed_tickets }} {{ __('closed tickets') }}</a>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <td>
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
                                </td>

                                <td>
                                    <div class="d-grid gap-2">

                                        <a class="btn btn-primary btn-sm" href="{{ route('admin.accounts.show', ['id' => $account->id]) }}">{{ __('Account details') }}</a>

                                        <a class="btn btn-success btn-sm" href="{{ route('admin.accounts.deleted.action', ['action' => 'restore', 'id' => $account->id]) }}">{{ __('Restore account') }}</a>

                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $account->id }}" class="btn btn-danger btn-sm">{{ __('Permanently delete') }}</a>
                                        <div class="modal fade confirm-{{ $account->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to permanently delete this account?') }}
                                                        <div class="mt-3 text-danger">
                                                            <i class="bi bi-exclamation-circle"></i>
                                                            {!! __('<b>Account details and activity will be removed from database. You can NOT recover this accout.</b>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="GET" action="{{ route('admin.accounts.deleted.action', ['action' => 'remove', 'id' => $account->id]) }}">
                                                            {{ csrf_field() }}
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

            {{ $accounts->appends(['search_terms' => $search_terms])->links() }}

        </div>

    </div>

</section>
