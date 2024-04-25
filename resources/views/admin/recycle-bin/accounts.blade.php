<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.recycle_bin') }}">{{ __('Recycle Bin') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Deleted accounts') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Deleted accounts') }} ({{ $items->total() ?? 0 }} {{ __('items') }})</h4>
            </div>

            <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    <a href="#" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#empty-trash"><i class="bi bi-trash"></i> {{ __('Empty this trash') }}</a>
                    @include('admin.recycle-bin.modals.empty-trash')
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
                @if ($message == 'restore')
                    {{ __('Item restored') }}
                @endif
                @if ($message == 'delete')
                    {{ __('Item permanently deleted') }}
                @endif
            </div>
        @endif

        <section>
            <form action="{{ route('admin.recycle_bin.module', ['module' => 'accounts']) }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                <div class="col-12">
                    <input type="text" name="search_terms" placeholder="Search name or email" class="form-control @if ($search_terms) is-valid @endif" value="{{ $search_terms ?? '' }}" />
                </div>

                <div class="col-12">
                    <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                    <a title="{{ __('Reset') }}" class="btn btn-light" href="{{ route('admin.recycle_bin.module', ['module' => 'accounts']) }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </section>


        <div class="mb-2"></div>


        @if ($items->total() == 0)
            {{ __('No item') }}
        @else
            <form method="POST" action="{{ route('admin.recycle_bin.multiple_action', ['module' => 'accounts']) }}">
                @csrf

                <div class="table-responsive-md">
                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th width="20"></th>
                                <th>{{ __('Details') }}</th>
                                <th width="320">{{ __('Role') }}</th>
                                <th width="200">{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <input name='item_checkbox[]' type='checkbox' id='item_checkbox_{{ $item->id }}[]' value='{{ $item->id }}'>
                                    </td>
                                    <td>
                                        <span class="float-start me-3"><img style="max-width:110px; height:auto;" src="{{ avatar($item->id) }}" /></span>

                                        @php
                                            if ($item->last_activity_at) {
                                                $last_activity_minutes = round(abs(strtotime(now()) - strtotime($item->last_activity_at)) / 60, 2);
                                            }
                                        @endphp

                                        <h5><a href="{{ route('admin.accounts.show', ['id' => $item->id]) }}">{{ $item->name }}</a>
                                            @if ($item->last_activity_at && $last_activity_minutes < 10)
                                                <i title="Online" class="bi bi-circle-fill text-success fs-6"></i>
                                            @endif
                                        </h5>
                                        {{ $item->email }}<br>
                                        {{ __('ID') }}: {{ strtoupper($item->id) }} |
                                        {{ __('Registered') }}: {{ date_locale($item->created_at, 'datetime') }} <br>
                                        {{ __('Last activity') }}: @if ($item->last_activity_at)
                                            {{ date_locale($item->last_activity_at, 'datetime') }}
                                        @else
                                            {{ __('never') }}
                                        @endif
                                    </td>

                                    <td>
                                        @switch($item->role)
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
                                                {{ $account->role }}
                                        @endswitch
                                    </td>

                                    <td>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'accounts', 'id' => $item->id, 'return_to' => 'recycle_bin', 'action' => 'delete']) }}"
                                                class="btn btn-danger btn-sm mb-2">{{ __('Permanently delete') }}</a>

                                            <a href="{{ route('admin.recycle_bin.single_action', ['module' => 'accounts', 'id' => $item->id, 'return_to' => 'recycle_bin', 'action' => 'restore']) }}"
                                                class="btn btn-success btn-sm">{{ __('Restore') }}</a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


                <div class="row row-cols-lg-auto g-3">
                    <div class="col-12">
                        <div class="form-group">
                            <select name="action" class="form-select" required>
                                <option value="">- {{ __('With selected:') }} -</option>
                                <option value="multiple_delete">{{ __('Permanently delete') }}</option>
                                <option value="multiple_restore">{{ __('Restore') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="hidden" name="return_to" value="recycle_bin">
                        <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    </div>
                </div>
            </form>

            {{ $items->appends(['search_terms' => $search_terms])->links() }}

        @endif

    </div>
    <!-- end card-body -->

</div>

<script language="JavaScript">
    $('#select-all').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });
</script>
