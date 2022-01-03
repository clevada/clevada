<!doctype html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <title>{{ __('Report') }}</title>
    <meta name="description" content="{{ __('Report forum content') }}">

    @include("{$template_view}.global.head")
</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        <div class="container-xxl">

            <div class="heading">
                <h2>{{ __('Report') }}</h2>
            </div>

            <div class="alert alert-info">
                @if ($type == 'topic')<b>{{ __('Report topic') }}: {{ $topic->title }}</b><br>{{ substr(strip_tags($topic->content), 0, 400) }}... @endif

                @if ($type == 'post')<b>{{ __('Report post') }}:</b><br>{{ substr(strip_tags($post->content), 0, 400) }} @endif
            </div>

            @if (!Auth::user())
                {{ __('You must be logged to report') }}. <a href="{{ route('login') }}">{{ __('Login') }}</a> {{ __('or') }} <a
                    href="{{ route('register') }}">{{ __('register account') }}</a>
            @else

                <form method="post" action="{{ route('forum.report', ['type' => $type, 'id' => $id]) }}">
                    @csrf

                    <div class="form-group">
                        <label>{{ __('Reason') }} </label>
                        <textarea name="reason" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-forum"><i class="fas fa-share"></i> {{ __('Send report') }}</button>
                    </div>

                </form>
            @endif

        </div>

    </div>

    @include("{$template_view}.footer")

</body>

</html>
