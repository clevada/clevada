<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ module('posts')->meta_title ?? __('Articles') }}</title>
    <meta name="description" content="{{ module('posts')->meta_description ?? __('Articles') }}">

    @include("{$template_view}.global.head")
</head>

<body>

    <div id="wrapper">

        @include("{$template_view}.global.navigation")

        <!-- Main Content -->
        <div id="content">

            <div class="container-xxl mt-4">
                
                <div class="row">

                    <div class="col-12">                       

                        @if ($template->posts_style == 'rows')
                            @foreach ($posts as $post)
                                <div class="listing-box mb-4">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-5 col-md-5 col-12">
                                            <a title="{{ $post->title }}" href="{{ post($post->id)->url }}">
                                                @if ($post->image)<img src="{{ thumb($post->image) }}" class="img-fluid mb-3" alt="{{ $post->title }}">@endif
                                            </a>
                                        </div>

                                        <div class="col-xl-9 col-lg-7 col-md-7 col-12">

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

                        @if ($template->posts_style == 'columns')
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
                        @endif

                        {{ $posts->links() }}
                    </div>

                </div>
            </div>

        </div>
        <!-- End Main Content -->

        @include("{$template_view}.global.footer")

    </div>
    <!-- End Wrapper -->

</body>

</html>
