<div class="card-header">
	<h3><i class="fas fa-exclamation-triangle"></i> {{ __('Posts reported') }} ({{ $reports_pending ?? 0 }} {{ __('pending') }})</h3>
</div>
<!-- end card-header -->

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
		@if ($message=='updated') {{ __('Updated') }} @endif
		@if ($message=='deleted') {{ __('Deleted') }} @endif
	</div>
	@endif

	<div class="table-responsive-md">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>{{ __('Post details') }}</th>
					<th width="600">{{ __('Report') }}</th>
					<th width="160">{{ __('Status') }}</th>
					<th width="200">{{ __('Actions') }}</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($reports as $report)
				<tr>
					<td>						
						{!! substr(strip_tags($report->post_content), 0, 600) !!}...

						<div class="mt-2 mb-2">
							In topic: 
							<b><a target="_blank" href="{{ route('forum.topic', ['id' => $report->topic_id, 'slug' => $report->topic_slug]) }}">{{ $report->topic_title }}</a></b>
						</div>

						<b>{{ __('Post author') }}:</b><br>
						@if($report->reported_user_avatar) <img class="logged_user_avatar rounded-circle" style="max-height:20px" src="{{ thumb($report->reported_user_avatar) }}">@endif
						<b>{{ $report->reported_user_name}}</b> ({{ $report->reported_user_email }})
					</td>


					<td>
						<div class="mb-2 font-weight-bold">
							{{ __('This post have') }} {{ forum_post_reports($report->post_id)->total() ?? 0 }} {{ __('reports') }}
						</div>
						@foreach(forum_post_reports($report->post_id) as $report)
						@if($report->from_avatar) <img class="logged_user_avatar rounded-circle" style="max-height:20px" src="{{ thumb($report->from_avatar) }}">@endif
						{{ $report->from_name}} {{ __('at') }} {{ date_locale($report->created_at, 'datetime') }}

						@if($report->reason)
						<div class="mb-2 text-muted text-small">
							{!! nl2br($report->reason) !!}
						</div>
						<hr>
						@endif
						@endforeach
					</td>

					<td>
						@if($report->processed==1)
						<button class="btn btn-sm btn-success btn-block">{{ __('Processed') }}</button>
						@else
						<button class="btn btn-sm btn-warning btn-block">{{ __('Pending') }}</button>
						@endif
					</td>

					<td>
						@if($report->processed!=1)
						<button data-toggle="modal" data-target="#update-report-{{$report->id}}" class="btn btn-dark btn-sm btn-block"><i class="fas fa-pen"></i> {{ __('Warn / Restrict') }}</button>
						@include('admin.forum.modals.update-report-post')

						<form method="POST" action="{{ route('admin.forum.reports.posts.delete', ['id' => $report->id]) }}">
							{{ csrf_field() }}
							<button type="submit" class="float-right btn btn-danger btn-sm btn-block mt-3 delete-item-{{$report->id}}"><i class="fas fa-trash-alt"></i> {{ __('Delete report') }}</button>
						</form>

						<script>
							$('.delete-item-{{$report->id}}').click(function(e){
										e.preventDefault() // Don't post the form, unless confirmed
										if (confirm('Are you sure to delete this item?')) {
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

	{{ $reports->links() }}

</div>
<!-- end card-body -->