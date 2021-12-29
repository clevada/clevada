
<div class="card mb-5">

    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <div class="user-header">
                    <i class="bi-plus-circle"></i> {{ __('Create new article') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card-body bg-white">

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!$categories)
            <div class="alert alert-danger mt-3">
                {{ __('Warning. You can not add an post because there is not any categoriy added by administrator') }}.
            </div>
        @else

            <form action="{{ route('user.posts') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="form-group col-xl-9 col-md-8 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Post title') }}</label>
                            <input class="form-control" name="title" type="text" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Summary') }}</label>
                            <textarea rows="3" class="form-control" name="summary"></textarea>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Search terms') }} ({{ __('separated by comma') }})</label>
                            <input type="text" class="form-control" name="search_terms" aria-describedby="searchHelp">
                            <small id="searchHelp" class="form-text text-muted">
                                {{ __("Search terms don't appear on website but they are used to find post in search form") }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="formFile" class="form-label">{{ __('Upload image') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="file" id="formFile" name="image">
                        </div>

                        <hr>

                        <div class="form-group">
                            <button type="submit" name="status" value="active" class="btn btn-primary">{{ __('Save and add content') }}</button>
                        </div>

                        <div class="form-text text-muted">{{ __('You can add blocks content in the next step') }}</div>

                    </div>

                    <div class="form-group col-xl-3 col-md-4 col-sm-12 border-left">

                        <div class="form-group">
                            <label>{{ __('Category') }}</label>
                            <select name="categ_id" class="form-select" required>
                                <option value="">- {{ __('select') }} -</option>
                                @foreach ($categories as $categ)
                                    @include('frontend.builder.user.posts.includes.categories-add-select-loop', $categ)
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Tags') }}</label>
                            <input type="text" class="form-control tagsinput" name="tags" id="tags">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Custom Meta title') }}</label>
                            <input type="text" class="form-control" name="meta_title" aria-describedby="metaTitleHelp">
                            <small id="metaTitleHelp" class="form-text text-muted">
                                {{ __('Leave empty to auto generate meta title based on post title') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Custom Meta description') }}</label>
                            <input type="text" class="form-control" name="meta_description" aria-describedby="metaDescHelp">
                            <small id="metaDescHelp" class="form-text text-muted">
                                {{ __('Leave empty to auto generate meta description based on post summary') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Custom slug') }}</label>
                            <input type="text" class="form-control" name="slug" aria-describedby="slugHelp">
                            <small id="slugHelp" class="form-text text-muted">
                                {{ __('Leave empty to auto generate slug based on post title') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input id="checkbox_disable_comments" type="checkbox" name="disable_comments" class="custom-control-input">
                                <label for="checkbox_disable_comments" class="custom-control-label"> {{ __('Disable comments') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input id="checkbox_disable_ratings" type="checkbox" name="disable_ratings" class="custom-control-input">
                                <label for="checkbox_disable_ratings" class="custom-control-label"> {{ __('Disable ratings') }}</label>
                            </div>
                        </div>                       


                    </div>

                </div><!-- end row -->

            </form>
        @endif

    </div>
    <!-- end card-body -->

</div>

