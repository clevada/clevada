<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.blocks') }}">{{ __('Content blocks') }}</a></li>
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
                    <h4 class="card-title">{{ __('Content blocks') }} ({{ $blocks->total() ?? 0 }})</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock"><i class="bi bi-plus-circle"></i> {{ __('Add new content block') }}</a>
                        @include('admin.includes.modal-add-block')
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

            <section>
                <form action="{{ route('admin.blocks') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">
                    <div class="col-12">
                        <select name="search_type_id" class="form-select @if ($search_type_id) is-valid @endif mr-2">
                            <option value="">- {{ __('Any type') }} -</option>
                            @foreach ($block_types as $type)
                                <option @if ($search_type_id == $type->id) selected @endif value="{{ $type->id }}">{{ __($type->label) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a title="{{ __('Reset') }}" class="btn btn-light" href="{{ route('admin.blocks') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </section>
            <div class="mb-3"></div>


            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            <th width="220">{{ __('Type') }}</th>
                            <th width="220">{{ __('Template code') }}</th>
                            <th width="160">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($blocks as $block)
                            <tr>

                                <td>
                                    <h5>{{ $block->label }}</h5>
                                    Id: {{ $block->id }}
                                    <div class="small text-secondary">{{ __('Created at ') }} {{ date_locale($block->created_at, 'datetime') }}</div>
                                </td>

                                <td>
                                    <h5>{{ $block->type_label }}</h5>
                                </td>

                                <td>
                                    <pre>{!! block(<?= $block->id ?>) !!}</pre>                                   
                                </td>

                                <td>
                                    <div class="d-grid gap-2">

                                        <a href="{{ route('admin.blocks.show', ['id' => $block->id]) }}" class="btn btn-primary btn-sm mb-2">{{ __('Update block') }}</a>

                                        <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $block->id }}" class="btn btn-danger btn-sm">{{ __('Delete block') }}</a>
                                        <div class="modal fade confirm-{{ $block->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('Are you sure you want to delete this block?') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.blocks.show', ['id' => $block->id]) }}">
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

            {{ $blocks->appends(['search_type_id' => $search_type_id])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
