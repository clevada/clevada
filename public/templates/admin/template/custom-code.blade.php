<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.template.custom_code') }}">{{ __('Custom code') }}</a></li>
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
                    @include('admin.template.layouts.menu-template')
                </div>               

            </div>

        </div>


        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'invalid_file') {{ __('Invalid file., Only css and javascript files are allowed') }} @endif
                </div>
            @endif

            <div class="alert alert-info" role="alert">
                <b>{{ __('This template codes will be inserted in frontend template.') }}.</b><br>
                {{ __('Head code is inserted inside <head>...</head> section in your template code.') }}.<br>
                {{ __('Footer code is inserted at the end section in your template code.') }}.<br>
                {{ __('You can add global css / javascript files, or even html / javascript code.') }}.
            </div>

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-row">

                    <div class="form-group col-12">
                        <label>{{ __('Code added in template head') }}</label>
                        <textarea class="form-control" name="template_global_head_code" rows="10">{{ $config->template_global_head_code ?? null }}</textarea>
                    </div>

                    <div class="form-group col-12">
                        <label>{{ __('Code added in template footer') }}</label>
                        <textarea class="form-control" name="template_global_footer_code" rows="10">{{ $config->template_global_footer_code ?? null }}</textarea>
                    </div>

                </div>

                <div class="form-group">
                    <label for="formFile" class="form-label">{{ __('Upload file') }} ({{ __('only css and js files are allowed') }})</label>						
                      <input class="form-control" type="file" id="formFile" name="file">
                </div>                 

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

            <hr>

            <h4>{{ __('Custom files') }}:</h4>
          
            @foreach($custom_files as $file) 
            <b>{{ $file }}</b> [<a target="_blank" href="/custom/files/{{ $file }}">{{ __('View') }}</a>] [<a class="text-danger" href="{{ route('admin.template.custom_code.delete_file', ['file' => $file]) }}">{{ __('Delete') }}</a>]
            <div class="mb-1"></div>
            @endforeach
         
        </div>
        <!-- end card-body -->

    </div>

</section>
