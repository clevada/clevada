<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ module('posts')->meta_title ?? __('Articles') }}</title>
    <meta name="description" content="{{ module('posts')->meta_description ?? __('Articles') }}">

    @include("{$template_view}.global.head")
</head>

<body>

    <!-- Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        <div class="container-xxl mt-4">

            @if (($template->posts_style ?? null) == 'columns')
                @php
                    if ($template->posts_columns == 2) {
                        $class = 'col-md-6 col-12';
                    } elseif ($template->posts_columns == 3) {
                        $class = 'col-md-4 col-12';
                    } elseif ($template->posts_columns == 4) {
                        $class = 'col-md-3 col-12';
                    } else {
                        $class = 'col-md-6 col-12';
                    }
                @endphp

                <div class="row">
                    @foreach ($posts as $post)
                        <div class="{{ $class }}">

                            <div class="listing-box">
                                <a title="{{ $post->title }}" href="{{ post($post->id)->url }}">
                                    @if ($post->image)<img src="{{ thumb($post->image) }}" class="img-fluid mb-3" alt="{{ $post->title }}">@endif
                                </a>

                                <div class="title">
                                    <a href="{{ post($post->id)->url }}">{{ $post->title }}</a>
                                </div>

                                <div class="summary">
                                    <p>{{ $post->summary }}</p>
                                </div>

                                <div class="meta">
                                    @if ($post->author_avatar) <img src="{{ thumb($post->author_avatar) }}" alt="{{ $post->author_name }}" class="img-fluid rounded-circle float-start me-2">@endif
                                    <a href="{{ profile_url($post->user_id) }}"> {{ $post->author_name }}</a> <i class="bi bi-calendar ms-3"></i>
                                    {{ date_locale($post->created_at) }} <i class="bi bi-clock ms-3"></i> {{ $post->minutes_to_read }} {{ __('minutes read') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else

                @foreach ($posts as $post)
                    <div class="listing-box mb-4">
                        <div class="row">

                            @if (!template('posts_hide_image'))
                                <div class="col-xl-3 col-lg-5 col-md-5 col-12">
                                    <a title="{{ $post->title }}" href="{{ post($post->id)->url }}">
                                        @if ($post->image)<img src="{{ thumb($post->image) }}" class="img-fluid mb-3" alt="{{ $post->title }}">@endif
                                    </a>
                                </div>
                            @endif

                            <div class="@if (!template('posts_hide_image')) col-xl-9 col-lg-7 col-md-7 @endif col-12">

                                <div class="title">
                                    <a href="{{ post($post->id)->url }}">{{ $post->title }}</a>
                                </div>

                                <div class="summary">
                                    <p>{{ $post->summary }}</p>
                                </div>

                                <div class="light small">
                                    @if ($post->author_avatar) <img src="{{ thumb($post->author_avatar) }}" alt="{{ $post->author_name }}" class="avatar rounded-circle float-start me-2">@endif
                                    <a href="{{ profile_url($post->user_id) }}"> {{ $post->author_name }}</a> <i class="bi bi-calendar ms-3"></i>
                                    {{ date_locale($post->created_at) }} <i class="bi bi-clock ms-3"></i> {{ $post->minutes_to_read }} {{ __('minutes read') }}
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            {{ $posts->links() }}
        </div>

    </div>
    <!-- End Main Content -->

    @include("{$template_view}.global.footer")

</body>

</html>
