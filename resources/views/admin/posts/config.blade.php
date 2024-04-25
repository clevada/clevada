<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">{{ __('Posts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts.config') }}">{{ __('Settings') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">
            <div class="col-12 mb-2">
                @include('admin.posts.includes.menu')
            </div>
        </div>

    </div>


    <div class="card-body">

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif

        <form method="post">
            @csrf

            <div class="form-row">

                <div class="fw-bold fs-5 mb-3">{{ __('Posts settings') }}</div>

                <div class="form-group col-lg-2 col-md-3 col-12">
                    <label>{{ __('Posts per page') }}</label>
                    <input type="number" name="posts_per_page" class="form-control" min="1" max="100" value="{{ $config->posts_per_page ?? 12 }}">
                </div>

                <div class="row">

                    <div class="col-12">
                        <div class="form-group mt-3 mb-0">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='posts_addtoany_enabled'>
                                <input class="form-check-input" type="checkbox" id="posts_addtoany_enabled" name="posts_addtoany_enabled" @if ($config->posts_addtoany_enabled ?? null) checked @endif
                                    @if (!($config->addthis_code_enabled ?? null)) disabled @endif>
                                <label class="form-check-label" for="posts_addtoany_enabled">{{ __('Enable AddToAny share buttons') }}</label>
                                @if (!($config->posts_addtoany_enabled ?? null))
                                    <div class="form-text text-danger">{{ __('AddToAny disabled') }}. <a href="{{ route('admin.config', ['module' => 'integration']) }}">{{ __('Change') }}</a></div>
                                @endif
                                <div class="form-text">{{ __('You can use AddToAny to add social share buttons into your posts.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mt-3 mb-0">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='posts_likes_disabled'>
                                <input class="form-check-input" type="checkbox" id="posts_likes_disabled" name="posts_likes_disabled" @if ($config->posts_likes_disabled ?? null) checked @endif>
                                <label class="form-check-label" for="posts_likes_disabled">{{ __('Disable like system') }}</label>
                                <div class="form-text">{{ __('This will disable likes for all posts. Note: If like system is enabled, you can disable likes for a specific post, in post settings') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mt-3">
                            <div class="form-check form-switch">
                                <input type='hidden' value='' name='posts_likes_require_login'>
                                <input class="form-check-input" type="checkbox" id="posts_likes_require_login" name="posts_likes_require_login" @if ($config->posts_likes_require_login ?? null) checked @endif>
                                <label class="form-check-label" for="posts_likes_require_login">{{ __('Only logged users can like posts') }}</label>
                                <div class="form-text">{{ __('If not enabled, anyone can like posts') }}</div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="fw-bold fs-5 mb-2 mt-4">{{ __('Comments settings') }}</div>

            <div class="col-12">
                <div class="form-group mt-3 mb-0">
                    <div class="form-check form-switch">
                        <input type='hidden' value='' name='posts_comments_disabled'>
                        <input class="form-check-input" type="checkbox" id="posts_comments_disabled" name="posts_comments_disabled" @if ($config->posts_comments_disabled ?? null) checked @endif>
                        <label class="form-check-label" for="posts_comments_disabled">{{ __('Disable comments system') }}</label>
                        <div class="form-text">{{ __('This will disable comments for all posts. Note: If comments system is enabled, you can disable comments for a specific post, in post settings') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mt-3">
                    <div class="form-check form-switch">
                        <input type='hidden' value='' name='posts_comments_antispam_enabled'>
                        <input class="form-check-input" type="checkbox" id="posts_comments_antispam_enabled" name="posts_comments_antispam_enabled" @if ($config->posts_comments_antispam_enabled ?? null) checked @endif
                            @if (!($config->google_recaptcha_enabled ?? null)) disabled @endif>
                        <label class="form-check-label" for="posts_comments_antispam_enabled">{{ __('Enable Google reCAPTCHA comments antispam for visitors') }}</label>
                        <div class="form-text">{{ __('Logged users do not have reCAPTCHA enabled') }}</div>
                        @if (!($config->google_recaptcha_enabled ?? null))
                            <div class="form-text text-danger">{{ __('Google reCAPTCHA is disabled') }}. <a href="{{ route('admin.config', ['module' => 'integration']) }}">{{ __('Change') }}</a></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mt-3">
                    <div class="form-check form-switch">
                        <input type='hidden' value='' name='posts_comments_require_login'>
                        <input class="form-check-input" type="checkbox" id="posts_comments_require_login" name="posts_comments_require_login" @if ($config->posts_comments_require_login ?? null) checked @endif>
                        <label class="form-check-label" for="posts_comments_require_login">{{ __('Only registered users can post comments') }}</label>
                        <div class="form-text">{{ __('If not enabled, anyone can add comments') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mt-3">
                    <div class="form-check form-switch">
                        <input type='hidden' value='' name='posts_comments_require_manual_approve'>
                        <input class="form-check-input" type="checkbox" id="posts_comments_require_manual_approve" name="posts_comments_require_manual_approve" @if ($config->posts_comments_require_manual_approve ?? null) checked @endif>
                        <label class="form-check-label" for="posts_comments_require_manual_approve">{{ __('Comments from visitors must be manually approved') }}</label>
                        <div class="form-text">{{ __('If not enabled, anyone can add comments') }}</div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="form-group mt-3 mb-0">
                    <div class="form-check form-switch">
                        <input type='hidden' value='' name='posts_comments_fb_enabled'>
                        <input class="form-check-input" type="checkbox" id="posts_comments_fb_enabled" name="posts_comments_fb_enabled" @if ($config->posts_comments_fb_enabled ?? null) checked @endif
                            @if (!($config->facebook_app_id ?? null)) disabled @endif>
                        <label class="form-check-label" for="posts_comments_fb_enabled">{{ __('Enable Facebook comments') }}</label>
                        @if (!($config->facebook_app_id ?? null))
                            <div class="form-text text-danger">{{ __('Facebook App ID is not set') }}. <a href="{{ route('admin.config', ['module' => 'integration']) }}">{{ __('Change') }}</a></div>
                        @endif
                        <div class="form-text">{{ __('You can use Facebook comments widget to add comments in your articles.') }}</div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="form-group mt-4 mb-0">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
