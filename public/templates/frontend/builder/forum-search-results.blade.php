<!doctype html>
<html lang="{{ $locale }}">

<head>
    <title>{{ $s }}</title>
    <meta name="description" content="{{ $s }} - {{ __('Forum search results') }}">

    @include("{$template_view}.global.head")

</head>

<body>

    <div id="wrapper">

        @include("{$template_view}.global.navigation")

        <!-- Main Content -->
        <div id="content">

            <div class="container-xxl mt-4">

                <nav aria-label="breadcrumb" class="forum-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ site()->url }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ forum_url() }}">{{ __('Forum') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Forum search results') }}</li>
                    </ol>
                </nav>

                <div class="heading">
                    <div class="title">{{ $s }} - {{ __('Search results') }}</div>
                </div>

                @foreach ($results as $result)

                    <div class="post-box-listing">

                        <a class="title" title="{{ $result->title }}" href="{{ route('forum.topic', ['id' => $result->id, 'slug' => $result->slug]) }}">{{ $result->title }}</a>
                        <p class="text-muted text-small">
                            {{ date_locale($result->created_at) }} /
                            <a href="{{ profile_url($result->user_id) }}">{{ $result->author_name }}</a>
                            <br>                          
                        </p>
                       
                        <hr>
                    </div>

                @endforeach

            </div>
            <!-- End Container -->

        </div>
        <!-- End Main Content -->

        @include("{$template_view}.global.footer")

    </div>
    <!-- End Wrapper -->

</body>

</html>
