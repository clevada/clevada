<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="{{ $config->site_author ?? 'Clevada - https://www.clevada.com' }}">

    <!-- Favicon -->
    @if ($config->favicon ?? null)
        <link rel="shortcut icon" href="{{ image($config->favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ config('app.cdn') }}/img/favicon.png">
    @endif

    <!-- Bootstrap CSS-->
    @if (($langDir ?? null) == 'rtl')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-7mQhpDl5nRA5nY9lr8F1st2NbIly/8WqhjTp+0oFxEA/QUuvlbF6M1KXezGBh3Nb"
            crossorigin="anonymous">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    @endif

    <!-- Bootstrap Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <!-- Main CSS Files -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/css/builder.css" />
    <link rel="stylesheet" href="{{ config('app.cdn') }}/css/blocks.css" />

    <!-- Custom CSS File -->
    <link rel="stylesheet" href='{{ asset('custom/custom.css') }}' />
    <link rel="stylesheet" href='{{ asset('custom/styles.css') }}' />

</head>

<body class="style_global">

    <!-- Start Main Content -->
    <div class="content">

        @include('web.global.navigation')

        <div class="container-xxl">

            <div class="mt-5 mb-3 fs-4">{{ __('This is a preview for this style') }}:<b> {{ $style->label }}</b></div>

            <a class="btn btn-light mb-3" href="{{ route('admin.preview-style', ['id' => $style->id]) }}">{{ __('Reload this page') }}</a>

            <div class="alert alert-light mb-5">
                <i class="bi bi-exclamation-circle"></i>
                {{ __("Note: If you don't see any changes after refresh, you can try to reload the website using CTRL+F5 or clear browser cache.") }}
            </div>

            <div class="style_{{ $style->id }} p-3">
                <div class="title mb-2">This is a title</div>
                <div class="subtitle mb-2">This is a subtitle</div>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vehicula dui nisl. <a href="#">Quisque ullamcorper orci enim</a>, sed porta diam bibendum in. Pellentesque in aliquet diam, a porta
                magna.
                Almost before <a href="#">we knew it</a>, we had left the ground. onec malesuada at elit facilisis lobortis.
                Curabitur scelerisque ornare urna vel vulputate. Cras <a href="#">pellentesque eros ut tellus scelerisque</a>, at iaculis felis vehicula. Quisque pulvinar sem quis turpis scelerisque aliquam. </a>

                <div class="caption mt-2">This is a caption text</div>
            </div>

        </div>

    </div>
    <!-- End Main Content -->


    @include('web.global.footer')

</body>

</html>
