<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}">{{ __('Accounts') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts.show', ['id' => $account->id]) }}">{{ $account->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Internal notes') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-header">
        <div class="card-title">{{ $account->name }}</div>
    </div>

    <div class="card-body">

        @include('admin.accounts.includes.menu-account')
        <div class="mb-3"></div>

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
                    {{ __('Created') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        <div class="float-end">
            <a class="btn btn-primary mb-3" href="#" data-bs-toggle="modal" data-bs-target="#add-account-note"><i class="fas fa-plus-square" aria-hidden="true"></i> {{ __('Add internal note') }}</a>
            @include('admin.accounts.modals.add-account-note')
        </div>

        <div class="mb-3 pt-2 small text-muted">
            <i class="bi bi-info-circle"></i> {{ __('Only administrators and managers can see internal notes. Internal notes can be added for editors, authors and contributors.') }}
        </div>

        <div class="table-responsive-md">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Details') }}</th>
                        <th width="100">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($notes as $note)
                        <tr>
                            <td>
                                @if ($note->sticky == 1)
                                    <div class="float-end ms-2 badge bg-info fs-6 fw-normal"><i class="bi bi-pin"></i> {{ __('Sticky') }}</div>
                                @endif

                                <div class="text-muted mb-3">

                                    <span class="float-start me-1"><img style="max-width:25px; height:auto;" class="img-fluid rounded rounded-circle" src="{{ avatar($note->created_by_user_id) }}" /></span>

                                    <b><a href="{{ route('admin.accounts.show', ['id' => $note->created_by_user_id]) }}">{{ $note->author->name }}</a></b> {{ __('at') }}
                                    {{ date_locale($note->created_at, 'datetime') }}
                                </div>
                                {!! nl2br($note->note) !!}

                                @if ($note->file)
                                    <div class="mb-2"></div>
                                    <a target="_blank" href="{{ asset('uploads/' . $note->attachment->file) }}"><i class="bi bi-link-45deg"></i> <b>{{ $note->attachment->original_name }}</b></a>
                                @endif
                            </td>

                            <td>
                                <div class="d-grid gap-2">
                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $note->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                    <div class="modal fade confirm-{{ $note->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this note?') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.account.internal_notes', ['note_id' => $note->id, 'id' => $account->id]) }}">
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

        {{ $notes->appends(['id' => $account->id])->links() }}

    </div>

</div>
