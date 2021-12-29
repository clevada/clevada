<div class="card-header">
    <h3><i class="far fa-user"></i> {{ $account->name}} ({{ $account->email}})</h3>
</div>
<!-- end card-header -->

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
        @if ($message=='created') {{ __('Created') }} @endif
        @if ($message=='updated') {{ __('Updated') }} @endif
        @if ($message=='deleted') {{ __('Deleted') }} @endif
    </div>
    @endif
   
    @if(check_access('accounts'))
    <div class="float-right">
        <a class="btn btn-primary mb-3" href="#" data-toggle="modal" data-target="#add-account-note"><i class="fas fa-plus-square" aria-hidden="true"></i> {{ __('Add internal note') }}</a>
        @include('admin.accounts.modals.add-account-note')
    </div>
    @endif

    <div class="table-responsive-md">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>{{ __('Details') }}</th>
                    <th width="60">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($notes as $note)
                <tr>
                    <td>
                        @if ($note->sticky==1) <span class="pull-right ml-2"><button type="button" class="btn btn-success btn-sm disabled"><i class="fas fa-thumbtack"></i> {{ __('Sticky') }}</button></span>
                        @endif

                        <div class="text-muted small mb-4">
                            @if ($note->author_avatar)
                            <span class="float-left mr-1"><img style="max-width:20px; height:auto;" src="{{ asset('uploads/'.$note->author_avatar) }}" /></span>
                            @endif
                            {{ $note->author_name }}
                            {{ __('at') }} {{ date_locale($note->created_at, 'datetime') }} 
                        </div>
                        {!! nl2br($note->note) !!}

                        @if($note->file)
                            <div class="mb-2"></div>
                            <a target="_blank" href="{{ asset('uploads/'.$note->file) }}"><i class="fas fa-link"></i> {{ $note->file }}</a>
                        @endif
                    </td>

                    <td>
                        @if(check_access('accounts', 'manager'))
                        <form method="POST" action="{{ route('admin.account.notes', ['id' => $account->id, 'note_id' => $note->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-danger btn-sm delete-item-{{$note->id}}"><i class="fas fa-trash-alt"></i></button>
                        </form>

                        <script>
                            $('.delete-item-{{$note->id}}').click(function(e){
									e.preventDefault() // Don't post the form, unless confirmed
									if (confirm("{{ __('Are you sure to delete this item?') }}")) {
										$(e.target).closest('form').submit() // Post the surrounding form
									}
								});
                        </script>
                        @endif
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{ $notes->appends(['id' => $account->id])->links() }}

</div>
<!-- end card-body -->