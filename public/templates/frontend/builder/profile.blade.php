<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $user->name }} - {{ site()->title }}</title>
    <meta name="description" content="{{ $user->name }} - {{ __('profile page') }}">

    @include("{$template_view}.global.head")
</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        <div class="container-xxl">

            <div>
                @if ($user->avatar) <img src="{{ thumb($user->avatar) }}" alt="{{ $user->name }}" class="img-fluid rounded-circle float-start me-3" style="max-height: 60px;">@endif
                <h2 class="mb-1">{{ $user->name }}</h2>
                <div class="text-muted small">{{ __('Registered') }}: {{ date_locale($user->created_at) }}</div>
            </div>

            @if ($bio)
                <div class="pofile-bio">{{ $bio }}</div>
            @endif

            <hr class="mt-3">

            @if ($posts->total() > 0)

                <div class="heading">
                    <h5>{{ $posts->total() }} {{ __('posts') }}</h5>
                </div>

                @foreach ($posts as $post)
                    <div class="mb-2">
                        <i class="bi bi-arrow-right-short"></i> <a title="{{ $post->title }}" href="{{ post($post->id)->url }}">{{ $post->title }}</a>
                        <span class="text-muted small">{{ date_locale($post->created_at, 'datetime') }}</span>
                    </div>
                @endforeach

                {{ $posts->appends(['id' => $user->id, 'slug' => $user->slug])->links() }}

            @endif

        </div>

    </div>
    <!-- End Main Content -->

    @include("{$template_view}.global.footer")

</body>

</html>
