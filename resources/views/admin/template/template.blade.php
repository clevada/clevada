@include('admin.includes.color-picker')

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                @include('admin.template.includes.menu-template')

                <div class="fw-bold mt-3 fs-5"><a href="{{ route('admin.templates.index') }}">{{ __('Templates') }}</a> / {{ $template['name'] }}</div>
            </div>

        </div>

    </div>


    <div class="card-body">

        <nav class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <a class="nav-item nav-link @if (($tab ?? null) == 'core-style') active @endif" href="{{ route('admin.templates.show', ['slug' => $slug, 'tab' => 'core-style']) }}">{{ __('Style') }}</a>
            <a class="nav-item nav-link @if (($tab ?? null) == 'core-navs') active @endif" href="{{ route('admin.templates.show', ['slug' => $slug, 'tab' => 'core-navs']) }}">{{ __('Menu & Footer') }}</a>
            <a class="nav-item nav-link @if (($tab ?? null) == 'core-posts') active @endif" href="{{ route('admin.templates.show', ['slug' => $slug, 'tab' => 'core-posts']) }}">{{ __('Posts Settings') }}</a>
            <a class="nav-item nav-link @if (($tab ?? null) == 'core-ads') active @endif" href="{{ route('admin.templates.show', ['slug' => $slug, 'tab' => 'core-ads']) }}">{{ __('Ads management') }}</a>
            @if ($template_menu_view ?? null)
                @include("{$template_menu_view}")
            @endif
        </nav>


        @if ($message = Session::get('success'))
            <div class="alert alert-success py-2">
                @if ($message == 'updated')
                    <div class="fw-bold">{{ __('Updated') }}</div>
                    @if (($tab ?? null) == 'core-style' || ($tab ?? null) == 'core-navs')
                        <i class="bi bi-exclamation-circle"></i> {{ __("Note: if you don't see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.") }}
                    @endif
                @endif
            </div>
        @endif


        @if ($template_tab_view ?? null)
            <form method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include("{$template_tab_view}")
                <hr>
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </form>
        @endif


    </div>
    <!-- end card-body -->

</div>
