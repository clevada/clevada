<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $categ->meta_title ?? $categ->title }}</title>
    <meta name="description" content="{{ $categ->meta_description ?? $categ->description ?? $categ->title }}">

    @include("{$template_view}.global.head")

</head>

<body>

    <div id="all">

        <div id="content-wrap">

            @include("{$template_view}.global.navigation")
            
            <section>

                <div class="container-xxl">

                    <div class="row">

                        <div class="col-12">

                            <div class="heading">
                                {{ $categ->title }}
                            </div>

                            @foreach ($posts as $post)

                            <div class="post-box-listing">
                                <div class="row">

                                    <div class="col-lg-4 col-md-5 col-sm-12">
                                        @if($post->image)
                                        <a title="{{ $post->title }}" href="{{ post($post->id)->url }}">
                                            <img src="{{ thumb($post->image) }}" alt="{{ $post->title }}" class="img-fluid"></a>
                                        @endif
                                    </div>

                                    <div class="col-lg-8 col-md-7 col-sm-12">
                                        <a class="title" title="{{ $post->title }}" href="{{ post($post->id)->url }}">{{ $post->title }}</a>
                                        <p class="text-muted text-small">
                                            {{ date_locale($post->created_at) }} /
                                            <a href="{{ profile_url($post->user_id) }}">{{ $post->author_name }}</a>
                                            <br>

                                            @foreach(breadcrumb($post->categ_id) as $categ)
                                            <a href="{{ posts_url($categ->id) }}">{{ $categ->title }}</a> @if(!$loop->last) / @endif
                                            @endforeach
                                        </p>
                                        <div class="summary">
                                            {{ $post->summary ?? substr(strip_tags($post->content), 0,300).'...' }}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            @endforeach

                            {{ $posts->links() }}
                        </div>

                    </div>
                </div>
            </section>

        </div>

        @include("{$template_view}.global.footer")

    </div>

</body>

</html>