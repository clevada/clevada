<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Languages') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">
    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Languages') }}</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#create-lang"><i class="bi bi-plus-circle"></i> {{ __('Add language') }}</a>
                        @include('admin.core.modals.create-lang')

                        @if (check_access('translates'))
                            <span class="float-end ms-2"><a class="btn btn-secondary" href="{{ route('admin.translates') }}"><i class="bi bi-flag"></i> {{ __('Manage translates') }}</a></span>
                        @endif
                    </div>
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
                    @if ($message == 'created') {{ __('Created') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This language exist') }} @endif
                    @if ($message == 'exists_content') {{ __('Error. This language can not be deleted because there is content in this language. You can make this language inactive') }} @endif
                    @if ($message == 'default') {{ __('Error. Default language can not be deleted') }} @endif
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            <th width="80">{{ __('Code') }}</th>
                            <th width="300">{{ __('Permalinks') }}</th>
                            <th width="250">{{ __('Locale') }}</th>
                            <th width="130">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($langs as $lang)

                            @php
                            $permalinks = unserialize($lang->permalinks);
                            @endphp

                            <tr @if ($lang->status != 'active') class="bg-light" @endif>
                                <td>
                                    @if ($lang->status == 'active')<span class="float-end ms-2 badge bg-success">{{ __('Active') }}</span>@endif
                                    @if ($lang->status == 'inactive')<span class="float-end ms-2 badge bg-warning">{{ __('Inactive') }}</span>@endif
                                    @if ($lang->status == 'disabled')<span class="float-end ms-2 badge bg-danger">{{ __('Disabled') }}</span>@endif

                                    
                                    @if ($lang->is_default == 1) <span class="float-end ms-2 badge bg-primary">{{ __('Default') }}</span> @endif
                                    <h5><img class="img-flag" src="{{ asset('assets/img/flags/'.$lang->code.'.png') }}"> {{ $lang->name }}</h5>

                                    <div class="small texx-muted">
                                        <i class="flag flag-us"></i>
                                        <b>{{ __('Site short title') }}</b>: {{ $lang->site_short_title }}
                                        <br>
                                        <b>{{ __('Homepage meta title') }}</b>: {{ $lang->homepage_meta_title }}
                                        <br>
                                        <b>{{ __('Homepage meta description') }}</b>: {{ $lang->homepage_meta_description }}
                                    </div>
                                </td>

                                <td>
                                    <h5>{{ $lang->code }}</h5>
                                </td>


                                <td>
                                    @if(check_admin_module('posts'))
                                    @if(check_module('posts'))
                                        {{ __('Permalink posts') }}:<br>                                                                           
                                        <a target="_blank" href="{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/{{ $permalinks['posts'] ?? 'blog' }}">{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['posts'] ?? 'blog' }}</b></a>
                                    @else
                                        {{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['posts'] ?? 'blog' }}</b>
                                    @endif                       
                                    <div class="mb-2"></div>
                                    @endif  
                                    
                                    @if(check_admin_module('cart'))
                                    @if(check_module('cart'))
                                        {{ __('Permalink shop') }}:<br>                                                                           
                                        <a target="_blank" href="{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/{{ $permalinks['cart'] ?? 'shop' }}">{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['cart'] ?? 'shop' }}</b></a>
                                    @else
                                        {{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['cart'] ?? 'shop' }}</b>
                                    @endif                       
                                    <div class="mb-2"></div>
                                    @endif 

                                    @if(check_admin_module('forum'))
                                    @if(check_module('forum'))
                                        {{ __('Permalink forum') }}:<br>                                                                           
                                        <a target="_blank" href="{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/{{ $permalinks['forum'] ?? 'forum' }}">{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['forum'] ?? 'forum' }}</b></a>
                                    @else
                                        {{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['forum'] ?? 'forum' }}</b>
                                    @endif                       
                                    <div class="mb-2"></div>
                                    @endif 

                                    @if(check_admin_module('docs'))
                                    @if(check_module('docs'))
                                        {{ __('Permalink knowledge base') }}:<br>                                                                           
                                        <a target="_blank" href="{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/{{ $permalinks['docs'] ?? 'docs' }}">{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['docs'] ?? 'docs' }}</b></a>
                                    @else
                                        {{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['docs'] ?? 'docs' }}</b>
                                    @endif                       
                                    <div class="mb-2"></div>
                                    @endif 

                                    @if(check_admin_module('contact'))
                                    @if(check_module('docs'))
                                        {{ __('Permalink contact page') }}:<br>                                                                           
                                        <a target="_blank" href="{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/{{ $permalinks['contact'] ?? 'contact' }}">{{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['contact'] ?? 'contact' }}</b></a>
                                    @else
                                        {{ $config->site_url }}@if (!$lang->is_default == 1)/{{ $lang->code }}@endif/<b>{{ $permalinks['contact'] ?? 'contact' }}</b>
                                    @endif                       
                                    <div class="mb-2"></div>
                                    @endif 

                                    {{ __('Permalink tags') }}: <b>{{ $permalinks['tag'] ?? 'tag' }}</b>
                                    <div class="mb-2"></div>
                                    
                                    {{ __('Permalink search') }}: <b>{{ $permalinks['search'] ?? 'search' }}</b>
                                    <div class="mb-2"></div>

                                    {{ __('Permalink profile') }}: <b>{{ $permalinks['profile'] ?? 'profile' }}</b>
                                    <div class="mb-2"></div>
                                </td>

                                <td>
                                    <div class="small texx-muted">
                                        <b>{{ __('Code') }}: {{ $lang->locale }}</b>
                                        <div class="mb-2"></div>
                                        {{ __('Date format') }}:
                                        @php
                                            setlocale(LC_TIME, $lang->locale ?? 'ro_RO');
                                        @endphp
                                        {{ strftime($lang->date_format ?? '%e %b %Y', strtotime('2019-12-30')) }}

                                        <div class="mb-2"></div>
                                        {{ __('Timezone') }}: {{ $lang->timezone ?? 'Europe/London' }}
                                        <div class="mb-2"></div>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-grid gap-2">
                                        <button data-bs-toggle="modal" data-bs-target="#update-lang-{{ $lang->id }}" class="btn btn-primary btn-sm">{{ __('Settings') }}</button>
                                        @include('admin.core.modals.update-lang')

                                        <a class="btn btn-secondary btn-sm mt-2" href="{{ route('admin.translate_lang', ['id' => $lang->id]) }}">{{ __('Translates') }}</a>

                                        <button data-bs-toggle="modal" data-bs-target="#update-permalinks-{{ $lang->id }}" class="btn btn-secondary btn-sm mt-2">{{ __('Permalinks') }}</button>
                                        @include('admin.core.modals.update-permalinks')
                                       

                                        @if ($lang->is_default != 1)
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $lang->id }}" class="btn btn-danger btn-sm mt-2">{{ __('Delete') }}</a>
                                            <div class="modal fade confirm-{{ $lang->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this language?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.config.langs.show', ['id' => $lang->id]) }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                                <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
