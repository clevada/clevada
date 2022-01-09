<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $categ->title }} | {{ site()->title }}</title>
    <meta name="description" content="{{ $categ->description ?? $categ->title }}">

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
                            @foreach (breadcrumb($categ->id, 'docs') as $categ)
                                <li class="breadcrumb-item"><a href="{{ docs_url($categ->id) }}">{{ $categ->title }}</a></li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">

                <div class="col-xl-3 col-sm-12 col-12">
                    <div class="bg-light p-3">
                        @foreach (docs_categ_tree() as $level0)

                            <a class="docs-categ-level1 @if ($categ->id == $level0->id) text-danger fw-bold @endif" href="{{ docs_url($level0->id) }}">{{ $level0->title }}</a>
                            <div class="mb-3"></div>

                            @if (in_array($categ->id, $level0->tree_ids))
                                @foreach ($level0->children as $level1)
                                    <a class="ms-3 docs-categ-level2 @if ($categ->id == $level1->id) text-danger fw-bold @endif" href="{{ docs_url($level1->id) }}"> {{ $level1->title }}</a>
                                    <div class="mb-3"></div>
                                    @foreach (docs_categ_tree($level1->id) as $level2)
                                        <a class="ms-4 docs-categ-level3 @if ($categ->id == $level2->id) text-danger fw-bold @endif" href="{{ docs_url($level2->id) }}"> {{ $level2->title }}</a>
                                        <div class="mb-3"></div>
                                        @foreach (docs_categ_tree($level2->id) as $level3)
                                            <a class="ms-5 docs-categ-level3 @if ($categ->id == $level3->id) text-danger fw-bold @endif" href="{{ docs_url($level3->id) }}"> {{ $level3->title }}</a>
                                            <div class="mb-3"></div>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endif
                            <div class="mb-4"></div>

                        @endforeach
                    </div>
                </div>


                <div class="col-xl-9 col-sm-12 col-12 pl-5">

                    <div class="docs-article-title">
                        {{ $categ->title }}
                    </div>

                    @foreach (docs_categ_tree($categ->id) as $subcateg)
                        <div class="docs-categ-level2 fw-bold mb-3"><a href="{{ docs_url($subcateg->id) }}">{{ $subcateg->title }}</a></div>
                    @endforeach

                    @foreach ($categ_articles as $article)
                        <h5 class="bold"><a href="{{ docs_url($categ->id) }}#{{ $article->slug }}"># {{ $article->title }}<a></h5>
                    @endforeach

                    <hr>


                    @foreach ($categ_articles as $article)
                        <a name="{{ $article->slug }}" class="anchor"></a>
                        <div class="fs-5 mt-4"><span class="fw-bold">#</span> {{ $article->title }}</div>

                        @if ($article->visibility == 'private' && !Auth::user())
                            <div class="alert alert-light mt-3 mb-3">
                                <div class="text-danger">{{ __('This article is visible for logged users only.') }} <a class="fw-bold" href="{{ route('login') }}">{{ __('Login') }}</a></div>
                            </div>
                        @else

                            @foreach (content_blocks('docs', $article->id) as $block)
                                @php
                                    $block_extra = unserialize($block->block_extra);
                                @endphp
                                <div class="section" id="block-{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
                                    @include("{$template_view}.includes.blocks-switch")
                                </div>
                            @endforeach

                        @endif

                    @endforeach

                </div>

            </div>
        </div>

    </div>
    <!-- End Main Content -->

    @include("{$template_view}.global.footer")

</body>

</html>
