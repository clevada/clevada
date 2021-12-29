<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="update-tag-{{ $tag->id }}" aria-hidden="true" id="update-tag-{{ $tag->id }}">
	<div class="modal-dialog">
		<div class="modal-content">

			<form action="{{ route('admin.accounts.tags.show', ['id' => $tag->id]) }}" method="post">
				@csrf
				@method('PUT')

				<div class="modal-header">
					<h5 class="modal-title" id="update-tag-{{ $tag->id }}">{{ __('Update account tag') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">

					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label>{{ __('Tag') }}</label>
								<input class="form-control" name="tag" type="text" required value="{{ $tag->tag }}" />
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label>{{ __('Color') }}</label>
								<input type="color" id="color_{{ $tag->id }}" class="form-control" name="color" value="{{ $tag->color }}">
							</div>
						</div>
								
						<div class="col-lg-6">
							<div class="form-group">
								<label>{{ __('Role') }}</label>
								<select name="role_id" class="form-control" required>
									<option value="">- {{ __('select') }} -</option>
									@foreach ($active_roles as $role)
									<option @if ($tag->role_id == $role->id) selected @endif value="{{ $role->id }}">
										@switch($role->role)
										@case('admin')
										{{ __('Administrator') }}
										@break
										
										@case('user')
										{{ __('Registered user') }}
										@break

										@case('internal')
										{{ __('Internal') }}
										@break

										@case('vendor')
										{{ __('Vendor') }}
										@break

										@default
										{{ $user->role }}
										@endswitch
									</option>
									@endforeach
								</select>
							</div>
						</div>

					</div>					

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ __('Update account tag') }}</button>
				</div>

			</form>

		</div>
	</div>
</div>