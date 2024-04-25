<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config', ['module' => 'whitelabel']) }}">{{ __('Whitelabel') }}</a></li>
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

        <form method="post" enctype="multipart/form-data">
            @csrf

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated')
                        {{ __('Updated') }}
                    @endif
                </div>
            @endif



            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="form-group mb-3">
                        <label for="logo_backend" class="form-label">{{ __('Logo for backend area') }}</label>
                        <input class="form-control" type="file" id="logo_backend" name="logo_backend">
                    </div>

                    <div class="form-text">{{ __('PNG, JPG, JPEG or GIF. Maximum 64 x 200 px. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}
                    </div>                  

                    @if ($config->logo_backend ?? null)
                        <br><img class="img-fluid" src="{{ image($config->logo_backend) }}" />
                    @endif
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="form-group mb-3">
                        <label for="logo_auth" class="form-label">{{ __('Logo for authentification pages') }}</label>
                        <input class="form-control" type="file" id="logo_auth" name="logo_auth">
                    </div>

                    <div class="form-text">{{ __('PNG, JPG, JPEG or GIF. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}</div>                  

                    @if ($config->logo_auth ?? null)
                        <br><img class="img-fluid" src="{{ image($config->logo_auth) }}" />
                    @endif
                </div>

                <div class="col-md-3 col-sm-12 col-12">
                    <div class="form-group mb-3">
                        <label>{{ __('Author name - for copyright / "powered by" credits') }}</label>
                        <div class="custom-file">
                            <input type="text" class="form-control" name="site_meta_author" value="{{ $config->site_meta_author ?? null }}" aria-describedby="authorHelp">
                            <small id="authorHelp" class="form-text text-muted">{{ __('Author name or company (1-4 words)') }}</small>
                        </div>                       
                    </div>
                </div>

                <div class="col-md-3 col-sm-12 col-12">
                    <div class="form-group  mb-3">
                        <label>{{ __('Author URL') }} ({{ __('optional') }})</label>
                        <div class="custom-file">
                            <input type="text" class="form-control" name="site_meta_author_url" value="{{ $config->site_meta_author_url ?? null }}" aria-describedby="authorUrlHelp">
                            <small id="authorUrlHelp" class="form-text text-muted">{{ __('https://www.website.com') }}</small>                         
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group mb-3 mt-3">
                        <label>{{ __('"Powered by"Footer text') }}</label>
                        <div class="custom-file">
                            <input type="text" class="form-control" name="backend_powered_by" value="{{ $config->backend_powered_by ?? null }}" aria-describedby="poweredByHelp">
                            <small id="poweredByHelp" class="form-text text-muted">{{ __('Used in admin and staff accounts area. HTML tags are allowed') }}</small>                          
                        </div>
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
