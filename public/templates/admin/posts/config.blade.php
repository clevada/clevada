<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts') }}">{{ __('Posts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts.config') }}">{{ __('Settings') }}</a></li>
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
                    @include('admin.posts.layouts.menu-config')
                </div>
            </div>

        </div>


        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif

            <form method="post">
                @csrf

                <div class="form-row">

                    <div class="col-12">
                        <h5>{{ __('Posts settings') }}</h5>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3 col-12">
                            <label>{{ __('Posts per page') }}</label>
                            <input type="integer" name="posts_per_page" class="form-control" value="{{ $config->posts_per_page ?? 12 }}">
                        </div>

                        <div class="form-group col-md-3 col-12">
                            <label>{{ __('Posts comments per page') }}</label>
                            <input type="integer" name="posts_comments_per_page" class="form-control" value="{{ $config->posts_comments_per_page ?? 20 }}">
                        </div>

                        <div class="form-group col-md-3 col-12">
                            <label>{{ __('Comments order') }}</label>
                            <select name="posts_comments_order" class="form-select" required>
                                <option @if (($config->posts_comments_order ?? null) == 'old') selected @endif value="old">{{ __('Latest comments are displayed last') }}</option>
                                <option @if (($config->posts_comments_order ?? null) == 'new') selected @endif value="new">{{ __('Latest comments are displayed first') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group mt-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_comments_disabled'>
                                    <input class="form-check-input" type="checkbox" id="posts_comments_disabled" name="posts_comments_disabled" @if ($config->posts_comments_disabled ?? null) checked @endif>
                                    <label class="form-check-label" for="posts_comments_disabled">{{ __('Disable comments') }}</label>
                                    <div class="form-text">{{ __('This will disable comments for all posts. Note: You can enable or disable comments for each post') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mt-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_comments_antispam_enabled'>
                                    <input class="form-check-input" type="checkbox" id="posts_comments_antispam_enabled" name="posts_comments_antispam_enabled" @if ($config->posts_comments_antispam_enabled ?? null) checked @endif @if (!($config->google_recaptcha_enabled ?? null)) disabled @endif>
                                    <label class="form-check-label" for="posts_comments_antispam_enabled">{{ __('Enable Google reCAPTCHA antispam for visitors') }}</label>
                                    <div class="form-text">{{ __('Logged users do not have reCAPTCHA enabled') }}</div>
                                    @if (!($config->google_recaptcha_enabled ?? null))<div class="form-text text-danger">{{ __('Google reCAPTCHA is disabled') }}. <a href="{{ route('admin.config.integration') }}">{{ __('Change') }}</a></div>@endif
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
                            <div class="form-group mt-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='posts_likes_disabled'>
                                    <input class="form-check-input" type="checkbox" id="posts_likes_disabled" name="posts_likes_disabled" @if ($config->posts_likes_disabled ?? null) checked @endif>
                                    <label class="form-check-label" for="posts_likes_disabled">{{ __('Disable like system') }}</label>
                                    <div class="form-text">{{ __('This will disable likes for all posts. Note: You can enable or disable likes for each post') }}</div>
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

                <div class="col-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
