<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Forum config') }}</li>
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
                    @include('admin.forum.layouts.menu-config')
                </div>

                <div class="col-12">
                    <h4 class="card-title">{{ __('SEO') }}</h4>
                    <p>{{ __('Meta title and meta description for community main page') }}</p>
                </div>

            </div>

        </div>

        <div class="card-body">
            
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                </div>
            @endif

            <form method="post">
                @csrf

                @foreach (sys_langs() as $lang)

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Meta title') }}
                                    @if (count(sys_langs()) > 1)- {{ $lang->name }} @if ($lang->is_default) ({{ __('default language') }})@endif @endif
                                </label>
                                <input name="meta_title_{{ $lang->id }}" class="form-control" value="{{ module_meta('forum', $lang->id)->meta_title ?? null }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Meta description') }}
                                    @if (count(sys_langs()) > 1)- {{ $lang->name }} @if ($lang->is_default) ({{ __('default language') }})@endif @endif
                                </label>
                                <textarea rows="2" name="meta_description_{{ $lang->id }}" class="form-control">{{ module_meta('forum', $lang->id)->meta_description ?? null }}</textarea>
                            </div>
                        </div>
                    </div>

                    @if (count(sys_langs()) > 1 && ! $loop->last)<hr>@endif

                @endforeach

                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>

            </form>


        </div>
        <!-- end card-body -->

    </div>

</section>
