<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Clevada Pro') }}</li>
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
                    <h4 class="card-title">{{ __('Clevada Pro') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            <form method="post" enctype="multipart/form-data">
                @csrf

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        @if ($message == 'updated') {{ __('Updated') }} @endif
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        @if ($message == 'error_name') {{ __('Error. Invalid license key') }} @endif
                    </div>
                @endif

                @if (($clevada_pro_active ?? null) == 0)
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <b>{{ __('Your Clevada Pro is not active') }}</b>
                    </div>
                @else
                    <h4 class="text-success mb-4"><i class="bi bi-exclamation-triangle"></i>
                        {{ __('Your Clevada Pro features expire on') }}: {{ date_locale($clevada_pro_expire_at) }}<br>
                        <small>{{ __('After this date, your pro features will be disabled. Please be careful to update your payment before expiration') }}</small>
                    </h4>
                @endif

                <h4>{{ __('Clevada Pro advantages') }}:</h4>
                <ul class='fs-5'>
                    <li>{!! __('Access to <b>automatic update</b> (one-click update directly from the admin area)') !!}.</li>
                    <li>{!! __('Remove <b>footer copyright credentials and links</b> to Clevada.com website from all templates in footer area') !!}.</li>
                    <li>{!! __('Remove all credentials and links to Clevada.com website from <b>login and registration pages</b>') !!}.</li>
                    <li>{!! __('Remove all credentials and signature links to Clevada.com website from all <b>emails</b> (registration email, notification emails...)') !!}</li>
                    <li>{!! __('Remove all Clevada <b>links and logos from all business modules</b> (tasks, projects, bookings,...) and replace woth your website link and logo') !!}</li>
                    <li>{!! __('Use your own <b>logo / icon</b> in admin area and staff accounts') !!}.</li>
                    <li>{!! __('Use your own <b>name or company name</b> in "author" meta tag from all pages (backend and frontend)') !!}.</li>
                </ul>


                <div class="fw-bold mb-2">{{ __('Go to your Clevada account to manage Clevada Pro feature for this domain') }}:</div>
                <a class="btn btn-primary mb-3" target="_blank" href="https://clevada.com/login/user/pro"><i class="bi bi-check"></i> {{ __('Renew Pro for 4 USD / month only') }}</a>

                <hr>

                <h4>{{ __('Clevada Pro settings') }}:</h4>


                <div class="col-md-3 col-sm-12 col-12 mb-4">
                    <label>{{ __('Clevada Pro key') }} ({{ __('required') }})</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="license_key" value="{{ $config->license_key ?? null }}">
                        <div class="form-text fw-bold">{{ __('Get key from your Clevada account') }}. <a target="_blank" href="https://clevada.com/login/user/pro"> {{ __('Go to my Clevada account') }}</a></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="logo_backend" class="form-label">{{ __('Logo for admininstration and staff area') }}</label>
                            <input class="form-control" type="file" id="logo_backend" name="logo_backend">
                        </div>

                        <div class="form-text">{{ __('PNG, JPG, JPEG or GIF. Maximum 64 x 200 px. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}
                        </div>
                        @if (($clevada_pro_active ?? null) == 0)<div class="form-text text-danger fw-bold">{{ __('This image can not be used until you have a valid license') }}</div>@endif

                        @if ($config->logo_backend ?? null)
                            <br><img class="img-fluid" src="{{ image($config->logo_backend) }}" />
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="logo_auth" class="form-label">{{ __('Logo for login and registration pages') }}</label>
                            <input class="form-control" type="file" id="logo_auth" name="logo_auth">
                        </div>

                        <div class="form-text">{{ __('PNG, JPG, JPEG or GIF. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}</div>
                        @if (($clevada_pro_active ?? null) == 0)<div class="form-text text-danger fw-bold">{{ __('This image can not be used until you have a valid license') }}</div>@endif

                        @if ($config->logo_auth ?? null)
                            <br><img class="img-fluid" src="{{ image($config->logo_auth) }}" />
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-12 col-12">
                        <label>{{ __('Author name - for copyright / "powered by" credits') }}</label>
                        <div class="custom-file">
                            <input type="text" class="form-control" name="site_meta_author" value="{{ $config->site_meta_author ?? null }}" aria-describedby="authorHelp">
                            <small id="authorHelp" class="form-text text-muted">{{ __('Author name or company (1-4 words)') }}</small>
                        </div>
                        @if (($clevada_pro_active ?? null) == 0)<div class="form-text text-danger fw-bold">{{ __('This settings can not be used until you have a valid license') }}</div>@endif
                    </div>

                    <div class="col-md-3 col-sm-12 col-12">
                        <label>{{ __('Author URL') }} ({{ __('optional') }})</label>
                        <div class="custom-file">
                            <input type="text" class="form-control" name="site_meta_author_url" value="{{ $config->site_meta_author_url ?? null }}" aria-describedby="authorUrlHelp">
                            <small id="authorUrlHelp" class="form-text text-muted">{{ __('https://www.website.com') }}</small>
                            @if (($clevada_pro_active ?? null) == 0)<div class="form-text text-danger fw-bold">{{ __('This settings can not be used until you have a valid license') }}</div>@endif
                        </div>
                    </div>

                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
