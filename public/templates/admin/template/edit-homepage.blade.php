<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.templates') }}">{{ __('Templates') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$template->label }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    @include('admin.template.layouts.menu-template')
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="float-end"><a class="btn btn-secondary" target="_blank" href="{{ route('homepage', ['preview_template_id' => $template->id]) }}"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview website') }}</a></div>

            <h4 class="mt-2 mb-3">{{ __('Edit template') }}: {{ $template->label }}</h4>
            
            <div class="mb-3">
                @include('admin.template.layouts.menu-template-edit')
            </div>       

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') 
                    <h4 class="alert-heading">{{ __('Updated') }}</h4>
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Info: If you don\'t see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.') }}
                    @endif
                    @if ($message == 'layout_updated') 
                        {{ __('Layout updated.') }}
                    @endif
                    @if ($message == 'deleted') 
                    {{ __('Deleted') }}
                @endif
                </div>
            @endif

            @include('admin.template.layouts.edit-layout-top') 

            <div class="mb-5"></div>

            @include('admin.template.layouts.edit-layout-content') 
            
            @include('admin.template.layouts.edit-layout-sidebar') 

            <div class="mb-5"></div>

            @include('admin.template.layouts.edit-layout-bottom') 

        </div>
        <!-- end card-body -->

    </div>

</section>
