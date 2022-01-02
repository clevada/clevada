<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="{{ $config->site_meta_author }}">

<!-- Favicon -->
@if ($config->favicon)<link rel="shortcut icon" href="{{ image($config->favicon) }}">@endif

<!-- Bootstrap CSS-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<!-- Bootstrap Fonts -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- Fancybox -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />

<!-- Template Main CSS File -->
<link rel="stylesheet" href="{{ asset("$template_path/assets/css/style.css") }}" />

<!-- Template Blocks CSS File -->
<link rel="stylesheet" href="{{ asset("$template_path/assets/css/blocks.css") }}" />

<!-- Custom CSS File -->
<link rel="stylesheet" href='{{ asset("custom/styles/$template->id.css") }}' />

<!-- Prism -->
<link rel="stylesheet" href='{{ asset("$template_path/assets/vendor/prism/prism.css") }}' />

<!-- jQuery -->
<script src="{{ asset("$template_path/assets/js/jquery.min.js") }}"></script>
{!! $config->template_global_head_code ?? null !!}
@php
$path = 'custom/files/';
$custom_files = array_diff(scandir($path), ['.', '..']);
@endphp
@foreach ($custom_files as $custom_file)
    @if (pathinfo($custom_file, PATHINFO_EXTENSION) == 'css')<link rel="stylesheet" href='{{ asset("custom/files/$custom_file") }}' />@endif
    @if (pathinfo($custom_file, PATHINFO_EXTENSION) == 'js')<script src="{{ asset("custom/files/$custom_file") }}"></script>@endif
@endforeach
@if(($config->google_analytics_id ?? null) && ($config->google_analytics_enabled ?? null))
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $config->google_analytics_id ?? null }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', '{{ $config->google_analytics_id ?? null }}');
</script>
@endif
