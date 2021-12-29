<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" id="create-tag">
	<div class="modal-dialog">
		<div class="modal-content">

			<form method="post">
				@csrf

				<div class="modal-header">
					<h5 class="modal-title" id="createLabel">{{ __('Create account tag') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">

					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label>{{ __('Tag') }}</label>
								<input class="form-control" name="tag" type="text" required />
							</div>
						</div>						
								
						<div class="col-lg-6">
							<div class="form-group">
								<label>{{ __('Role') }}</label>
								<select name="role_id" class="form-select" required>
									<option value="">- {{ __('select') }} -</option>
									@foreach ($active_roles as $role)
									<option value="{{ $role->id }}">
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

						<div class="col-lg-6">
							<div class="form-group">
								<label>{{ __('Color') }}</label><br>
								<input class="form-control form-control-color" id="color" name="color" value="#b7b7b7">
                                <script>
                                    $('#color').spectrum({
                                        type: "color",
                                        showInput: true,
                                        showInitial: true,
                                        showAlpha: false,
                                        showButtons: false,
                                        allowEmpty: false,
                                    });
                                </script>
							</div>
						</div>

					</div>
					
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ __('Create account tag') }}</button>
				</div>

			</form>

		</div>
	</div>
</div>