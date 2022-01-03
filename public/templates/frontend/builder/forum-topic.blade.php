<!doctype html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <title>{{ $topic->title }} - {{ site()->title }}</title>
    <meta name="description" content="{{ substr(strip_tags($topic->content), 0, 300) }}">

    @include("{$template_view}.global.head")

    <link rel="stylesheet" href="{{ asset('assets/vendors/prism/prism.css') }}">
</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        <div class="container-xxl">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ site()->url }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ forum_url() }}">{{ __('Forum') }}</a></li>
                    @foreach (breadcrumb($categ->id, 'forum') as $b_categ)
                        <li class="breadcrumb-item"><a href="{{ route('forum.categ', ['slug' => $b_categ->slug]) }}">{{ $b_categ->title }}</a></li>
                    @endforeach
                </ol>
            </nav>

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'error_content') {{ __('Error. Please input content') }} @endif
                    @if ($message == 'error_topic_not_active') {{ __("Error. You can't reply to this topic") }} @endif
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-info font-weight-bold">
                    @if ($message == 'reported')<i class="fas fa-exclamation-triangle"></i> {{ __('Report was sent. Thank you') }} @endif
                    @if ($message == 'post_created'){{ __('Your reply was added') }} @endif
                </div>
            @endif

            <div class="mb-3">
                <div class="float-end">
                    <a class="btn btn2 ms-3" href="{{ route('forum.topic.create') }}"><i class="bi bi-plus-circle" aria-hidden="true"></i> {{ __('New topic') }}</a>
                </div>

                <div class="float-end">
                    <form class="form-inline">
                        <input class="form-control" name="search" placeholder="{{ __('Search in forum') }}">
                    </form>
                </div>
            </div>


            <h3>{{ $topic->title }}</h3>
            <div class="text-muted small mb-3">{{ __('Created at') }} {{ date_locale($topic->created_at, 'datetime') }} {{ __('by') }} {{ $topic->author_name }}</div>

            <div class="card card-forum">
                @include("{$template_view}.includes.forum-topic-header")

                @include("{$template_view}.includes.forum-topic-body")
            </div>

            <div class="mb-3"></div>

            @foreach ($posts as $post)
                <div class="card card-forum">

                    @include("{$template_view}.includes.forum-post-header")

                    <div class="card-body @if ($categ->type == 'question' and $post->count_best_answer > 0 and $loop->index == 0) post-best-answer @endif">
                        @include("{$template_view}.includes.forum-post-body")
                    </div>
                </div>
            @endforeach

            {{ $posts->links() }}

            <div class="mb-3"></div>

            @if (!Auth::user())
                {{ __('You must be logged to post new topic') }}. <a href="{{ route('login') }}">{{ __('Login') }}</a> {{ __('or') }} <a
                    href="{{ route('register') }}">{{ __('register account') }}</a>
            @else

                <a name="reply"></a>

                @if ($topic->status != 'active')
                    <div class="text-danger font-weight-bold">{{ __('This topic is closed') }}</div>
                @else
                    <form method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>{{ __('Post reply') }}</label>
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
                            <button type="submit" class="btn btn1">{{ __('Post reply') }}</button>
                        </div>

                    </form>
                @endif
            @endif

        </div>
        <!-- End Container -->

    </div>
    <!-- End Main Content -->

    @include("{$template_view}.global.footer")

    <script src="{{ asset('assets/vendors/prism/prism.js') }}"></script>

    @include("{$template_view}.includes.trumbowyg-assets-simple")

</body>

</html>
