<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config', ['module' => 'icons']) }}">{{ __('Icons') }}</a></li>
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

        <form method="post" enctype="multipart/form-data">
            @csrf


            <h4 class="mt-3">{{ __('Icons') }}</h4>

            <div class="form-group">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="customSwitchBootstrap" disabled checked name="bootstrap">
                    <label class="form-check-label" for="customSwitch">{{ __('Bootstrap icons') }} (version 1.11.3)</label>
                </div>
                <div class="form-text">
                    {{ __('Code example') }}: <b><code>{{ '<i class="bi bi-star"></i>' }}</code></b><br>
                    <b><a target="_blank" href="https://icons.getbootstrap.com">{{ __('See all icons codes') }}</a>.</b><br>
                    {{ __('Note: this icons can not be disabled because there are used in default templates and admin area.') }}
                </div>
            </div>

            {{--
            <div class="form-group mt-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="customSwitchFA" name="use_icons_fontawesome" @if ($config->use_icons_fontawesome ?? null) checked @endif>
                    <label class="form-check-label" for="customSwitchFA">{{ __('Font awesome icons') }}</label>
                </div>
                <div class="form-text">
                    {{ __('Code example') }}: <b><code>{{ '<i class="fas fa-user"></i>' }}</code></b><br>
                    <b><a target="_blank" href="https://fontawesome.com/icons">{{ __('See all icons codes') }}</a>.</b><br>
                </div>
            </div>
            --}}

            <div class="form-group mt-4">
                <div class="form-check form-switch">
                    <input type="hidden" name="use_icons_boxicons" value="">
                    <input class="form-check-input" type="checkbox" id="customSwitchBox" name="use_icons_boxicons" @if ($config->use_icons_boxicons ?? null) checked @endif>
                    <label class="form-check-label" for="customSwitchBox">{{ __('Box icons') }} (version 2.1.4)</label>
                </div>
                <div class="form-text">
                    {{ __('Code example') }}: <b><code>{{ '<i class="bx bx-user"></i>' }}</code></b><br>
                    <b><a target="_blank" href="https://boxicons.com">{{ __('See all icons codes') }}</a>.</b><br>
                </div>
            </div>


            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
