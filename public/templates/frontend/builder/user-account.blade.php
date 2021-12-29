<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>

    <title>{{ Auth::user()->name }} - {{ __('My account') }}</title>

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

            <div class="container-xxl mt-4">

                <div class="row">

                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                        @include('frontend/builder.user.sidebar')
                    </div>

                    <div class="col-sm-12 col-md-8 col-lg-9 col-xl-10">
                        @include("frontend/builder.user.{$view_file}")
                    </div>

                </div>

            </div>

        </div>
        <!-- End Main Content -->

        @include("{$template_view}.global.footer")

    </div>
    <!-- End Wrapper -->

    <!-- Tags -->
    <script src="{{ asset('assets/vendors/tags-input-autocomplete/dist/jquery.tagsinput.min.js') }}"></script>
    <link href="{{ asset('assets/vendors/tags-input-autocomplete/dist/jquery.tagsinput.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script>
        $(document).ready(function() {
            'use strict';

            $('.tagsinput').tagsInput({
                'width': 'auto',
                'defaultText': "{{ __('Add a tag') }}",
                'autocomplete_url': "{{ route('admin.ajax', ['source' => 'posts_tags']) }}"
            });

            $('.sub-menu ul').hide();
            @if ($nav_menu ?? null) $('.{{ $nav_menu ?? null }} ul').show(); @endif
            $(".sub-menu a").click(function() {
                $(this).parent(".sub-menu").children("ul").slideToggle("100");
                $(this).find(".right").toggleClass("bi-chevron-up bi-chevron-down");
            });

        });
    </script>
</body>

</html>
