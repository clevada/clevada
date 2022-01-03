<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>

    <title>{{ $content->meta_title ?? $content->title }}</title>
    <meta name="description" content="{{ $content->meta_description ?? ($content->meta_title ?? $content->title) }}">

    @include("{$template_view}.global.head")

    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
    <!-- END CSS for this page -->

</head>

<body>

    <!-- Start Main Content -->
    <div class="content">
        @include("{$template_view}.global.navigation")

        <div class="container-xxl">
            @if ($sidebar_id && $sidebar_position == 'left')
                @include("{$template_view}.layouts.layout-sidebar-left")
            @elseif ($sidebar_id && $sidebar_position == 'right')
                @include("{$template_view}.layouts.layout-sidebar-right")
            @else
                @include("{$template_view}.layouts.layout-content")
            @endif
        </div>

    </div>
    <!-- End Main Content -->

    @include("{$template_view}.global.footer")

</body>

</html>
