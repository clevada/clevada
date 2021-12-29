<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config.registration') }}">{{ __('Configuration') }}</a></li>
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

            @if ($config->registration_disabled ?? null)
                <div class="alert alert-danger">
                    {{ __('Warning! Users registration is disabled') }}
                </div>
            @endif

            <form method="post">
                @csrf

                <div class="row">
                    
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="SwitchRegDisabled" name="registration_disabled" @if ($config->registration_disabled ?? null) checked @endif>
                            <label class="form-check-label" for="SwitchRegDisabled">{{ __('Disable registration') }}</label>
                            <div class="form-text">{{ __('If you disable registration, visitors can not register accounts. Administrators can manually create accounts from accounts area.') }}</div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="SwitchEmailVerify" name="registration_verify_email_disabled" @if ($config->registration_verify_email_disabled ?? null) checked @endif>
                            <label class="form-check-label" for="SwitchEmailVerify">{{ __('Disable email verification for registration') }}</label>
                            <div class="form-text"><span class="text-danger fw-bold">{{ __('This action is not recommended.') }}</span>. {{ __('If you disable email verification, visitors can register and use their accounts without to verify their email address.') }}</div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchRecaptcha" name="registration_recaptcha" @if ($config->registration_recaptcha ?? null) checked @endif>
                            <label class="form-check-label" for="switchRecaptcha">{{ __('Enable Google reCAPTCHA for registration') }}</label>
                            <div class="form-text"><a href="{{ route('admin.config.integration') }}">{{ __('Manage reCAPTCHA keys') }}</a></div>
                        </div>                                                
                    </div>

                    <hr>
                    
                    <div class="fs-5">{{ __('Posts access') }}:</div>

                    <div class="form-group mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchPostsContributor" name="registration_posts_contributor" @if ($config->registration_posts_contributor ?? null) checked @endif>
                            <label class="form-check-label" for="switchPostsContributor">{{ __('Registered users are posts contributors (can create articles)') }}</label>
                            <div class="form-text">{{ __('Notes: you can enable / disable  this setting for each user in Accounts page (edit user)') }}. <a href="{{ route('admin.accounts') }}">{{ __('Manage accounts') }}</a></div>
                        </div>                                                
                    </div>

                    <div class="form-group mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchPostsContributorApprove" name="registration_posts_auto_approve" @if ($config->registration_posts_auto_approve ?? null) checked @endif>
                            <label class="form-check-label" for="switchPostsContributorApprove">{{ __('Posts (articles) are automatically approved (not recommended)') }}</label>
                            <div class="form-text">{{ __('Notes: you can enable / disable  this setting for each user in Accounts page (edit user)') }}. <a href="{{ route('admin.accounts') }}">{{ __('Manage accounts') }}</a></div>
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
