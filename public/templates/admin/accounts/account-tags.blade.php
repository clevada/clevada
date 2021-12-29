<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts') }}">{{ __('Accounts') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts.show', ['id' => $account->id]) }}">{{ $account->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Tags') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">
            <h4 class="card-title">{{ $account->name }}</h4>
            @if ($account->is_deleted == 1)<div class='alert alert-danger fw-bold'>{{ __('This account is deleted') }}. <a href="{{ route('admin.accounts.deleted') }} ">{{ __('View deleted accounts') }}</a></div>@endif
        </div>

        <div class="card-body">


            @include('admin.accounts.layouts.menu-account')
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
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This tag exist') }} @endif
                </div>
            @endif

            @if (check_access('accounts'))
                <div class="float-end mb-3">
                    <a class="btn btn-primary me-2" href="#" data-bs-toggle="modal" data-bs-target="#add-account-tag"><i class="bi bi-plus-circle"></i> {{ __('Add tag') }}</a>
                    @include('admin.accounts.modals.add-account-tag')

                    @if (check_access('accounts', 'manager'))
                    <a class="btn btn-secondary" href="{{ route('admin.accounts.tags') }}"><i class="bi bi-gear"></i> {{ __('Manage tags') }}</a>
                    @endif
                </div>
            @endif

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Tag') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($account_tags as $tag)
                            <tr>
                                <td>
                                    <div style="background-color: {{ $tag->color ?? '#b7b7b7' }}; padding: 5px 10px; display: inline; color: #fff; width: 100%;">{{ $tag->tag }}</div>

                                    <div class="float-end">
                                        @if (check_access('accounts'))
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $tag->id }}" class="btn btn-danger btn-sm"><i class='bi bi-trash'></i></a>
                                            <div class="modal fade confirm-{{ $tag->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this item?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.account.tags', ['id' => $account->id, 'tag_id' => $tag->id]) }}">
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

            {{ $account_tags->appends(['id' => $account->id])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
