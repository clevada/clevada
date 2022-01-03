<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $tag }}</title>
    <meta name="description" content="{{ $tag }}">

    @include("{$template_view}.global.head")

</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        @include("{$template_view}.blocks.search-cart")

        <section class="bar background-white no-mb">
            <div class="container-xxl">


                <div class="heading">
                    <h2>{{ $tag }} - {{ $posts->total() }} {{ __('posts') }}</h2>
                </div>

                @foreach ($posts as $post)

                    <div class="post-box-listing">
                        <div class="row">

                            <div class="col-lg-4 col-md-5 col-sm-12">
                                @if ($post->image)
                                    <a title="{{ $post->title }}" href="{{ post_url($post->id) }}">
                                        <img src="{{ thumb($post->image) }}" alt="{{ $post->title }}" class="img-fluid"></a>
                                @endif
                            </div>

                            <div class="col-lg-8 col-md-7 col-sm-12">
                                <a class="title" title="{{ $post->title }}" href="{{ post_url($post->id) }}">{{ $post->title }}</a>
                                <p class="text-muted text-small">
                                    {{ date_locale($post->created_at) }} /
                                    <a href="{{ profile_url($post->user_id) }}">{{ $post->author_name }}</a>
                                    <br>
                                    @foreach (breadcrumb($post->categ_id) as $categ)
                                        <a href="{{ posts_url($categ->id) }}">{{ $categ->title }}</a> @if (!$loop->last) / @endif
                                    @endforeach
                                </p>
                                <div class="summary">
                                    {{ substr($post->summary, 0, 300) ?? substr(strip_tags($post->content), 0, 300) }}...
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>

                @endforeach

                {{ $posts->links() }}

            </div>
            
        </section>

    </div>

    @include("{$template_view}.global.footer")

</body>

</html>
