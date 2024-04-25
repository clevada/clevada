<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.ads.index') }}">{{ __('Ads') }}</a></li>
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
                    <div class="mt-1 fst-italic"><i class="bi bi-exclamation-circle"></i> {{ __('Note: Ads must be added to content blocks.') }}.</div>
                </div>
            </div>

            <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Ads') }} ({{ $items->total() }})</h4>
            </div>

            <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create" href="#"><i class="bi bi-plus-circle"></i> {{ __('Create new ad') }}</a>
                    @include('admin.ads.modals.create')
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
                    {{ __('Error. There is another ad with this label. Please input a different label') }}
                @endif
            </div>
        @endif

        <div class="table-responsive-md">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="40">ID</th>
                        <th>{{ __('Details') }}</th>
                        <th width="120">{{ __('Type') }}</th>
                        <th width="120">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($items as $item)
                        <tr @if ($item->active == 0) class="table-warning" @endif>
                            <td>
                                {{ $item->id }}
                            </td>

                            <td>
                                <div class="fs-6 fw-bold">{{ $item->label }}</div>

                                @if ($item->type == 'image')
                                    @if ($item->url)
                                        URL: <a target="_blank" href="{{ $item->url }}">{{ $item->url }}</a>
                                    @else
                                        <div class="text-danger">{{ __('URL not set') }}</div>
                                    @endif
                                    <div class="mb-2"></div>
                                @endif

                                @if ($item->type == 'image' && $item->content)
                                    <a target="_blank" href="{{ image($item->content) }}"><img style="max-width:200px; height:auto;" src="{{ image($item->content) }}" /></a>
                                @endif
                            </td>

                            <td>
                                @if ($item->type == 'image')
                                    {{ __('Image') }}
                                @endif
                                @if ($item->type == 'code')
                                    {{ __('Code') }}
                                @endif
                            </td>

                            <td>
                                <div class="d-grid gap-2">

                                    <a href="#" data-bs-toggle="modal" data-bs-target="#update-{{ $item->id }}" class="btn btn-primary btn-sm mb-2">{{ __('Update') }}</a>
                                    @include('admin.ads.modals.update')

                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $item->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                    <div class="modal fade confirm-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this item?') }}
                                                    <div class="fw-bold text-danger">{{ __('Warning: If you use this ad in template, the ad will not be displayed.') }}</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.ads.show', ['id' => $item->id]) }}">
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

        {{ $items->links() }}

    </div>
    <!-- end card-body -->

</div>
