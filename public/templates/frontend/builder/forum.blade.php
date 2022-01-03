<!doctype html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <title>{{ module('forum')->meta_title }}</title>
    <meta name="description" content="{{ module('forum')->meta_description ?? __('Community') }}">

    @include("{$template_view}.global.head")

</head>

<body>

    <!-- Start Main Content -->
    <div class="content">

        @include("{$template_view}.global.navigation")

        @if ($sidebar_id && $sidebar_position == 'left')
            <div class="container-xxl">
                <div class="row">

                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12">
                        @foreach (content_blocks('sidebar', $sidebar_id) as $block)
                            @php
                                $block_extra = unserialize($block->block_extra);
                            @endphp
                            <div class="section" id="{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
                                @include("{$template_view}.includes.blocks-switch")
                            </div>
                        @endforeach
                    </div>

                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
                        @include("{$template_view}.includes.forum-index")
                    </div>

                </div>
            </div>

        @elseif ($sidebar_id && $sidebar_position == 'right')
            <div class="container-xxl">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
                        @include("{$template_view}.includes.forum-index")
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12">
                        @foreach (content_blocks('sidebar', $sidebar_id) as $block)
                            @php
                                $block_extra = unserialize($block->block_extra);
                            @endphp
                            <div class="section" id="{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
                                @include("{$template_view}.includes.blocks-switch")
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @else
            <div class="container-xxl">
                @include("{$template_view}.includes.forum-index")
            </div>
        @endif

    </div>
    <!-- End Main Content -->

    @include("{$template_view}.global.footer")

</body>

</html>
