<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Configuration') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                @include('admin.core.layouts.menu-config')
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
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif

        <form method="post">
            @csrf

            <div class="fw-bold mb-2 mt-1">
                {{ __('Website status') }}:
                @if ($config->website_maintenance_enabled ?? null)
                    <span class="text-danger badge bg-warning text-uppercase">
                        {{ __('maintenance mode') }}
                    </span>
                @else
                    <span class="text-white badge bg-success text-uppercase">
                        {{ __('active') }}
                    </span>
                @endif
            </div>


            <div class="form-group mt-1 mb-0">
                <div class="form-check form-switch">
                    <input type="hidden" value="" name="website_maintenance_enabled">
                    <input class="form-check-input" type="checkbox" id="website_maintenance" name="website_maintenance_enabled" @if ($config->website_maintenance_enabled ?? null) checked @endif>
                    <label class="form-check-label" for="website_maintenance">{{ __('Enable maintenance mode') }}</label>
                </div>
            </div>

            <div class="text-muted mb-3">
                <i class="bi bi-info-circle"></i>
                {{ __('If enabled, public website can not be accessible by visitors and registered users can not use their accounts. Only administrators can use their accounts and see the website.') }}
            </div>

            <script>
                $('#website_maintenance').change(function() {
                    select = $(this).prop('checked');
                    if (select)
                        document.getElementById('hidden_div').style.display = 'block';
                    else
                        document.getElementById('hidden_div').style.display = 'none';
                })
            </script>

            <div id="hidden_div" @if ($config->website_maintenance_enabled ?? null) style="display: visible" @else style="display: none" @endif>
                <div class="form-group col-12">
                    <label>{{ __('Add a custom text for maintenance page') }} </label>
                    <textarea name="website_maintenance_text" class="form-control" rows="5">{!! $config->website_maintenance_text ?? null !!}</textarea>
                    <div class="text-muted small">{{ __('Tip: you can use HTML code.') }}</div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Website author') }}</label>
                        <input type="text" class="form-control" name="site_author" value="{{ $config->site_author ?? null }}">
                        <small class="text-muted small">{{ __('Used in "meta author" tag') }}</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Website label') }}</label>
                        <input type="text" class="form-control" name="site_label" value="{{ $config->site_label ?? null }}">
                        <small class="text-muted small">{{ __('A short website title (1-3 words)') }}</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Website language') }}</label>
                        <select name="locale" class="selectpicker" data-live-search="true" title="{{ __('Select') }}" required>
                            @foreach (App\Models\Locale::lang_codes_array() as $key => $lang_code)
                                @if ($key == 'divider')
                                    <option disabled>-----------------</option>
                                @else
                                    <option @if (($config->locale ?? config('app.locale')) == $lang_code) selected @endif value="{{ $lang_code }}">{{ $key }} ({{ $lang_code }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>


                {{--
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Website language') }}</label>
                        <select name="locale" class="selectpicker" data-live-search="true" title="{{ __('Select') }}" required>
                            <option @if ($locale ?? config('app.locale')) selected @endif value="">- {{ __('Select') }} -</option>
                            @foreach (App\Models\Locale::locales_array() as $key => $locale)
                                <option @if (($locale ?? null) == $key) selected @endif value="{{ $key }}">{{ $locale }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                --}}

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Timezone') }}</label>
                        <select name="timezone" class="selectpicker" data-live-search="true" title="{{ __('Select') }}" required>
                            <option selected value="UTC">UTC</option>
                            @foreach (App\Models\Locale::generate_timezone_list() as $key => $timezone)
                                <option @if (($config->timezone ?? config('app.timezone')) == $key) selected @endif value="{{ $key }}">{{ $timezone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Text direction') }}</label>
                        <select name="site_text_dir" class="form-select" aria-describedby="activeFrontendHelp">
                            <option @if (($config->site_text_dir ?? null) == 'ltr') selected @endif value="ltr">{{ __('LTR (Left to Right)') }}</option>
                            <option @if (($config->site_text_dir ?? null) == 'rtl') selected @endif value="rtl">{{ __('RTL (Right to Left)') }}</option>
                        </select>
                    </div>
                </div>

                <div class="fw-bold fs-5 mt-2">{{ __('Website home page') }}</div>


                <div class="form-group">
                    <label>{{ __('Home page meta title') }}</label>
                    <input type="text" class="form-control" name="site_meta_title" value="{{ $config->site_meta_title ?? null }}">
                    <small class="text-muted small">{{ __('This is used as site meta title. Recomended: maximum 60 characters') }}</small>
                </div>

                <div class="form-group">
                    <label>{{ __('Home page meta description') }}</label>
                    <input type="text" class="form-control" name="site_meta_description" value="{{ $config->site_meta_description ?? null }}">
                    <small class="text-muted small">{{ __('Include relevant information about the website content in the description.') }}</small>
                </div>                
            </div>

            <div class="form-group mt-3">
                <input type="hidden" name="section" value="general">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </form>

    </div>
    <!-- end card-body -->

</div>
