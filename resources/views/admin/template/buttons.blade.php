<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template') }}">{{ __('Template builder') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.template.buttons') }}">{{ __('Buttons') }}</a></li>
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
                    @include('admin.template.includes.menu-template')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Buttons') }}</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-button"><i class="bi bi-plus-circle"></i> {{ __('Create button') }}</button>
                        @include('admin.template.modals.create-button')
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
                        {{ __('Error. This button exists') }}
                    @endif
                </div>
            @endif

            <div class="table-responsive-md">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="40">{{ __('ID') }}</th>
                            <th>{{ __('Label') }}</th>
                            <th width="180">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($buttons as $button)
                            <tr>
                                <td>
                                    {{ $button->id }}
                                </td>

                                <td>
                                    <style>
                                        .btn_style_{{ $button->id }} {
                                            background-color: {{ $button->bg_color ?? config('defaults.button_bg_color') }};
                                            color: {{ $button->font_color ?? config('defaults.button_font_color') }};                                            
                                        }

                                        .btn_style_{{ $button->id }}:hover {
                                            background-color: {{ $button->bg_color_hover ?? config('defaults.button_bg_color_hover') }};
                                            color: {{ $button->font_color_hover ?? config('defaults.button_font_color') }};                                            
                                        }
                                    </style>                                     
                                    
                                    <div class="float-end m-2">
                                        <button class="btn btn_style_{{ $button->id }} {{ $button->rounded ?? null }} {{ $button->font_weight ?? null }} {{ $button->shadow ?? null }} {{ $button->size ?? null }}">Preview button</button>
                                    </div>

                                    <div class="fs-6 fw-bold">{{ $button->label }} @if($button->is_default == 1)<span class="badge bg-light text-secondary"></i> {{ __('Default button') }}</span>@endif</div>
                                    
                                                                                               
                                </td>

                                <td>
                                    <div class="d-grid gap-2">

                                        <a class="btn btn-gear btn-sm mb-2" href="{{ route('admin.template.buttons.show', ['id' => $button->id]) }}">{{ __('Edit style') }}</a>

                                        @if ($button->is_default == 0)
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $button->id }}" class="btn btn-danger btn-sm">{{ __('Delete button') }}</a>
                                            <div class="modal fade confirm-{{ $button->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this button?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.template.buttons.show', ['id' => $button->id]) }}">
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

            {{ $buttons->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
