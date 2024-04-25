<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.template') }}">{{ __('Template') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Footer') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
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
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif


        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold mt-3 mb-2">{{ __('Footer settings') }}</h5>

                <form action="{{ route('admin.template.footer') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12 col-xl-5 col-md-6">
                            <div class="form-group">
                                <label>{{ __('Choose footer layout') }}</label><br>
                                <select class="form-select" name="tpl_footer_columns">
                                    <option @if (($config->tpl_footer_columns ?? null) == '1') selected @endif value="1">{{ __('One column') }}</option>
                                    <option @if (($config->tpl_footer_columns ?? null) == '2') selected @endif value="2">{{ __('Two columns') }}</option>
                                    <option @if (($config->tpl_footer_columns ?? null) == '3') selected @endif value="3">{{ __('Three columns') }}</option>
                                    <option @if (($config->tpl_footer_columns ?? null) == '4') selected @endif value="4">{{ __('Four columns') }}</option>
                                </select>
                                <div class="text-muted small">{{ __('Select number of columns for primary footer') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input type='hidden' value='' name='tpl_footer2_show'>
                                    <input class="form-check-input" type="checkbox" id="footer2_show" name="tpl_footer2_show" @if ($config->tpl_footer2_show ?? null) checked @endif>
                                    <label class="form-check-label" for="footer2_show">{{ __('Show secondary footer') }}</label>
                                </div>
                                <div class="text-muted small">{{ __('This footer is below main footer') }}</div>
                            </div>

                            <script>
                                $('#footer2_show').change(function() {
                                    select = $(this).prop('checked');
                                    if (select)
                                        document.getElementById('hidden_div_footer2').style.display = 'block';
                                    else
                                        document.getElementById('hidden_div_footer2').style.display = 'none';
                                })
                            </script>

                            <div id="hidden_div_footer2" style="display: @if ($config->tpl_footer2_show ?? null) block @else none @endif">
                                <div class="row">
                                    <div class="col-12 col-xl-5 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Choose footer layout') }}</label><br>
                                            <select class="form-select" name="tpl_footer2_columns">
                                                <option @if (($config->tpl_footer2_columns ?? null) == '1') selected @endif value="1">{{ __('One column') }}</option>
                                                <option @if (($config->tpl_footer2_columns ?? null) == '2') selected @endif value="2">{{ __('Two columns') }}</option>
                                                <option @if (($config->tpl_footer2_columns ?? null) == '3') selected @endif value="3">{{ __('Three columns') }}</option>
                                                <option @if (($config->tpl_footer2_columns ?? null) == '4') selected @endif value="4">{{ __('Four columns') }}</option>
                                            </select>
                                            <div class="text-muted small">{{ __('Select number of columns for primary footer') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="redirect_to" value="footer">
                    <button type="submit" class="btn btn-primary mt-1">{{ __('Update footer settings') }}</button>
                </form>


            </div>



            <div class="col-md-6">
                <div class="fw-bold fs-5 mt-2 mb-3">{{ __('Footer content') }}</div>

                <div class="fw--bold mb-3">
                    <i class="bi bi-info-circle"></i> {{ __('You can set footer layout (number of columns) and activate secondary footer in Template Builder page.') }}
                    <a class="fw-bold" href="{{ route('admin.template') }}">{{ __('Go to template builder') }}</a>
                </div>

                <div class="d-grid gap-2 d-md-block mb-3">
                    <a class="btn btn-gear" href="{{ route('admin.template.footer.content', ['footer' => 'primary']) }}"><i class="bi bi-pencil-square"></i> {{ __('Manage main footer content') }}</a>
                    @if ($config->tpl_footer2_show ?? null)
                        <a class="btn btn-gear ms-2" href="{{ route('admin.template.footer.content', ['footer' => 'secondary']) }}"><i class="bi bi-pencil-square"></i>
                            {{ __('Manage secondary footer content') }}</a>
                    @endif
                </div>
            </div>
        </div>



    </div>
    <!-- end card-body -->

</div>
