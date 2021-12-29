<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true" id="add-account-tag">
	<div class="modal-dialog">
		<div class="modal-content">

			<form method="post">
				@csrf

				<div class="modal-header">
					<h5 class="modal-title" id="addLabel">{{ __('Add tag') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">

					<div class="row">
						<div class="col-lg-12">

							@if(count($all_tags) == 0) {{ __("You don't have any tag. Go to 'Manage tags' to add tags") }}.

							<a class="btn btn-primary mt-3" href="{{ route('admin.accounts.tags') }}"><i class="bi bi-gear"></i> {{ __('Manage tags') }}</a>

							@else
							<div class="form-group">
								<label>{{ __('Tag') }}</label>
								<select class="form-control" name="tag_id" type="text" required>
									<option value="">- {{ __('select') }} -</option>
									@foreach($all_tags as $tag)
									<option value="{{ $tag->id }}">{{ $tag->tag }}</option>
									@endforeach
								</select>
							</div>
							@endif
						</div>

					</div>

				</div>

				@if(count($all_tags) != 0) 
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ __('Add tag') }}</button>
				</div>
				@endif

			</form>

		</div>
	</div>
</div>