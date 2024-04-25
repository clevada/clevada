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

        @if ($config->website_disabled ?? null)
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __('Public website is disabled.') }}
            </div>
        @endif

        @if ($config->website_maintenance_enabled ?? null)
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __('Website is in maintenance mode.') }}
            </div>
        @endif

        @if ($config->registration_disabled ?? null)
            <div class="fw-bold text-danger mb-3">
                <i class="bi bi-info-circle"></i> {{ __('Users registration is disabled.') }}
            </div>
        @endif

        <form method="post">
            @csrf
            
            <div class="form-group mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" value="" name="registration_disabled">
                    <input class="form-check-input" type="checkbox" id="SwitchRegDisabled" name="registration_disabled" @if ($config->registration_disabled ?? null) checked @endif>
                    <label class="form-check-label" for="SwitchRegDisabled">{{ __('Disable registration') }}</label>
                </div>
                <div class="form-text">{{ __('If you disable registration, visitors can not register accounts. Administrators can manually create accounts from accounts area.') }}</div>
            </div>         

            <div class="form-group">
                <input type="hidden" name="section" value="labels">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </form>

    </div>
    <!-- end card-body -->

</div>
