<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template') }}">{{ __('Template') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.template.logo') }}">{{ __('Logo and icons') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-3">
                @include('admin.template.includes.menu-template')
            </div>

            <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Logo and icons') }}</h4>
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
        
        @if ($config->website_disabled ?? null)
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __('Public website is disabled.') }} <a href="{{ route('admin.config', ['module' => 'general']) }}">{{ __('Change') }}</a>
            </div>
        @endif

        @if ($config->website_maintenance_enabled ?? null)
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __('Website is in maintenance mode.') }} <a href="{{ route('admin.website.config') }}">{{ __('Change') }}</a>
            </div>
        @endif

        @if (! ($config->logo ?? null))
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __("Warning. Website logo is missing.") }}
            </div>
        @endif

        @if (! ($config->favicon ?? null))
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __("Warning. Website favicon is missing.") }}
            </div>
        @endif

        <form method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <label for="logo" class="form-label">{{ __('Main logo') }} ({{ __('required') }})</label>
                    <input class="form-control" aria-describedby="logoHelp" id="logo" type="file" name="logo">
                    <div class="text-muted small">
                        {{ __('This logo is displayed on navigation. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}
                    </div>

                    @if ($config->logo ?? null)
                        <br><img class="img-fluid" src="{{ asset('uploads/'.$config->logo) }}" />
                    @endif
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <label for="favicon" class="form-label">{{ __('Favicon') }} ({{ __('required') }})</label>
                    <input type="file" class="form-control" id="favicon" name="favicon" aria-describedby="faviconHelp">
                    <div class="text-muted small">
                        {{ __('PNG, JPG, JPEG or GIF. Recomended file type: 32px x 32px. Note: Original image will be uploaded, without any crop or resize.') }}
                    </div>

                    @if ($config->favicon ?? null)
                        <br><img class="img-fluid" src="{{ asset('uploads/'.$config->favicon) }}" />
                    @endif
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <label for="logo_auth" class="form-label">{{ __('Logo for auth') }} ({{ __('optional') }})</label>
                    <input type="file" class="form-control" id="logo_auth" name="logo_auth">
                    <div class="text-muted small">
                        {{ __('PNG, JPG, JPEG or GIF. Recomended file type: 32px x 32px. Note: Original image will be uploaded, without any crop or resize.') }}
                    </div>

                    @if ($config->logo_auth ?? null)
                        <br><img class="img-fluid" src="{{ asset('uploads/'.$config->logo_auth) }}" />
                    @endif
                </div>
            </div>


            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
