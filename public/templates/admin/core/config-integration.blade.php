<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config.integration') }}">{{ __('Integration') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

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
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif


            <form method="post">
                @csrf

                <h5>{{ __('Google reCAPTCHA') }}</h5>
                <p>{{ __('Create site / secret keys here') }}: <a target="_blank" href="https://www.google.com/recaptcha/admin/create"><b>{{ __('Create Google reCAPTCHA keys') }}</b></a></p>

                <div class="row">

                    <div class="form-group">
                        @if (!($config->google_recaptcha_enabled ?? null))
                            <div class="alert alert-light text-danger">{{ __('Google reCAPTCHA is disabled') }}</div>
                        @endif

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

                <hr>

                <h5>{{ __('Google Analytics') }}</h5>
                <p class="form-text">{{ __('Get this code from') }} <a target="_blank" href="https://google.com/analytics"><b>{{ __('Google Analytics account') }}</b></a></p>

                <div class="form-group col-md-4 col-12">
                    <label>{{ __('Google Analytics parameter') }} (UA-XXXXX-Y):</label>
                    <input type="text" class="form-control" name="google_analytics_ua" aria-describedby="analyticsHelp" value="{{ $config->google_analytics_ua ?? null }}">
                </div>

                <hr>

                <h5>{{ __('AddThis share buttons') }}</h5>
                <p class="form-text">{{ __('You can use AddThis to add social share buttons in your articles or pages.') }} <a target="_blank"
                        href="https://www.addthis.com/dashboard"><b>{{ __('Get the code from your AddThis dashboard') }}</b></a></p>

                <div class="form-group col-md-4 col-12">
                    <label>{{ __('AddThis code') }}:</label>
                    <textarea rows="3" class="form-control" name="addthis_code">{{ $config->addthis_code ?? null }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
