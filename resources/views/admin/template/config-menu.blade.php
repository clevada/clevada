<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template') }}">{{ __('Template builder') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Menu links') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-3">
                @include('admin.template.includes.menu-template')
            </div>

            <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Menu links') }}</h4>
            </div>

            <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-menu-link"><i class="bi bi-plus-circle"></i> {{ __('Add link') }}</button>
                    @include('admin.template.modals.create-menu-link')
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
                    {{ __('Creates') }}
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
                    {{ __('Error. This menu exist') }}
                @endif
                @if ($message == 'error_delete')
                    {{ __('Error. This menu can not be deleted') }}
                @endif
            </div>
        @endif

        <div class="table-responsive-md">
            <table class="table table-bordered table-hover sortable">
                <thead>
                    <tr>
                        <th width="40"><i class="bi bi-arrow-down-up"></i></th>
                        <th>{{ __('Details') }}</th>
                        <th width="230">{{ __('Destination') }}</th>
                        <th width="160">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="sortable">
                    @foreach ($links as $link)
                        <tr id="item-{{ $link->id }}">

                            <td class="movable">
                                <i class="bi bi-arrow-down-up"></i>
                            </td>

                            <td>
                                <div class="fw-bold">{!! $link->icon !!} {{ $link->label }}</div>

                                @if ($link->type == 'home')
                                    <a target="_blank" href="{{ route('home') }}">{{ route('home') }}</a>
                                @endif

                                @if ($link->type == 'custom')
                                    <a target="_blank" href="{{ $link->value }}">{{ $link->value }}</a>
                                @endif

                                @if ($link->type == 'contact')
                                    <a target="_blank"
                                        href="{{ route('contact') }}">{{ route('contact') }}</a>

                                    @if (($config->module_contact ?? null) != 'active')
                                        <div class="fw-bold text-danger mb-3">
                                            <i class="bi bi-info-circle"></i> {{ __('Contact page is not available on website because this module is not active.') }} <a
                                                href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Change') }}</a>
                                        </div>
                                    @endif
                                @endif

                                @if ($link->type == 'page')
                                    @php
                                        $link_url = page((int) $link->value)->url;
                                    @endphp
                                    <a target="_blank" href="{{ $link_url }}">{{ $link_url }}</a>
                                @endif                               
                            </td>

                            <td>
                                @if ($link->type == 'home')
                                    {{ __('Homepage') }}
                                @elseif($link->type == 'custom')
                                    {{ __('Custom link') }}
                                @elseif($link->type == 'contact')
                                    {{ __('Contact page') }}                               
                                @elseif($link->type == 'page')
                                    {{ __('Page') }}
                                @elseif($link->type == 'dropdown')
                                    <a class="btn btn-primary mb-2" href="{{ route('admin.template.menu.dropdown', ['link_id' => $link->id]) }}">{{ __('Manage dropdown links') }}</a>
                                    {{ __('Dropdown menu') }}
                                @else
                                    {{ $link->type }}
                                @endif
                            </td>

                            <td>
                                <div class="d-grid gap-2">

                                    <button data-bs-toggle="modal" data-bs-target="#update-menu-link-{{ $link->id }}" class="btn btn-primary btn-sm mb-2">{{ __('Update link') }}</button>
                                    @include('admin.template.modals.update-menu-link')


                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $link->id }}" class="btn btn-danger btn-sm">{{ __('Delete link') }}</a>
                                    <div class="modal fade confirm-{{ $link->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this link?') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.template.menu.show', ['id' => $link->id]) }}">
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

    </div>
    <!-- end card-body -->

</div>


<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#sortable").sortable({
            revert: true,
            axis: 'y',
            opacity: 0.5,
            revert: true,
            handle: ".movable",

            update: function(event, ui) {
                var data = $(this).sortable('serialize');
                // POST to server using $.post or $.ajax
                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{{ route('admin.template.menu.sortable') }}',
                });
            }
        });

        $("ul, li, .actions").disableSelection();
    });
</script>
