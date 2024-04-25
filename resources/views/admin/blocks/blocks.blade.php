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

            <div class="alert alert-light mb-3">
                <i class="bi bi-exclamation-circle"></i> <b>{{ __('Here you can create custom content blocks that you can use in your templates.') }}</b><br>
                    {{ __('Note: Blocks added in pages / posts etc... can only be managed from the content of that page / post') }}.
                    {{ __('To avoid losing content, blocks created during template installation can only be deleted when you uninstall a template.') }}
            </div>

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
                        <input name="search_terms" class="form-control @if ($search_terms) is-valid @endif mr-2" value="{{ $search_terms ?? null }}" placeholder="{{ __('Search label') }}">                          
                    </div>

                    <div class="col-12">
                        <select name="search_type" class="form-select @if ($search_type) is-valid @endif mr-2">
                            <option value="">- {{ __('Any type') }} -</option>
                            @foreach (get_block_types() as $type)
                                <option @if ($search_type == $type->id) selected @endif value="{{ $type->id }}">{{ __($type->label) }}</option>
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
                            <th width="180">{{ __('Type') }}</th>
                            <th width="330">{{ __('Template code') }}</th>
                            <th width="180">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($blocks as $block)
                            <tr>

                                <td>
                                    <div class="fs-6 fw-bold">{{ $block->label }}</div>
                                    ID: {{ $block->id }}
                                    @if($block->template)
                                    <div class="text-info">
                                    <i class="bi bi-exclamation-circle"></i> {{ __('Used in template') }}: {{ $block->template }}
                                    </div>
                                    @endif
                                    <div class="small text-secondary">{{ __('Created at ') }} {{ date_locale($block->created_at, 'datetime') }}</div>
                                </td>

                                <td>
                                    <b>{{ $block->type_label }}</b>
                                </td>

                                <td>
                                    <code>
                                        <?php echo "@include('core.block', ['id' => $block->id])"; ?>                                                                                
                                    </code>                                   
                                </td>

                                <td>
                                    <div class="d-grid gap-2">

                                        <a href="{{ route('admin.blocks.show', ['id' => $block->id]) }}" class="btn btn-primary btn-sm mb-2">{{ __('Manage content') }}</a>

                                        @if(!$block->template)
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
                                        @endif

                                    </div>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $blocks->appends(['search_type' => $search_type, 'search_terms' => $search_terms])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
