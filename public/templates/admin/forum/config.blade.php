<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.forum.topics') }}">{{ __('Community') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.forum.config') }}">{{ __('Forum config') }}</a></li>
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
                    @include('admin.forum.layouts.menu-config')
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
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            <div class="row">
                <div class="col-md-4 col-12">

                    <form method="post">
                        @csrf

                        <h5>{{ __('LIKES SYSTEM') }}</h5>
                        <div class="form-group">
                            <label>{{ __('Likes system enabled') }}</label>
                            <select class="form-select" name="forum_likes_system">
                                <option @if (($config->forum_likes_system ?? null) == 'yes') selected @endif value="yes">{{ __('Yes') }}</option>
                                <option @if (($config->forum_likes_system ?? null) == 'no') selected @endif value="no">{{ __('No') }}</option>
                            </select>
                        </div>

                        <h5 class="mt-4">{{ __('UPLOAD IMAGES') }}</h5>
                        <div class="form-group">
                            <label>{{ __('Enable image uploads') }}</label>
                            <select class="form-select" name="forum_upload_images_enabled">
                                <option @if (($config->forum_upload_images_enabled ?? null) == 'yes') selected @endif value="yes">{{ __('Yes') }} ({{ __('maximum 12 images') }})</option>
                                <option @if (($config->forum_upload_images_enabled ?? null) == 'no') selected @endif value="no">{{ __('No') }}</option>
                            </select>
                            <small id="uploadHelp" class="form-text text-muted">{{ __('Enable image uploads in topics and posts') }}</small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Visitors can view images') }}</label>
                            <select class="form-select" name="forum_images_public">
                                <option @if (($config->forum_images_public ?? null) == 'yes') selected @endif value="yes">{{ __('Yes') }}</option>
                                <option @if (($config->forum_images_public ?? null) == 'no') selected @endif value="no">{{ __('No') }} ({{ __('recomended') }})</option>
                            </select>
                            <small id="uploadHelp" class="form-text text-muted">{{ __('If NO, visitors must be registered and logged to view images') }}</small>
                        </div>

                        <h5 class="mt-4">{{ __('USERS SIGNATURE') }}</h5>

                        <div class="form-group">
                            <label>{{ __('Enable signatures') }}</label>
                            <select class="form-select" name="forum_signatures_enabled">
                                <option @if (($config->forum_signatures_enabled ?? null) == 'yes') selected @endif value="yes">{{ __('Yes') }}</option>
                                <option @if (($config->forum_signatures_enabled ?? null) == 'no') selected @endif value="no">{{ __('No') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Users must have minimum posts to allow signatures') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="forum_signature_min_posts_required" value="{{ $config->forum_signature_min_posts_required ?? null }}"
                                    aria-describedby="postsHelp">
                                <div class="input-group-append">
                                    <span class="input-group-text">posts</span>
                                </div>
                            </div>
                            <small id="postsHelp" class="form-text text-muted">{{ __('Leave empty to ignore rule') }}</small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Users must have minimum topics to allow signatures') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="forum_signature_min_topics_required" value="{{ $config->forum_signature_min_topics_required ?? null }}"
                                    aria-describedby="postsHelp">
                                <div class="input-group-append">
                                    <span class="input-group-text">topics</span>
                                </div>
                            </div>
                            <small id="postsHelp" class="form-text text-muted">{{ __('Leave empty to ignore rule') }}</small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Users must have minimum likes received to allow signatures') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="forum_signature_min_likes_required" value="{{ $config->forum_signature_min_likes_required ?? null }}"
                                    aria-describedby="likesHelp">
                                <div class="input-group-append">
                                    <span class="input-group-text">likes</span>
                                </div>
                            </div>
                            <small id="likesHelp" class="form-text text-muted">{{ __('Leave empty to ignore rule') }}</small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Users must have minimum days from registration') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="forum_signature_min_days_required" value="{{ $config->forum_signature_min_days_required ?? null }}"
                                    aria-describedby="daysHelp">
                                <div class="input-group-append">
                                    <span class="input-group-text">days</span>
                                </div>
                            </div>
                            <small id="daysHelp" class="form-text text-muted">{{ __('Leave empty to ignore rule') }}</small>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>

                    </form>
                </div>
            </div>

        </div>
        <!-- end card-body -->

    </div>

</section>
