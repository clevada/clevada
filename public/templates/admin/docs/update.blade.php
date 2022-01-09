<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.docs') }}">{{ __('Knowledge Base') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Update article') }}</li>
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
                    <h4 class="card-title">{{ __('Update Article') }}</h4>
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
                    @if ($message == 'duplicate') {{ __('Error. This label exists') }} @endif
                </div>
            @endif

            <form method="post" action="{{ route('admin.docs.show', ['id' => $doc->id]) }}">
                @csrf
                @method('PUT')

                <div class="card p-3 bg-light mb-4">
                    <div class="row">

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" required value="{{ $doc->label }}">
                                <div class="form-text">
                                    {{ __('Tip: input a representative short description about this article. You can use this label to search a specific article in template editor. Label is not visible on public website.') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Select category') }}</label>
                                <select name="categ_id" class="form-select mr-2" required>
                                    <option selected="selected" value="">- {{ __('select') }} -</option>
                                    @foreach ($categories as $categ)
                                        @include('admin.docs.loops.post-edit-select-loop', $categ)
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-3 col-12">
                            <div class=" form-group">
                                <label>{{ __('Article visibility') }}</label>
                                <select name="visibility" class="form-select" required>
                                    <option @if($doc->visibility == 'public') selected @endif value="public">{{ __('Public (anyone can read this article)') }}</option>
                                    <option @if($doc->visibility == 'private') selected @endif value="private">{{ __('Private (only registered and logged users ca read this article)') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Article position in this category') }}</label>
                                <input class="form-control" name="position" value="{{ $doc->position }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch1" name="active" @if ($doc->active) checked @endif>
                                    <label class="form-check-label" for="customSwitch1">{{ __('Active') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch2" name="featured" @if ($doc->featured) checked @endif>
                                    <label class="form-check-label" for="customSwitch2">{{ __('Featured article') }}</label>
                                </div>
                            </div>
                        </div>
                    
                    </div>

                </div>


                @foreach ($langs as $lang)

                    @if (count(sys_langs()) > 1)<div class="fs-5">{!! flag($lang->code) !!} {{ $lang->name }}</div> @endif
                    <div class="form-group">
                        <label>{{ __('Title') }}</label>
                        <input class="form-control" name="title_{{ $lang->id }}" type="text" required value="{{ $lang->title }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Custom permalink') }} ({{ __('leave empty to auto generate') }})</label>
                                <input type="text" class="form-control" name="slug_{{ $lang->id }}" value="{{ $lang->slug }}">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Search terms (separated by comma)') }}</label>
                                <input type="text" class="form-control" name="search_terms_{{ $lang->id }}" value="{{ $lang->search_terms }}">
                                <div class="form-text">
                                    {{ __("These terms are used in the search form. It's important to add all the search terms that describe this article") }}
                                </div>
                            </div>
                        </div>                        
                    </div>

                    @if (count(sys_langs()) > 1 && ! $loop->last)<hr>@endif
                    <div class="clearfix mb-3"></div>

                @endforeach

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
