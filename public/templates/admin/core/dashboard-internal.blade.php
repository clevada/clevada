<section class="section">
    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12">
                    <h4 class="card-title">{{ __('Dashboard') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($config->site_maintenance ?? null == 1)
                <div class="alert alert-danger font-weight-bold">
                    {{ __('Site is offline (maintenance mode)') }}.
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'demo') {{ __('Error. This action is disabled in demo mode') }} @endif
                </div>
            @endif

            <h5 class="mb-2">{{ __('Hello') }}, {{ Auth::user()->name }}. {{ __('Your permission are') }}:</h5>

            @foreach ($user_permissions as $user_permission)
                <b class="fs-5">{{ $user_permission->module_label }}</b>
                <br>
                {{ __('Permission') }}: <b>{{ $user_permission->permission_label }}</b>
                <div class="small text-muted">{{ $user_permission->permission_description }}</div>
                <div class="mb-3"></div>
            @endforeach

        </div>

    </div>

</section>
