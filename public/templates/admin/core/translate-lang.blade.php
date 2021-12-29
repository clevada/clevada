<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.config.langs') }}">{{ __('Languages') }}</a></li>
					<li class="breadcrumb-item active"><a href="{{ route('admin.translates') }}">{{ __('Translates') }}</a></li>
					<li class="breadcrumb-item active" aria-current="page">{{ $lang->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Translates for') }} {{ $lang->name }} ({{ $lang_keys->total() }} {{ __('keys total') }}, {{ $translated_keys }} {{ __('keys translated') }})</h4>
                </div>

				<div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
					<div class="float-end">						
						<a class="btn btn-danger mr-3" href="{{ route('admin.translates.regenerate_lang_file', ['locale' => $locale, 'lang_id' => $lang->id]) }}">{{ __('Update translations file ') }} - {{ $lang->name }}</a>
						<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-translate-key"><i class="bi bi-plus-circle"></i> {{ __('Add new translate key') }}</button>
						@include('admin.core.modals.create-translate-key')
					</div>
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
		@if ($message=='created') {{ __('Created') }} @endif
		@if ($message=='updated') {{ __('Updated') }}<br><i class="bi bi-exclamation-circle"></i> <b>{{ __('After you finish to translate keys, you MUST click on the button for the changes to take effect') }}</b> @endif
		@if ($message=='deleted') {{ __('Deleted') }} @endif
		@if ($message=='regenerated') {{ __('Translation file updated') }} @endif
	</div>
	@endif

	@if ($message = Session::get('error'))
	<div class="alert alert-danger">
		@if ($message=='error_file') {{ __('Error. Choose file') }} @endif
		@if ($message=='duplicate') {{ __('Error. This language key exist') }} @endif
	</div>
	@endif


	<section>
		<form action="{{ route('admin.translate_lang', ['id' => $lang->id]) }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

			<div class="col-12">
			<input type="text" name="search_terms" placeholder="{{ __('Search key') }}" class="form-control @if($search_terms) is-valid @endif me-2" value="{{ $search_terms ?? null }}" />
			</div>

			<div class="col-12">
			<input type="hidden" name="id" value="{{ $lang->id }}">
			<button class="btn btn-secondary me-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
			<a class="btn btn-light" href="{{ route('admin.translate_lang', ['id' => $lang->id]) }}"><i class="bi bi-arrow-counterclockwise"></i></a>
			</div>
		</form>
	</section>

	<div class="mb-3"></div>

	<div class="table-responsive-md">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="450">{{ __('Language key') }}</th>
					<th width="150">{{ __('Area') }}</th>
					<th>{{ __('Translates') }} ({{ $lang->name }})</th>
				</tr>
			</thead>
			<tbody>

				@foreach ($lang_keys as $lang_key)
				<tr>

					<td>
						<h5>{{ $lang_key->lang_key }}</h5>

						<div class="mb-4"></div>

						<div class="d-flex">
							<button data-bs-toggle="modal" data-bs-target="#modal-update-translate-key-{{ $lang_key->id }}" class="btn btn-primary btn-sm me-3"><i class="bi bi-pencil-square" aria-hidden="true"></i></button>
							@include('admin.core.modals.update-translate-key')

							<form method="POST" action="{{ route('admin.translates.delete_key', ['key_id' => $lang_key->id, 'lang_id' => $lang->id]) }}">
								@csrf
								<button type="submit" class="float-right btn btn-danger btn-sm delete-item-{{$lang_key->id}}"><i class="bi bi-trash"></i></button>
							</form>
							<script>
								$('.delete-item-{{$lang_key->id}}').click(function(e){
											e.preventDefault() // Don't post the form, unless confirmed
											if (confirm("{{ __('Are you sure to delete this item?') }}'")) {
												$(e.target).closest('form').submit() // Post the surrounding form
											}
										});
							</script>
						</div>
					</td>

					<td>
						<h5>{{ $lang_key->area }}</h5>
					</td>

					<td>
						<form action="{{ route('admin.translates.update_translate') }}" method="POST">
							@csrf
							<textarea class="form-control" name="translate" rows="2">{{ $lang_key->translate ?? null }}</textarea>
							<input type="hidden" name="lang_id" value="{{ $lang->id }}">
							<input type="hidden" name="key_id" value="{{ $lang_key->id }}">
							<button type="submit" class="btn btn-primary btn-sm btn-dark mt-2">{{ __('Update') }}</button>
						</form>
						<hr>

					</td>

				</tr>
				@endforeach

			</tbody>
		</table>
	</div>

	{{ $lang_keys->appends(['id' => $lang_id, 'search_terms' => $search_terms])->links() }}

</div>
<!-- end card-body -->

	</div>

</section>