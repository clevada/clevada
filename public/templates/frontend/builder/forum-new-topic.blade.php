<!doctype html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <title>{{ __('Create forum topic') }}</title>
    <meta name="description" content="{{ __('Create forum topic') }}">

    @include("{$template_view}.global.head")

    <link rel="stylesheet" href="{{ asset('assets/vendors/prism/prism.css') }}">
</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        <div class="container-xxl">

            <nav aria-label="breadcrumb" class="forum-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ site()->url }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ forum_url() }}">{{ __('Forum') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create forum topic') }}</li>
                </ol>
            </nav>

            <div class="heading">
                <div class="title">{{ __('Create forum topic') }}</div>
            </div>

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'error_categ') {{ __('Error. Select category') }} @endif
                    @if ($message == 'error_title') {{ __('Error. Input title') }} @endif
                    @if ($message == 'error_content') {{ __('Error. Please input content') }} @endif
                </div>
            @endif

            @if (!Auth::user())
                {{ __('You must be logged to post new topic') }}. <a href="{{ route('login') }}">{{ __('Login') }}</a> {{ __('or') }} <a
                    href="{{ route('register') }}">{{ __('register account') }}</a>
            @else

                <form method="post" action="{{ route('forum.topic.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group col-lg-4 col-md-6">
                        <label>{{ __('Select forum') }}</label><br>
                        <select name="categ_id" class="form-select form-select-lg col-md-6 col-12" required>
                            <option value="">- {{ __('select') }} -</option>
                            @foreach ($categories as $categ)
                                @include("{$template_view}.loops.forum-categories-select-loop", $categ)
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Title (subject)') }}</label>
                        <input class="form-control form-control-lg" name="title" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Content') }}</label>
                        <textarea class="form-control trumbowyg" name="content" required></textarea>
                    </div>

                    @if (($config->forum_upload_images_enabled ?? null) == 'yes')
                        <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            {{ __('Attach images') }}
                        </a>

                        <div class="collapse" id="collapseExample">
                            <small class="form-text text-muted mb-3">{{ __('Maximum 6 images. File extensions: jpg,jpeg,bmp,png,gif,webp') }}</small>

                            <div class="row">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" name="image_{{ $i }}">
                                        </div>
                                    </div>
                                @endfor
                            </div>

                        </div>
                    @endif

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn1">{{ __('Create forum topic') }}</button>
                    </div>

                </form>
            @endif

        </div>
        <!-- End Main Content -->

        @include("{$template_view}.global.footer")

        <script src="{{ asset('assets/vendors/prism/prism.js') }}"></script>

        @include("{$template_view}.includes.trumbowyg-assets-simple")

</body>

</html>
