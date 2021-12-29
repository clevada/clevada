<div class="account-header">
	<h3><i class="fas fa-exclamation-triangle"></i> {{ __('My forum warnings') }} ({{ $warnings->total() ?? 0 }})</h3>
</div>

@if(count($warnings)==0)
{{ __('No warning') }}
@else
<div class="table-responsive-md">

	<table class="table table-bordered table-hover">
		<tbody>

			@foreach ($warnings as $warning)
			<tr>
				<td>
					<p>{!! nl2br($warning->warning) !!}</p>
					<div class="text-muted small mt-2">{{ date_locale($warning->created_at, 'datetime') }}</div>
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>
</div>
@endif

{{ $warnings->links() }}