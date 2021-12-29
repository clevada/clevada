<!-- Color picker -->
<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts') }}">{{ __('Accounts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage tags') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section">

    <div class="card">

        <div class="card-header">
            <h4 class="card-title"> {{ __('Accounts tags') }} ({{ $tags->total() ?? 0 }})</h4>
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
                    @if ($message == 'duplicate') {{ __('Error. This tag exist') }} @endif
                </div>
            @endif

            <span class="float-end mb-3">
                <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#create-tag"><i class="bi bi-plus-circle"></i> {{ __('Create account tag') }}</a>
                @include('admin.accounts.modals.create-tag')
            </span>

            <section>
                <form action="{{ route('admin.accounts.tags') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="Search tag" class="form-control @if ($search_terms) is-valid @endif" value="{{ $search_terms ?? '' }}" />
                    </div>

                    <div class="col-12">
                        <select name="search_role_id" class="form-select @if ($search_role_id) is-valid @endif">
                            <option value="">- {{ __('All roles') }} -</option>
                            @foreach ($active_roles as $role)
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
                        <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a title="{{ __('Reset') }}" class="btn btn-light" href="{{ route('admin.accounts.tags') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </section>

            <div class="mb-2"></div>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Tag') }}</th>
                            <th width="200">{{ __('Role') }}</th>
                            <th width="160">{{ __('Accounts') }}</th>
                            <th width="100">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($tags as $tag)
                            <tr>
                                <td>
                                    <div style="background-color: {{ $tag->color ?? '#b7b7b7' }}; padding: 5px 10px; display: inline; color: #fff; width: 100%;">{{ $tag->tag }}</div>
                                </td>

                                <td>
                                    <h5>
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

                                            @default
                                                {{ $tag->role }}
                                        @endswitch
                                    </h5>
                                </td>

                                <td>
                                    <h5><a href="{{ route('admin.accounts', ['search_tag_id' => $tag->id]) }}">{{ $tag->count_accounts }} {{ __('accounts') }}</a></h5>
                                </td>

                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#update-tag-{{ $tag->id }}" class="btn btn-primary btn-sm me-2"><i class='bi bi-pen'></i></button>
                                    @include('admin.accounts.modals.update-tag')

                                    @if (check_access('accounts', 'manager'))
                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $tag->id }}" class="btn btn-danger btn-sm"><i class='bi bi-trash'></i></a>
                                        <div class="modal fade confirm-{{ $tag->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to delete this tag?') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.accounts.tags.show', ['id' => $tag->id]) }}">
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
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $tags->appends(['search_terms' => $search_terms])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
