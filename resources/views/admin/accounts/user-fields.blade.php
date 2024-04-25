@include('admin.includes.color-picker')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}">{{ __('Accounts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Accounts custom fields') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <span class="float-end">
            <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#create-account-field"><i class="bi bi-plus-circle"></i> {{ __('Create field') }}</a>
            @include('admin.accounts.modals.create-account-field')
        </span>

        <h4 class="card-title"> {{ __('Accounts custom fields') }}</h4>
    </div>

    <div class="card-body">

        <div class="alert alert-light"><i class="bi bi-info-circle"></i>
            {{ __('Create additional fields for accounts. Users can fill this fields with details in their profile page. Yopu can create different fields for each account role.') }}</div>

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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. This field exist') }}
                @endif
            </div>
        @endif


        <div class="mb-2"></div>

        <div class="table-responsive-md">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Field') }}</th>
                        <th width="200">{{ __('Role') }}</th>
                        <th width="220">{{ __('Property') }}</th>
                        <th width="100">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($fields as $field)
                        <tr>
                            <td>
                                @if ($field->active == 0)
                                    <span class="float-end ms-1 badge bg-warning">{{ __('Inactive') }}</span>
                                @endif

                                <div class="fw-bold">{{ $field->name }}</div>
                                @if ($field->helptext)
                                    <div class="text-muted small">{{ $field->helptext }}</div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">
                                    @include('admin.includes.account-role-switch', ['role' => $field->role])
                                </div>
                            </td>

                            <td>
                                {{ __('Required') }}:
                                @if ($field->required == 1)
                                    <span class="fw-bold text-success">{{ __('Yes') }}</span>
                                @else
                                    {{ __('No') }}
                                @endif
                                <br>
                                {{ __('Show on the website') }}:
                                @if ($field->show_on_website == 1)
                                    <span class="fw-bold text-success">{{ __('Yes') }}</span>
                                @else
                                    {{ __('No') }}
                                @endif
                            </td>

                            <td>
                                <button data-bs-toggle="modal" data-bs-target="#update-field-{{ $field->id }}" class="btn btn-primary btn-sm me-2"><i class='bi bi-pen'></i></button>
                                @include('admin.accounts.modals.update-account-field')

                                <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $field->id }}" class="btn btn-danger btn-sm"><i class='bi bi-trash'></i></a>
                                <div class="modal fade confirm-{{ $field->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel-{{ $field->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="ConfirmDeleteLabel-{{ $field->id }}">{{ __('Confirm delete') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ __('Are you sure you want to delete this field?') }}
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST" action="{{ route('admin.accounts-fields.show', ['id' => $field->id]) }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                    <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                </form>
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

    </div>
    <!-- end card-body -->

</div>
