<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.template.global_sections') }}">{{ __('Top / bottom sections') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 mb-3">
                    @include('admin.template.layouts.menu-template')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Top / bottom sections') }}</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-global-section"><i class="bi bi-plus-circle"></i> {{ __('Create section') }}</button>
                        @include('admin.template.modals.create-global-section')
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
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This section exists') }} @endif
                </div>
            @endif

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Section') }}</th>
                            <th width="180">{{ __('Manage content') }}</th>
                            <th width="120">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($sections as $section)
                            <tr>
                                <td>
                                    <div class="listing">{{ $section->label }}</div>
                                </td>

                                <td>
                                    <a class="btn btn-primary" href="{{ route('admin.template.global_sections.show', ['id' => $section->id]) }}">{{ __('Manage content') }}</a>
                                </td>

                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#update-global-section-{{ $section->id }}" class="btn btn-primary me-2"><i class="bi bi-pencil-square"></i></button>
                                    @include('admin.template.modals.update-global-section')

                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $section->id }}" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                                    <div class="modal fade confirm-{{ $section->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this section?') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.template.global_sections.show', ['id' => $section->id]) }}">
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

            {{ $sections->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
