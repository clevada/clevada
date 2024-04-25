<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contact') }}">{{ __('Contact messages') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Manage custom fields') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <div class="alert alert-light">
                    <i class="bi bi-info-circle"></i> {{ __("Add custom fields to contact form. Note: name, subject, email and message fields are already set in the form, so you don't need to add this fields.") }}
                </div>
            </div>

            <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Custom fields') }}</h4>
            </div>

            <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                <div class="float-end mb-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-form-field"><i class="bi bi-plus-circle"></i> {{ __('Create new field') }}</button>
                    @include('admin.contact.includes.modal-create-form-field')
                </div>
            </div>
        </div>

    </div>


    <div class="card-body">

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
                @if ($message == 'protected')
                    {{ __("This field can't be deleted. You can set this field as inactive if you don't want to be displayed in the form") }}
                @endif
            </div>
        @endif

        <div class="table-responsive-md">
            <table class="table table-bordered table-hover sortable">
                <thead>
                    <tr>
                        <th width="40"><i class="bi bi-arrow-down-up"></i></th>
                        <th>{{ __('Field details') }}</th>
                        <th width="260">{{ __('Type') }}</th>
                        <th width="160">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="sortable">
                    @foreach ($fields as $field)
                        <tr @if ($field->active == 0) class="bg-light" @endif id="item-{{ $field->id }}">

                            <td class="movable">
                                <i class="bi bi-arrow-down-up"></i>
                            </td>

                            <td>

                                @if ($field->active == 0)
                                    <span class="float-end"><button class="btn btn-warning btn-sm">{{ __('Inactive') }}</button></span>
                                @endif

                                @if ($field->required == 1)
                                    <span class="float-end"><button class="btn btn-info btn-sm">{{ __('Required') }}</button></span>
                                @endif

                                <div class="fw-bold mb-1 fs-5">{{ $field->label }}</div>

                                @if ($field->type == 'select' || $field->type == 'checkbox')
                                    <div class="mb-1"></div>
                                    @if (!($field->dropdowns ?? null))
                                        <span class="text-danger">{{ __('Values not set') }}</span>
                                    @else
                                        {{ __('Values') }}: <br>{!! nl2br($field->dropdowns) !!}
                                    @endif
                                @endif

                                @if ($field->info ?? null)
                                    <i>{{ $field->info }}</i>
                                @endif
                            </td>

                            <td>
                                @switch($field->type)
                                    @case('text')
                                        {{ __('Text (one row)') }}
                                    @break

                                    @case('textarea')
                                        {{ __('Textarea (multiple rows)') }}
                                    @break

                                    @case('select')
                                        {{ __('Select from dropdown values (one selection)') }}
                                    @break

                                    @case('checkbox')
                                        {{ __('Select multiple values') }}
                                    @break

                                    @case('file')
                                        {{ __('Upload file') }}
                                    @break

                                    @case('email')
                                        {{ __('Email') }}
                                    @break

                                    @case('number')
                                        {{ __('Number (integer)') }}
                                    @break

                                    @case('month')
                                        {{ __('Month') }}
                                    @break

                                    @case('date')
                                        {{ __('Date (day / month / year)') }}
                                    @break

                                    @case('time')
                                        {{ __('Time (hour / minute)') }}
                                    @break

                                    @case('datetime')
                                        {{ __('Date and time') }}
                                    @break

                                    @case('color')
                                        {{ __('Color') }}
                                    @break

                                    @default
                                        {{ $field->type }}
                                @endswitch
                                </b>
                                </h6>
                            </td>

                            <td>
                                <div class="d-grid gap-2">

                                    <a href="{{ route('admin.contact.show_field', ['field_id' => $field->id]) }}" class="btn btn-primary btn-sm mb-2">{{ __('Update field') }}</a>

                                    @if ($field->protected != 1)
                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $field->id }}" class="btn btn-danger  btn-sm">{{ __('Delete field') }}</a>
                                        <div class="modal fade confirm-{{ $field->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to delete this field?') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.contact.delete_field', ['field_id' => $field->id]) }}">
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
                    url: "{{ route('admin.contact.sortable_fields') }}",
                });
            }
        });

        $("ul, li, .actions").disableSelection();
    });
</script>
