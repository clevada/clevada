<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.log.email') }}">{{ __('Email log') }}</a></li>					
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
                    @include('admin.core.layouts.menu-tools')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Emails sent log') }} ({{ $emails -> total() }} {{ __('emails') }})</h4>
                </div>                           

            </div>

        </div>


		<div class="card-body">	
	
			@if ($message = Session::get('success'))
			<div class="alert alert-success">
				@if ($message=='deleted') {{ __('Deleted') }} @endif
			</div>
			@endif

			<section>
                <form action="{{ route('admin.log.email') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_email" placeholder="{{ __('Search email') }}" class="form-control me-2 mb-2 @if ($search_email) is-valid @endif" value="<?= $search_email ?>" />
                    </div>

					<div class="col-12">
                        <input type="text" name="search_subject" placeholder="{{ __('Search subject') }}" class="form-control me-2 mb-2 @if ($search_subject) is-valid @endif" value="<?= $search_subject ?>" />
                    </div>                                     

                    <div class="col-12">
                        <button class="btn btn-secondary me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light mb-2" href="{{ route('admin.log.email') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </section>

            <div class="mb-2"></div>
			
			<div class="table-responsive-md">

				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>{{ __('Details') }}</th>
							<th width="250">{{ __('Destinatar') }}</th>
							<th width="250">{{ __('Sent at') }}</th>
							<th width="40"></th>
						</tr>
					</thead>

					<tbody>

						@foreach ($emails as $email)
						<tr>
							<td>
								<div class="listing fw-bold"><a href="{{ route('admin.log.email.show', ['id' => $email->id]) }}">{{ $email->subject}}</a></div>
								{{ __('Module') }}: {{ $email->module }}
							</td>

							<td>
								<b>{{ $email->email }}</b>								
							</td>

							<td>
								<b>{{ date_locale($email->created_at, 'datetime') }}</b>																
							</td>

							<td>
								<a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $email->id }}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                    <div class="modal fade confirm-{{ $email->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this log item?') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.log.email.show', ['id' => $email->id, 'search_email' => $search_email, 'search_subject' => $search_subject]) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                        <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                    </form>
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

			{{ $emails->appends(['search_email' => $search_email, 'search_subject' => $search_subject])->links() }}

		</div>
		<!-- end card-body -->

	</div>

</section>