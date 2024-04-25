<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.templates.index') }}">{{ __('Website templates') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-3">
                @include('admin.template.includes.menu-template')
            </div>

        </div>

    </div>



    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'created')
                    {{ __('Created') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. This button exists') }}
                @endif
            </div>
        @endif

        <div class="fw-bold fs-5 mb-3">{{ __('Active template') }}</div>

        <div class="card card-template bg-light">
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                    @if ($activeTemplate['screenshot'])
                        @php
                            $imageData = base64_encode(file_get_contents($activeTemplate['screenshot']));
                            $src = 'data: ' . mime_content_type($activeTemplate['screenshot']) . ';base64,' . $imageData;
                            echo '<img class="img-fluid" src="' . $src . '">';
                        @endphp
                    @else
                        <img src="{{ config('app.cdn') }}/img/no-image.png" class="card-img-top" alt="No image">
                    @endif
                </div>

                <div class="col-xl-10 col-lg-9 col-md-8 col-12">

                    <div class="card-body">
                        <h4 class="card-title">{{ $activeTemplate['name'] }}</h4>
                        <p class="card-text">{{ $activeTemplate['description'] }}</p>

                        <a href="{{ route('admin.templates.show', ['slug' => basename($activeTemplate['path'])]) }}" class="btn btn-success btn-lg"><i class="bi bi-pencil-square"></i>
                            {{ __('Edit template') }}</a>

                        <a class="btn btn-secondary btn-lg ms-3" target="_blank" href="{{ route('home', ['preview_template' => basename($activeTemplate['path'])]) }}"><i class="bi bi-box-arrow-up-right"></i>
                            {{ __('Preview') }}</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="fw-bold fs-5 mb-3 mt-4">{{ __('All templates') }}</div>

        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-4 g-4">
            @foreach ($templates as $template)
                <div class="col">
                    <div class="card card-template h-100">
                        @if ($activeTemplate['screenshot'])
                            @php
                                $imageData = base64_encode(file_get_contents($activeTemplate['screenshot']));
                                $src = 'data: ' . mime_content_type($activeTemplate['screenshot']) . ';base64,' . $imageData;
                                echo '<img class="img-fluid" src="' . $src . '">';
                            @endphp
                        @else
                            <img src="{{ config('app.cdn') }}/img/no-image.png" class="card-img-top" alt="No image">
                        @endif

                        <div class="card-body bg-light">
                            @if ($config->activeTemplate != basename($template['path']))
                                <a href="{{ route('admin.template.set_default', ['slug' => basename($template['path'])]) }}" class="btn btn-danger me-2"><i class="bi bi-check-square"></i>
                                    {{ __('Set default') }}</a>
                            @endif

                            <a href="{{ route('admin.templates.show', ['slug' => basename($template['path'])]) }}" class="btn btn-success me-2"><i class="bi bi-pencil-square"></i>
                                {{ __('Edit template') }}</a>

                            <a class="btn btn-secondary me-2" target="_blank" href="{{ route('home', ['preview_template' => basename($template['path'])]) }}"><i class="bi bi-box-arrow-up-right"></i>
                                {{ __('Preview') }}</a>

                            <hr>

                            @if ($config->activeTemplate == basename($template['path']))
                                <span class="float-end ms-2 badge bg-info">{{ __('Active') }}</span>
                            @endif
                            <h4 class="card-title">{{ $template['name'] }}</h4>
                            <p class="card-text">{{ $template['description'] }}</p>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    <!-- end card-body -->

</div>
