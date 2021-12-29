<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.media') }}">{{ __('Media') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

				<div class="col-12">
					<div class="alert alert-light">
						<b>{{ __('Here you can upload custom images that you can use in your custom template code')}}.</b>
						<div class="mt-1 fst-italic"><i class="bi bi-exclamation-circle"></i> {{ __('Note: Images added to content blocks (pages, posts, etc.) can only be managed from the content of that block.')}}.</div>
					</div>
				</div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Images') }} ({{ $items->total() }})</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    @if (check_access('pages'))
                        <div class="float-end">
                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create" href="#"><i class="bi bi-plus-circle"></i> {{ __('Upload new image') }}</a>
                            @include('admin.media.modals.create')
                        </div>
                    @endif
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

            <div class="table-responsive-md">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="160">{{ __('Image') }}</th>
                            <th>{{ __('Details') }}</th>
							<th width="280">{{ __('Template codes') }}</th>
                            <th width="120">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($items as $item)
                            <tr>

                                <td>
                                    @if ($item->file)
                                        <img style="max-width:160px; height:auto;" src="{{ thumb($item->file) }}" />
                                    @endif                                    
                                </td>

                                <td>
                                    {{ __('ID') }}: {{ $item->id }}
									<div class="mb-1"></div>
									{{ __('Big image URL') }}: <a target="_blank" href="{{ image($item->file) }}">{{ image($item->file) }}</a>
									<br>
									{{ __('Thumb URL') }}: <a target="_blank" href="{{ thumb($item->file) }}">{{ thumb($item->file) }}</a>
                                </td>

								<td>
									{{ __('Big image') }}:
                                    <pre>&lt;img src="{!! image(<?= $item->id ?>) !!}"&gt;</pre>  

									<div class="mb-1"></div>

									{{ __('Thumb image') }}:
                                    <pre>&lt;img src="{!! thumb(<?= $item->id ?>) !!}"&gt;</pre>  
                                </td>                               

                                <td>
                                    <div class="d-grid gap-2">

                                        <a href="#" data-bs-toggle="modal" data-bs-target="#update-{{ $item->id }}" class="btn btn-primary btn-sm mb-2">{{ __('Update') }}</a>
                                        @include('admin.media.modals.update')

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
														<div class="fw-bold text-danger">{{ __('Warning: If you use this image in custom code, the image will not be displayed.') }}</div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.media.show', ['id' => $item->id]) }}">
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

</section>
