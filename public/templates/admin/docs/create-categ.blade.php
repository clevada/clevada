@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.docs') }}">{{ __('Knowledge Base') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.docs.categ') }}">{{ __('Categories') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Create category') }}</li>
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
                    <h4 class="card-title">{{ __('Create category') }}</h4>
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

            <form method="post" action="{{ route('admin.docs.categ') }}">
                @csrf

                <div class="p-2 bg-light mb-3">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Category label') }}</label>
                                <input class="form-control" name="label" type="text" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Select parent category') }}</label>
                                <select class="form-select" name="parent_id">
                                    <option value="">{{ __('Root (no parent)') }}</option>
                                    @foreach ($categories as $categ)
                                        @include('admin.docs.loops.categories-add-select-loop', $categ)
                                    @endforeach
                                </select>
                            </div>
                        </div>                                                                               
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Icon code') }} ({{ __('optional') }}) <a target="_blank" href="{{ route('admin.config.icons') }}"><i class="bi bi-question-circle"></i></a></label>
                                <input class="form-control" name="icon" type="text" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Position') }}</label>
                                <input class="form-control" name="position" type="text" />
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">{{ __('Active') }}</label>
                            </div>
                        </div>

                    </div>
                </div>



                @foreach (sys_langs() as $lang)

                    @if (count(sys_langs()) > 1) <h5 class="mb-2">{!! flag($lang->code) !!} {{ $lang->name }} @if ($lang->is_default) ({{ __('default language') }})@endif</h5> @endif

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Category title') }}</label>
                                <input class="form-control" name="title_{{ $lang->id }}" type="text" required />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Description') }} ({{ __('optional') }}) </label>
                                <textarea class="form-control" name="description_{{ $lang->id }}" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Custom URL structure') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="slug_{{ $lang->id }}" type="text" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta title') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="meta_title_{{ $lang->id }}" type="text" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="meta_description_{{ $lang->id }}" rows="2"></textarea>
                            </div>
                        </div>

                    </div>

                    @if (count(sys_langs()) > 1 && !$loop->last)<hr>@endif

                @endforeach

                <button type="submit" class="btn btn-primary">{{ __('Create category') }}</button>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
