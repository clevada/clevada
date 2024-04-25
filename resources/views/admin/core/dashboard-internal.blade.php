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
                    @if ($message == 'demo')
                        {{ __('Error. This action is disabled in demo mode') }}
                    @endif
                </div>
            @endif

            <h5 class="mb-2">{{ __('Hello') }}, {{ Auth::user()->name }}.</h5>

           

        </div>

    </div>
