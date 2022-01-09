<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $s }} - {{ __('Knowledge base') }}</title>
    <meta name="description" content="{{ $s }} - {{ __('search in knowledge base') }}">
    @include("{$template_view}.global.head")
</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        @include("{$template_view}.includes.search-docs")
        
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ site()->url }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ docs_url() }}">{{ __('Documentation') }}</a></li>
                            <li class="breadcrumb-item">{{ __('Search results') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">

                <div class="col-xl-3 col-sm-12 col-12">
                    <div class="bg-light p-3">
                        @foreach (docs_categ_tree() as $level0)

                            <a class="docs-categ-level1" href="{{ docs_url($level0->id) }}">{{ $level0->title }}</a>
                            <div class="mb-1"></div>

                            @foreach ($level0->children as $level1)
                                <a class="docs-categ-level2" href="{{ docs_url($level1->id) }}"> {{ $level1->title }}</a>
                                <div class="mb-1"></div>
                                @foreach (docs_categ_tree($level1->id) as $level2)
                                    <a class="ms-4 docs-categ-level3" href="{{ docs_url($level2->id) }}"> {{ $level2->title }}</a>
                                    <div class="mb-1"></div>
                                    @foreach (docs_categ_tree($level2->id) as $level3)
                                        <a class="ms-5 docs-categ-level3" href="{{ docs_url($level3->id) }}"> {{ $level3->title }}</a>
                                        <div class="mb-1"></div>
                                    @endforeach
                                @endforeach
                            @endforeach
                            <div class="mb-4"></div>

                        @endforeach
                    </div>
                </div>


                <div class="col-xl-9 col-sm-12 col-12 pl-5">

                    <h2>{{ $s }} - {{ __('search results') }}</h2>
                    <p class="small textmuted">"{{ $s }}" - {{ $articles->total() ?? 0 }} {{ __('articles') }}</p>

                    @foreach ($articles as $article)
                        <h3><a href="{{ docs_url($article->categ_id) }}#{{ $article->slug }}">{{ $article->title }}</a></h3>
                        <span class="small textmuted">{!! substr(strip_tags($article->content), 0, 300) !!}</span>
                        <div class="mb-4"></div>
                    @endforeach

                    {{ $articles->appends(['s' => $s])->links() }}

                </div>

            </div>
        </div>

    </div>

    @include("{$template_view}.global.footer")

</body>

</html>
