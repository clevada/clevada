<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="add-account-note" aria-hidden="true" id="add-account-note">
	<div class="modal-dialog">
		<div class="modal-content">

			<form method="post" enctype="multipart/form-data">
				@csrf

				<div class="modal-header">
					<h5 class="modal-title" id="add-account-note">{{ __('Add internal note') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">

					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label>{{ __('Internal note') }}</label>
								<textarea class="form-control editor" name="note" rows="6" required></textarea>									
							</div>
							
							<div class="form-group">
								<label>{{ __('Upload file') }} ({{ __('optional') }})</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="validatedCustomFile" name="file">
									<label class="custom-file-label" for="validatedCustomFile">{{ __('Choose file') }}...</label>
								</div>
							</div>

							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input id="checkbox_sticky" type="checkbox" name="sticky" class="custom-control-input">
									<label for="checkbox_sticky" class="custom-control-label"> {{ __('Sticky') }}</label>
								</div>
							</div>
						</div>
						
					</div>

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ __('Add internal note') }}</button>
				</div>

			</form>

		</div>
	</div>
</div>