<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.config.langs') }}">{{ __('Languages') }}</a></li>
					<li class="breadcrumb-item active" aria-current="page">{{ __('Translates') }}</li>
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
                    <h4 class="card-title">{{ __('Translations') }} ({{ $count_keys }} {{ __('translate keys') }})</h4>
                </div>

				<div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
					<div class="float-end">
						<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-translate-key"><i class="bi bi-plus-circle"></i> {{ __('Add new translate key') }}</button>
						@include('admin.core.modals.create-translate-key')
					</div>
				</div>

            </div>
        </div>

        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                    @if ($message == 'regenerated') {{ __('Translation file updated') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'error_file') {{ __('Error. Choose file') }} @endif
                    @if ($message == 'duplicate') {{ __('Error. This language key exist') }} @endif
                </div>
            @endif

            <div class="alert alert-info">
                {{ __('Info: You cau automatic scan template files to automatic add all translate keys into database') }}
            </div>


            <section>
                <form class="row row-cols-lg-auto g-3 align-items-center" method="post" action="{{ route('admin.translates.scan_template') }}">
                    @csrf

                    <div class="col-12">
                        <select name="template" class="form-select me-2" required>
                            <option value=''>- {{ __('Select') }} -</option>
                            <option value="templates/frontend/builder">{{ __('Frontend template') }}</option>
                            <option value="templates/admin">{{ __('Admin area') }}</option>
                            <option value="templates/user">{{ __('Users area') }}</option>
                            <option value="templates/auth">{{ __('Auth area') }}</option>
                            <option value="templates/emails">{{ __('Emails templates') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-secondary">{{ __('Scan files') }}</button>
                    </div>
                </form>
            </section>

			<div class="mb-3"></div>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Language') }}</th>
                            <th width="260">{{ __('Statistics') }}</th>
                            <th width="200">{{ __('Translate') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($langs as $lang)
                            <tr @if ($lang->status != 'active') class="table-warning" @endif>

                                <td>
                                    @if ($lang->status == 'inactive') <span class="float-end"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Inactive') }}</button> </span> @endif
                                    @if ($lang->status == 'disabled') <span class="float-end"><button type="button" class="btn btn-danger btn-sm disabled">{{ __('Disabled') }}</button> </span> @endif
                                    @if ($lang->is_default == 1) <span class="float-end"><button type="button" class="btn btn-info btn-sm disabled">{{ __('Default Language') }}</button> </span> @endif
                                    <div class="listing fw-bold">{{ $lang->name }}</div>
                                    {{ __('Code') }}: <b>{{ $lang->code }}</b>
                                </td>

                                <td>
                                    <b>
                                        @if ($count_keys - $lang->count_translated_keys > 0)
                                            <span class="text-danger"> {{ $count_keys - $lang->count_translated_keys }} {{ __('untranslated keys') }}</span>
                                        @else
                                            <span class="text-success"> {{ __('translation completed') }}</span>
                                        @endif
                                    </b>
                                </td>

                                <td>
                                    <a class="btn btn-primary" href="{{ route('admin.translate_lang', ['id' => $lang->id]) }}">{{ __('Manage translations') }}</a>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
        <!-- end card-body -->

    </div>

</section>
