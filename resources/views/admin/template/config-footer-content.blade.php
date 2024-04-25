<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.template') }}">{{ __('Template') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.template.footer') }}">{{ __('Footer') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Footer content') }}</li>
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

            <div class="col-12">
                <h4 class="card-title">
                    @if ($footer == 'primary')
                        {{ __('Update primary footer content') }}
                        @endif @if ($footer == 'secondary')
                            {{ __('Update secondary footer content') }}
                        @endif
                </h4>
                <div class="form-text">{{ __('Click on "Add blocs" to add content blocks (text, images, columns, ...)') }}</div>
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. Page with this slug already exists') }}
                @endif
                @if ($message == 'length2')
                    {{ __('Error. Page slug must be minimum 3 characters') }}
                @endif
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif

        @if ($footer == 'primary')
            @if (($config->tpl_footer_columns ?? null) == '1')
                @include('admin.template.includes.edit-footer-1-col', ['footer' => 'primary'])
            @elseif (($config->tpl_footer_columns ?? null) == '2')
                @include('admin.template.includes.edit-footer-2-cols', ['footer' => 'primary'])
            @elseif (($config->tpl_footer_columns ?? null) == '3')
                @include('admin.template.includes.edit-footer-3-cols', ['footer' => 'primary'])
            @elseif (($config->tpl_footer_columns ?? null) == '4')
                @include('admin.template.includes.edit-footer-4-cols', ['footer' => 'primary'])
            @endif
        @endif

        @if ($footer == 'secondary')
            @if (($config->tpl_footer2_columns ?? null) == '1')
                @include('admin.template.includes.edit-footer-1-col', ['footer' => 'secondary'])
            @elseif (($config->tpl_footer2_columns ?? null) == '2')
                @include('admin.template.includes.edit-footer-2-cols', ['footer' => 'secondary'])
            @elseif (($config->tpl_footer2_columns ?? null) == '3')
                @include('admin.template.includes.edit-footer-3-cols', ['footer' => 'secondary'])
            @elseif (($config->tpl_footer2_columns ?? null) == '4')
                @include('admin.template.includes.edit-footer-4-cols', ['footer' => 'secondary'])
            @endif
        @endif

    </div>
    <!-- end card-body -->

</div>
