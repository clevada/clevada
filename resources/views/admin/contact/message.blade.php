<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item "><a href="{{ route('admin.contact') }}">{{ __('Contact forms') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Message details') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if ($alert = Session::get('success'))
    <div class="alert alert-success">
        @if ($alert == 'task_created')
            {{ __('Task created') }}
        @endif
        @if ($alert == 'updated')
            {{ __('Updated') }}
        @endif
    </div>
@endif

<div class="row">

    <div class="col-12 col-md-7 col-lg-8">

        <div class="card">

            <div class="card-body">

                <div class="text-muted small mb-1">{{ date_locale($message->created_at, 'datetime') }}</div>

                <div class="fw-bold mb-3">{{ $message->subject }}</div>

                {!! nl2br($message->message) !!}

                @if (count($fields) > 0)
                    <hr>
                    @foreach ($fields as $item)
                        <div class="fw-bold">{{ $item->field->label ?? null }}:</div>
                        @if ($item->value)
                            @if ($item->field->type == 'checkbox')
                                @php
                                    $checkboxes_values_array = json_decode($item->value);
                                @endphp
                                @foreach ($checkboxes_values_array as $checkbox_value)
                                    @php
                                        if (strlen($checkbox_value) == 7 && substr($checkbox_value, 0, 1) == '#') {
                                            echo '<i class="bi bi-square-fill fs-3" style="color: ' . $checkbox_value . '"></i>';
                                        }
                                    @endphp

                                    {!! $checkbox_value !!}
                                    <br>
                                @endforeach
                            @elseif ($item->field->type == 'color')
                                <i class="bi bi-square-fill fs-3" style="color:{{ $item->value }}"></i> {{ $item->value }}
                            @elseif ($item->field->type == 'file')
                                @if ($item->file ?? null)
                                    <i class="bi bi-paperclip"></i> <a target="_blank" href="{{ asset('uploads/' . $item->file->file) }}">{{ $item->value }} </a>
                                @endif
                            @else
                                {!! nl2br($item->value) !!}
                            @endif
                        @else
                            <span class="text-danger">{{ __('No value') }}</span>
                        @endif
                        <div class="mb-3"></div>
                    @endforeach
                @endif

            </div>
            <!-- end card-body -->

        </div>

    </div>


    <div class="col-12 col-md-5 col-lg-4">

        <div class="card">

            <div class="card-body">

                {{ __('Sender name') }}:
                @if (!$message->name)
                    <span class="text-danger">{{ __('No name') }}</span>
                @else
                    <b>{{ $message->name }}</b>
                @endif
                <br />
                {{ __('Sender email') }}:
                @if (!$message->email)
                    <span class="text-danger">{{ __('No email') }}</span>
                @else
                    <b>{{ $message->email }}</b>
                @endif

                <br>
                
                IP: {{ $message->ip }}

                <hr>

                @if ($message->is_important == 0)
                    <a href="{{ route('admin.contact.mark', ['id' => $message->id, 'action' => 'important']) }}" class="btn btn-info btn-sm me-2"><i class="bi bi-star"></i>
                        {{ __('Flag as important') }}</a>
                @else
                    <a href="{{ route('admin.contact.mark', ['id' => $message->id, 'action' => 'not_important']) }}" class="btn btn-info btn-sm me-2"><i class="bi bi-star"></i>
                        {{ __('Flag as normal') }}</a>
                @endif


                @can('delete', App\Models\Contact::class)
                    @if (!$message->deleted_at)
                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $message->id }}" class="btn btn-danger btn-sm ms-2"><i class="bi bi-trash"></i> {{ __('Delete') }}</a>
                        <div class="modal fade confirm-{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ __('Are you sure you want to move this message to Recycle Bin?') }}
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="{{ route('admin.contact.delete', ['id' => $message->id]) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                            <button type="submit" class="btn btn-danger">{{ __('Yes. Move to trash') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <hr>

                        <div class="text-danger fw-bold mb-2">
                            {{ __('This item is in the Trash.') }}
                        </div>

                        <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'contact', 'id' => $message->id, 'return_to' => 'contact', 'action' => 'delete']) }}"
                            class="btn btn-danger btn-sm">{{ __('Permanently delete') }}</a>

                        <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'contact', 'id' => $message->id, 'return_to' => 'contact', 'action' => 'restore']) }}"
                            class="btn btn-success btn-sm ms-3">{{ __('Restore') }}</a>
                    @endif
                @endcan


            </div>

        </div>

    </div>

</div>
