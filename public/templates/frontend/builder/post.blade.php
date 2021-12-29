<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <title>{{ $post->meta_title ?? $post->title }}</title>
    <meta name="description" content="{{ $post->meta_description ?? ($post->summary ?? strip_tags(substr($post->content, 0, 300))) }}">
    @include("{$template_view}.global.head")

    <meta property="og:title" content="{{ $post->title }}" />
    @if ($post->image)<meta property="og:image" content="{{ image($post->image) }}" />@endif
    <meta property="og:site_name" content="{{ site()->title }}" />
    <meta property="og:description" content="{{ $post->meta_description ?? ($post->summary ?? strip_tags(substr($post->content, 0, 300))) }}" />
    <meta property="fb:app_id" content="{{ $config->facebook_app_id ?? null }}" />
    <meta property="og:type" content="article" />

    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
    <!-- END CSS for this page -->
   
</head>

<body>

    <div id="wrapper">

        @include("{$template_view}.global.navigation")

        <!-- Main Content -->
        <div id="content">

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
                            @include("{$template_view}.includes.post-item")
                        </div>

                    </div>
                </div>

            @elseif ($sidebar_id && $sidebar_position == 'right')
                <div class="container-xxl">
                    <div class="row">
                        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
                            @include("{$template_view}.includes.post-item")
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
                <div class="container-xxl mt-4">
                    @include("{$template_view}.includes.post-item")
                </div>
            @endif

        </div>
        <!-- End Main Content -->

        @include("{$template_view}.global.footer")

    </div>
    <!-- End Wrapper -->

    @if (!($config->posts_likes_disabled ?? null))
        <script>
            jQuery(document).ready(function() {
                $(".like").on('click', function(event, value, caption) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            post_id: '{{ json_encode($post->id) }}'
                        },
                        url: '{{ posts_submit_like_url($post->categ_slug, $post->slug, active_lang()->code) }}',
                        success: function(data) {
                            if (data == 'liked') {
                                var elem = document.getElementById('like_success');
                                $(elem).show();
                            }
                            if (data == 'already_liked') {
                                var elem = document.getElementById('like_error');
                                var elem2 = document.getElementById('like_success');
                                $(elem2).hide();
                                $(elem).show();
                            }
                            if (data == 'login_required') {
                                var elem = document.getElementById('login_required');
                                $(elem).show();
                            }
                        }
                    });
                });
            });
        </script>
    @endif

</body>

</html>
