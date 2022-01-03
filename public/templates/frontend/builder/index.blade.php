<!DOCTYPE html>
<html lang="{{ $lang ?? default_lang()->code }}">

<head>
    <title>{{ site()->meta_title }}</title>
    <meta name="description" content="{{ site()->meta_description }}">
    @include("{$template_view}.global.head")

    @if (check_page_recaptcha('homepage'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ $config->google_recaptcha_site_key ?? null }}"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $config->google_recaptcha_site_key ?? null }}', {
                    action: 'contact'
                }).then(function(token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        </script>
    @endif
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
