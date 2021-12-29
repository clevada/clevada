<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Internal permissions') }}</li>
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
                    <h4 class="card-title">{{ __('Internal permissions') }}</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    @if (check_access('accounts', 'manager'))
                        <div class="float-end">
                            <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#permissionsHelp"><i class="bi bi-question-circle-fill"></i> {{ __('Help related to permissions') }}</a>
                            @include('admin.accounts.modals.permissions-help')
                        </div>
                    @endif
                </div>

            </div>

        </div>



        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif
          
            <section>
                <form action="{{ route('admin.accounts.permissions') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="Search user" class="form-control me-2 @if ($search_terms) is-valid @endif" value="{{ $search_terms ?? '' }}" />
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light" href="{{ route('admin.accounts.permissions') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>

                </form>
            </section>

            <div class="mb-3"></div>

            <form method="post">
                @csrf

                <div class="table-responsive-md">
                    <table class="table table-bordered table-hover">


                        <tbody>
                            @foreach ($internal_accounts as $user)
                                <tr>
                                    <td width="240" style="width:250px !important">
                                        @if ($user->avatar)
                                            <span class="float-left mr-2"><img style="max-width:25px; height:auto;" src="{{ asset('uploads/' . $user->avatar) }}" /></span>
                                        @endif
                                        <a target="_blank" href="{{ route('admin.accounts.show', ['id' => $user->id]) }}"><b>{{ $user->name }}</b></a>
                                        <div class="clearfix"></div>
                                        {{ $user->email }}
                                    </td>

                                    <td>
                                        <div class="row">
                                            @foreach ($modules_permissions as $module_permissions)

                                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
                                                    <label>{{ $module_permissions->module_label }}</label>
                                                    <select class="form-control" name="{{ $module_permissions->module_id }}_{{ $user->id }}">
                                                        <option @if (!chekbox_permissions($module_permissions->module_id, $user->id)) selected @endif value="0">- {{ __('No access') }} -</option>
                                                        @foreach ($module_permissions->permissions as $permission)
                                                            <option @if (chekbox_permissions($permission->id, $user->id)) selected @endif value="{{ $permission->id }}">{{ $permission->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            @endforeach
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


                <input class="btn btn-danger" type="submit" name="action" value="{{ __('Update permissions') }}">

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
