<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config.template.logo') }}">{{ __('Server info') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 mb-3">
                    @include('admin.core.layouts.menu-tools')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Server info') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            <?php
            ob_start();
            phpinfo();
            $pinfo = ob_get_contents();
            ob_end_clean();
            $pinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
            print_r($pinfo);
            ?>

        </div>
        <!-- end card-body -->

    </div>

</section>
