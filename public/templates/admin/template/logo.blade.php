<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.template.logo') }}">{{ __('Logo and icons') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 mb-3">
                    @include('admin.template.layouts.menu-template')
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
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <label for="logo" class="form-label">{{ __('Main logo') }} ({{ __('required') }})</label>
                        <input class="form-control" aria-describedby="logoHelp" id="logo" type="file" name="logo">
                        <div id="logoHelp" class="form-text">{{ __('This logo is displayed on navigation. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}</div>

                        @if ($config->logo ?? null)
                            <br><img class="img-fluid" src="{{ image($config->logo) }}" />
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
						<label for="logo_alt" class="form-label">{{ __('Alternative logo') }}</label>
                        <input class="form-control" aria-describedby="logoHelp2" id="logo_alt" type="file" name="logo_alt">
                        <div id="logoHelp2" class="form-text">{{ __('This logo is displayed on website areas such as footer. Recomended file type: PNG transparent image. Note: Original image will be uploaded, without any crop or resize.') }}</div>                        

                        @if ($config->logo_alt ?? null)
                            <br><img class="img-fluid" src="{{ image($config->logo_alt) }}" />
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <label for="favicon" class="form-label">{{ __('Favicon') }} ({{ __('required') }})</label>
                        <input type="file" class="form-control" id="favicon" name="favicon" aria-describedby="faviconHelp">
                        <div id="faviconHelp" class="form-text">{{ __('PNG, JPG, JPEG or GIF. Recomended file type: 32px x 32px. Note: Original image will be uploaded, without any crop or resize.') }}</div>

                        @if ($config->favicon ?? null)
                            <br><img class="img-fluid" src="{{ image($config->favicon) }}" />
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

</section>
