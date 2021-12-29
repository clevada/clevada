<div class="card-header">
	<h3><i class="fas fa-exclamation-triangle"></i> Reports ({{ $reports_pending ?? 0 }} pending)</h3>
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
		@if ($message=='updated') Updated @endif
		@if ($message=='deleted') Deleted @endif
	</div>
	@endif

	<div class="table-responsive-md">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="550">{{ __('Report details') }}</th>
					<th>{{ __('Forum details') }}</th>
					<th width="140">{{ __('Actions') }}</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($reports as $report)
				<tr>
					<td>
						<div class="float-right text-muted text-small">{{ nura_datetime($report->created_at) }}</div>
						{{ __('Reported by') }}<br>
						@if($report->from_avatar) <img class="logged_user_avatar rounded-circle" style="max-height:20px" src="/uploads/{{ nura_thumb($report->from_avatar) }}">@endif
						{{ $report->from_name}}

						<div class="mb-3"></div>
						{{ __('Reason') }}:<br>
						<div class="text-danger text-small">{!! nl2br($report->reason??null) !!}</div>
					</td>

					<td>
						@if (!$report->post_id)
						<button class="btn btn-sm btn-outline-danger">{{ __('Topic') }}</button>
						<b><a target="_blank" href="{{ route('forum.topic', ['id' => $report->topic_id, 'slug' => $report->topic_slug]) }}">{{ $report->topic_title }}</a></b>
						<div class="mt-2"></div>
						{!! substr(strip_tags($report->topic_content), 0, 400) !!}...						
						@else
						<button class="btn btn-sm btn-outline-warning">{{ __('Post') }}</button>
						in topic: <a target="_blank" href="{{ route('forum.topic', ['id' => $report->topic_id, 'slug' => $report->topic_slug]) }}">{{ $report->topic_title }}</a>
						<div class="mt-2"></div>
						{!! substr(strip_tags($report->post_content), 0, 400) !!}...
						@endif

						<div class="mt-2"></div>
						@if($report->reported_user_avatar) <img class="logged_user_avatar rounded-circle" style="max-height:20px" src="/uploads/{{ nura_thumb($report->reported_user_avatar) }}">@endif
						<b>{{ $report->reported_user_name}}</b> ({{ $report->reported_user_email }})
					</td>					

					<td>
						<button data-toggle="modal" data-target="#update_report_{{$report->id}}" class="btn btn-dark btn-sm btn-block"><i class="fas fa-pen"></i> {{ __('Manage') }}</button>
						@include('admin.forum.modals.update_report')
					</td>
				</tr>
				@endforeach

			</tbody>
		</table>
	</div>

	{{ $reports->links() }}

</div>
<!-- end card-body -->