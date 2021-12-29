<div class="account-header">
	<h3><i class="fas fa-exclamation-triangle"></i> {{ __('My forum restrictions') }} ({{ $restrictions->total() ?? 0 }})</h3>
</div>


@if(count($restrictions)==0)
{{ __('No restriction') }}
@else
<div class="table-responsive-md">

	<table class="table table-bordered table-hover">
		<tbody>

			@foreach ($restrictions as $restriction)
			<tr>
				<td>
					@if($restriction->deny_topic_create_days>0)
					{{ __('You can not create new topic until') }} {{ date_locale($restriction->deny_topic_create_expire_at, 'datetime') }}
					@if($restriction->deny_topic_create_expire_at<$today) <div class="text-danger bold">{{ __('EXPIRED') }}
</div>
@endif
<br>
@endif

@if($restriction->deny_post_create_days>0)
{{ __('You can not write posts until') }} {{ date_locale($restriction->deny_post_create_expire_at, 'datetime') }}
@if($restriction->deny_post_create_expire_at<$today) <div class="text-danger bold">{{ __('EXPIRED') }}</div>@endif
	@endif

	<div class="text-muted small mt-2">{{ __('Restriction from') }} {{ date_locale($restriction->created_at, 'datetime') }}</div>
	</td>
	</tr>
	@endforeach

	</tbody>
	</table>
	</div>
	@endif

	{{ $restrictions->links() }}