@include('admin.includes.trumbowyg-assets')

@if (($config->site_maintenance ?? null) == 1)
    <style>
        #hidden_div {
            display: visible;
        }

    </style>
@else
    <style>
        #hidden_div {
            display: none;
        }

    </style>
@endif

<script>
    function showDiv(divId, element) {
        document.getElementById(divId).style.display = element.value == 1 ? 'block' : 'none';
    }
</script>

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.config.site_offline') }}">{{ __('Configuration') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Site offline') }}</li>
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

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'no_token') {{ __('Error. You must inpout a bypass token (code) so you can access the website') }} @endif
                </div>
            @endif

            @if (($config->site_maintenance ?? null))
                <div class="alert alert-danger">
                    {{ __('Warning! Site is offline') }}
                </div>
            @endif

            <form method="post">
                @csrf

                <div class="form-row">

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchOffline" name="site_maintenance" @if ($config->site_maintenance ?? null) checked @endif>
                            <label class="form-check-label" for="switchOffline">{{ __('Site is Offline (Maintenance mode)') }}</label>
                        </div>
                    </div>

                    <script>
                        $('#switchOffline').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div').style.display = 'block';
                            else
                                document.getElementById('hidden_div').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div" @if ($config->site_maintenance ?? null) style="display: visible" @endif>
                        <div class="form-group col-md-4">
                            <label>{{ __('Code for bypassing maintenance mode') }} ({{ __('required') }})</label>
                            <input name="site_maintenance_token" class="form-control" placeholder="Input bypass token" value="{{ $config->site_maintenance_token ?? null }}">

                            <div class="text-muted mt-3">
                                {{ __('Even while in maintenance mode, you may use the secret option to specify a maintenance mode bypass token (code).
                                                                                                After placing the application in maintenance mode, you may navigate to the application URL matching this token and website will issue a maintenance mode bypass cookie to your browser') }}:
                                <div class="mb-1"></div>

                                https://example.com/abcd1234

                                <div class="mb-1"></div>
                                {{ __('Once the cookie has been issued to your browser, you will be able to browse the application normally as if it was not in maintenance mode.') }}

                                <div class="mb-3 mt-3 text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i>
                                    {{ __('Warning. If you forget bypass token, you will not be abble to acces any area of your website (including admin area). The only way to to disable maintenance mode is to use the up command in the console') }}:
                                    <span class="fw-italic font-weight-bold">php artisan up</span>
                                </div>

                            </div>
                        </div>

                        <div class="alert alert-light">
                            {{ __('Note: If you enable site maintenance mode, you will be automatically redirected to homepage with bypass token, so you can continue to navigate in website, including admin area') }}
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-danger">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
