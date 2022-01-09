<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ module('docs')->meta_title ?? __('Knowledge base') }}</title>
    <meta name="description" content="{{ module('docs')->meta_description }}">

    @include("{$template_view}.global.head")

    <!-- Syntax highlight-->
    <link rel="stylesheet" href="{{ asset('assets/vendors/prism/prism.css') }}">
    <script src="{{ asset('assets/vendors/prism/prism.js') }}"></script>
</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        @include("{$template_view}.includes.search-docs")

        <div class="container-xxl">
            <div class="row">

                <div class="col-12 mb-1">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ site()->url }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ docs_url() }}">{{ __('Documentation') }}</a></li>
                        </ol>
                    </nav>
                </div>

                @php
                    $cols = get_template_value($template->id, 'docs_index_columns') ?? 3;
                    if ($cols == 2) {
                        $cols_class = 'col-md-6 col-12';
                    } elseif ($cols == 4) {
                        $cols_class = 'col-md-3 col-sm-6 col-12';
                    } else {
                        $cols_class = 'col-md-4 col-12';
                    }
                @endphp

                @foreach (docs_categ_tree() as $root_categ)
                    <div class="{{ $cols_class ?? 'col-12' }}">
                        @if (template('docs_index_style') == 'cards')
                            <a class="docs-card text-center" title="{{ $root_categ->title }}" href="{{ docs_url($root_categ->id) }}">
                                @if ($root_categ->icon && !(template('docs_hide_icons') ?? null)) <div class="card-categ-icon">{!! $root_categ->icon !!}</div>@endif

                                <div class="card-categ-title">
                                    {{ $root_categ->title }}
                                </div>

                                <div class="card-categ-description">{{ $root_categ->description }}</div>
                            </a>

                        @else

                            <div class="docs-list-categ">
                                <a title="{{ $root_categ->title }}" href="{{ docs_url($root_categ->id) }}">@if ($root_categ->icon && !(template('docs_hide_icons') ?? null)) {!! $root_categ->icon !!}@endif {{ $root_categ->title }}</a>
                            </div>

                            @foreach ($root_categ->children as $subcateg)
                                <div class="docs-list-subcateg">
                                    <a title="{{ $subcateg->title }}" href="{{ docs_url($subcateg->id) }}"> {{ $subcateg->title }}</a>
                                </div>
                            @endforeach
                            <div class="mb-4"></div>

                        @endif

                    </div>
                @endforeach

            </div>
        </div>

        @if (count($featured_articles) > 0)
            <div class="docs-featured-bar" style="background-color: {{ template('docs_features_bg_color') ?? '#FBF7F0' }};">

                <div class="container-xxl">
                    <div class="featured-heading fs-4 mb-3">{{ __('Suggested articles') }}</div>
                    <div class="row">
                        @foreach ($featured_articles as $article)
                            <div class="col-md-6 col-12">
                                <a class="docs-card docs-card-featured" title="{{ $article->title }}"
                                    href="{{ docs_url($article->categ_id) }}#{{ $article->slug }}">@if (!(template('docs_hide_icons') ?? null))<i class="bi bi-star-fill"></i> @endif{{ $article->title }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

    </div>
    <!-- End Main Content -->

    @include("{$template_view}.global.footer")

</body>

</html>
