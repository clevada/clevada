<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.templates') }}">{{ __('Templates') }}</a></li>
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
                    <h4 class="card-title">{{ __('Templates') }}</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-template"><i class="bi bi-plus-circle"></i> {{ __('Create template') }}</button>
                        @include('admin.template.modals.create-template')
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

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This template exists.') }} @endif
                    @if ($message == 'delete_default') {{ __('Error. Default template can not be deleted.') }} @endif
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Template') }}</th>
                            <th width="200">{{ __('Edit template') }}</th>
                            <th width="180">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($templates as $template)
                            <tr>
                                <td>                                    
                                    <h4>{{ $template->label }} @if ($template->is_default == 1) <span class="ms-2"><button type="button" class="btn btn-success btn-sm disabled">{{ __('Default') }}</button></span> @endif</h4>
                                    <div class="text-muted">{{ __('Created at') }}: {{ date_locale($template->created_at, 'datetime') }}</div>
                                </td>

                                <td>
                                    <div class="d-grid gap-2">
                                        <a class="btn btn-primary" href="{{ route('admin.templates.show', ['id' => $template->id]) }}"><i class="bi bi-pencil-square"></i> {{ __('Template editor') }}</a>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-grid gap-2">

                                        <a class="btn btn-secondary btn-sm mb-2" target="_blank" href="{{ route('homepage', ['preview_template_id' => $template->id]) }}"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview') }}</a>
                                        
                                        @if ($template->is_default == 0)                                            
                                            <a class="btn btn-secondary btn-sm mb-2" href="{{ route('admin.templates.set_default', ['id' => $template->id]) }}">{{ __('Set as default') }}</a>
                                        @endif

                                        @if ($template->is_default != 1)
                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $template->id }}" class="btn btn-danger btn-sm">{{ __('Delete template') }}</a>
                                        <div class="modal fade confirm-{{ $template->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to delete this template?') }}
                                                        <p class="fw-bold text-danger mt-2">{{ __('Warning! All content blocks, settings and styles will be permanently deleted') }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.templates.show', ['id' => $template->id]) }}">
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

            {{ $templates->links() }}


        </div>
        <!-- end card-body -->

    </div>

</section>
