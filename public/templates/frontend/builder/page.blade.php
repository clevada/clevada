<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>

    <title>{{ $content->meta_title ?? $content->title }}</title>
    <meta name="description" content="{{ $content->meta_description ?? ($content->meta_title ?? $content->title) }}">

    @include("{$template_view}.global.head")

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
                @include("{$template_view}.layouts.layout-sidebar-left")                
            @elseif ($sidebar_id && $sidebar_position == 'right')
                @include("{$template_view}.layouts.layout-sidebar-right")
            @else            
                @include("{$template_view}.layouts.layout-content")   
            @endif         

        </div>
        <!-- End Main Content -->

        @include("{$template_view}.global.footer")

    </div>
    <!-- End Wrapper -->

</body>

</html>
