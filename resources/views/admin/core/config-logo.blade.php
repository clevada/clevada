<div class="card-header">
    @include('admin.core.layouts.menu-template')
</div>
<!-- end card-header -->


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
            <i class="bi bi-info-circle"></i> {{ __('Website is in maintenance mode.') }}
        </div>
    @endif

    <form method="post" enctype="multipart/form-data">
        @csrf
        
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-12">
                <label>{{ __('Main Logo') }}</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="logo" name="logo" aria-describedby="logoHelp">
                    <label class="custom-file-label" for="logo">{{ __('Choose file') }}...</label>
                </div>
                <small id="logoHelp"
                    class="form-text text-muted">{{ __('PNG, JPG, JPEG or GIF. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}</small>

                @if ($config->logo ?? null)
                    <br><img style="max-width:200px; height:auto;" src="{{ image($config->logo->code) }}" />
                @endif
            </div>

            <div class="form-group col-md-3 col-sm-6 col-12">
                <label>{{ __('Alternative logo') }} ({{ __('for light background') }})</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="logo_alt" name="logo_alt" aria-describedby="logo_altHelp">
                    <label class="custom-file-label" for="logo_alt">{{ __('Choose file') }}...</label>
                </div>
                <small id="logo_altHelp"
                    class="form-text text-muted">{{ __('PNG, JPG, JPEG or GIF. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}</small>

                @if ($config->logo_alt ?? null)
                    <br><img style="max-width:200px; height:auto;" src="{{ image($config->logo_alt) }}" />
                @endif
            </div>

            <div class="form-group col-md-3 col-sm-6 col-12">
                <label>Favicon</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="favicon" name="favicon" aria-describedby="faviconHelp">
                    <label class="custom-file-label" for="favicon">{{ __('Choose file') }}...</label>
                </div>
                <small id="faviconHelp" class="form-text text-muted">{{ __('PNG, JPG, JPEG or GIF. Recomended file type: 32px x 32px. Note: Original image will be uploaded, without any crop or resize.') }}</small>

                @if ($config->favicon ?? null)
                    <br><img style="max-width:200px; height:auto;" src="{{ image($config->favicon) }}" />
                @endif
            </div>

            <div class="form-group col-md-3 col-sm-6 col-12">
                <label>Big image</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="logo_big" name="logo_big" aria-describedby="logo_bigHelp">
                    <label class="custom-file-label" for="favicon">{{ __('Choose file') }}...</label>
                </div>
                <small id="logo_bigHelp" class="form-text text-muted">{{ __('Logo image used in login / register pages. This is used in default template too') }}</small>

                @if ($config->logo_big ?? null)
                    <br><img style="max-width:200px; height:auto;" src="{{ image($config->logo_big) }}" />
                @endif
            </div>

        </div>


        <div class="form-group">
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>

    </form>

</div>
<!-- end card-body -->
