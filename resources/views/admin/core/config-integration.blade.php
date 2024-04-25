<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config', ['module' => 'integration']) }}">{{ __('Integration') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-body">

        <div class="col-12">
            @include('admin.core.layouts.menu-config')
        </div>

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

            <div class="fw-bold mt-3 mb-1 fs-5">
                {{ __('Google reCAPTCHA') }}
                @if (!($config->google_recaptcha_enabled ?? null))
                    <span class="badge bg-danger fw-normal">{{ __('Disabled') }}</span>
                @else
                    <span class="badge bg-success fw-normal">{{ __('Enabled') }}</span>
                @endif
            </div>

            <div class="text-muted mb-2">
                {{ __('Create site / secret keys here') }}: <a target="_blank" href="https://www.google.com/recaptcha/admin/create">{{ __('Create Google reCAPTCHA keys') }}</a>
            </div>


            <div class="row">
                <div class="form-group">
                    <div class="form-check form-switch">
                        <input type="hidden" name="google_recaptcha_enabled" value="">
                        <input class="form-check-input" type="checkbox" id="SwitchRecaptcha" name="google_recaptcha_enabled" @if ($config->google_recaptcha_enabled ?? null) checked @endif>
                        <label class="form-check-label" for="SwitchRecaptcha">{{ __('Enable Google reCAPTCHA') }}</label>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>{{ __('Google reCAPTCHA site key') }}:</label>
                        <input class="form-control" name="google_recaptcha_site_key" value="{!! $config->google_recaptcha_site_key ?? null !!}" />
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>{{ __('Google reCAPTCHA secrtet key') }}:</label>
                        <input type="password" class="form-control" name="google_recaptcha_secret_key" value="{!! $config->google_recaptcha_secret_key ?? null !!}" />
                    </div>
                </div>
            </div>


            <div class="fw-bold mt-4 mb-1 fs-5">
                {{ __('Google Analytics') }}
                @if (!($config->google_analytics_enabled ?? null))
                    <span class="badge bg-danger fw-normal">{{ __('Disabled') }}</span>
                @else
                    <span class="badge bg-success fw-normal">{{ __('Enabled') }}</span>
                @endif
            </div>

            <div class="text-muted mb-2">
                {{ __('Get this code from') }} <a target="_blank" href="https://google.com/analytics">{{ __('Google Analytics account') }}</a>
            </div>

            <div class="row">
                <div class="form-group">
                    <div class="form-check form-switch">
                        <input type="hidden" name="google_analytics_enabled" value="">
                        <input class="form-check-input" type="checkbox" id="SwitchAnalytics" name="google_analytics_enabled" @if ($config->google_analytics_enabled ?? null) checked @endif>
                        <label class="form-check-label" for="SwitchAnalytics">{{ __('Enable Google analytics') }}</label>
                    </div>
                </div>

                <div class="form-group col-md-4 col-12">
                    <label>{{ __('Google Analytics ID') }} (UA-XXXXX-Y or G-XXXXXX):</label>
                    <input type="text" class="form-control" name="google_analytics_id" value="{{ $config->google_analytics_id ?? null }}">
                </div>
            </div>



            <div class="fw-bold mt-4 mb-1 fs-5">
                {{ __('AddToAny share buttons') }}
                @if (!($config->addtoany_enabled ?? null))
                    <span class="badge bg-danger fw-normal">{{ __('Disabled') }}</span>
                @else
                    <span class="badge bg-success fw-normal">{{ __('Enabled') }}</span>
                @endif
            </div>

            <div class="text-muted mb-2">
                {{ __('Get share code from') }} <a target="_blank" href="https://addtoany.com">{{ __('AddToAny website') }}</a>
            </div>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input type="hidden" name="addtoany_enabled" value="">
                    <input class="form-check-input" type="checkbox" id="SwitchAdtoany" name="addtoany_enabled" @if ($config->addtoany_enabled ?? null) checked @endif>
                    <label class="form-check-label" for="SwitchAdtoany">{{ __('Enable AddToAny share button') }}</label>
                </div>
            </div>

            <div class="form-group col-md-4 col-12">
                <label>{{ __('AddToAny button code code') }}:</label>
                <textarea rows="6" class="form-control" name="addtoany_code">{{ $config->addtoany_code ?? null }}</textarea>
            </div>


            <div class="form-group mt-4 mb-0">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
