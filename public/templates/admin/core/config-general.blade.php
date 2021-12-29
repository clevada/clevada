<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config.general') }}">{{ __('Configuration') }}</a></li>
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
                    @include('admin.core.layouts.menu-config')
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
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif

            <h4 class="mt-3">{{ __('Modules settings') }}</h4>
            <p>{{ __('Enable / disable modules needed for your website') }}</p>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Module') }}</th>
                            <th width="160">{{ __('Update') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($modules as $module)
                            <tr @if ($module->status == 'inactive' || $module->status == 'disabled') class="bg-light" @endif>
                                <td>                                    
                                    <h4>                                       
                                        @if ($module->status == 'disabled') <button type="button" class="btn btn-danger btn-sm disabled me-2">{{ __('Disabled') }}</button> @endif
                                        @if ($module->status == 'inactive') <button type="button" class="btn btn-warning btn-sm disabled me-2">{{ __('Inactive') }}</button> @endif
                                        @if ($module->status == 'active') <button type="button" class="btn btn-success btn-sm disabled me-2">{{ __('Active') }}</button> @endif
                                        {{ $module->label }}
                                    </h4>                                    
                                    @if($module->route_admin && $module->status != 'disabled')[<a href="{{ route($module->route_admin) }}">{{ __('Manage') }}</a>] @endif
                                    @if($module->route_web && $module->status == 'active')[<a target="_blank" href="{{ route($module->route_web) }} ">{{ __('View on website') }}</a>] @endif

                                    @if ($module->module == 'pages')<div class="text-muted mt-2">{{ __('Create static pages') }}</div>@endif									
                                    @if ($module->module == 'posts')<div class="text-muted mt-2">{{ __('Add posts who belongs to categories (blog posts, articles, news...)') }}</div>@endif								
                                    @if ($module->module == 'docs')<div class="text-muted mt-2">{{ __('Add a Knowledge Base section') }}</div>@endif								
                                    @if ($module->module == 'forms')<div class="text-muted mt-2">{{ __('Create forms and manage messages') }}</div>@endif		
                                    @if ($module->module == 'tasks')<div class="text-muted mt-2">{{ __('Create and manage tasks') }}</div>@endif														
                                    @if ($module->module == 'forum')<div class="text-muted mt-2">{{ __('Create a community forum') }}</div>@endif								
                                </td>

                                <td>
                                    <div class="d-flex">
                                        <button data-bs-toggle="modal" data-bs-target="#update-module-{{ $module->id }}" class="btn btn-primary btn-sm btn-block">{{ __('Module settings') }}</button>
                                        @include('admin.core.modals.update-module')
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
        <!-- end card-body -->

    </div>

</section>
