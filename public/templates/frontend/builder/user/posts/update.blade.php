    <div class="card mb-5">

        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <div class="user-header">
                        <div class="float-end"><a href="{{ route('user.posts') }}" class="btn btn-sm btn-secondary text-white">{{ __('Back to articles') }}</a>
                        </div>
                        {{ __('Update') }} - {{ $post->title }}
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
                    @if ($message == 'main_image_deleted') {{ __('Deleted') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            <div class="row">

                <div class="form-group col-xl-8 col-md-7 col-sm-12">
                    @include('frontend.builder.user.posts.post-blocks')
                </div>

                <div class="form-group col-xl-4 col-md-5 col-sm-12">
                    <div class="p-3 bg-light">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>{{ __('Post title') }}</label>
                                <textarea rows="2" class="form-control" name="title">{{ $post->title }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Summary') }}</label>
                                <textarea rows="4" class="form-control" name="summary">{{ $post->summary }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Category') }}</label>
                                <select name="categ_id" class="form-select mr-2" required>
                                    <option selected="selected" value="">- {{ __('select') }} -</option>
                                    @foreach ($categories as $categ)
                                        @include('admin.posts.loops.post-edit-select-loop', $categ)
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Tags') }}</label>
                                <input type="text" class="form-control tagsinput" name="tags" id="tags" value="{{ $tags }}">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Search terms') }} ({{ __('separated by comma') }})</label>
                                <input type="text" class="form-control" name="search_terms" aria-describedby="searchHelp" value="{{ $post->search_terms }}">
                                <small id="searchHelp" class="form-text text-muted">
                                    {{ __("Search terms don't appear on website but they are used to find post in search form") }}
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="formFile" class="form-label">{{ __('Change image') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="file" id="formFile" name="image">

                                @if ($post->image)
                                    <div class="mt-3"></div>

                                    <div class="float-start me-2"><img style="max-width:25px; height:auto;" src="{{ thumb($post->image) }}" /></div>

                                    <a target="_blank" href="{{ image($post->image) }}">{{ __('View image') }}</a> |
                                    @if (check_access('posts', 'manager'))
                                        <a class="text-danger" href="{{ route('admin.posts.delete_main_image', ['id' => $post->id]) }}">{{ __('Delete image') }}</a>
                                    @endif
                                @endif
                            </div>

                            <div class="form-group">
                                <label>{{ __('Custom Meta title') }}</label>
                                <input type="text" class="form-control" name="meta_title" aria-describedby="metaTitleHelp" value="{{ $post->meta_title }}">
                                <small id="metaTitleHelp" class="form-text text-muted">
                                    {{ __('Leave empty to auto generate meta title based on post title') }}
                                </small>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Custom Meta description') }}</label>
                                <input type="text" class="form-control" name="meta_description" aria-describedby="metaDescHelp" value="{{ $post->meta_description }}">
                                <small id="metaDescHelp" class="form-text text-muted">
                                    {{ __('Leave empty to auto generate meta description based on post summary') }}
                                </small>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Custom slug') }}</label>
                                <input type="text" class="form-control" name="slug" aria-describedby="slugHelp" value="{{ $post->slug }}">
                                <small id="slugHelp" class="form-text text-muted">
                                    {{ __('Leave empty to auto generate slug based on post title') }}
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="checkbox_disable_comments" type="checkbox" name="disable_comments" class="custom-control-input" @if ($post->disable_comments == 1) checked @endif>
                                    <label for="checkbox_disable_comments" class="custom-control-label"> {{ __('Disable comments') }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="checkbox_disable_likes" type="checkbox" name="disable_likes" class="custom-control-input" @if ($post->disable_likes == 1) checked @endif>
                                    <label for="checkbox_disable_likes" class="custom-control-label"> {{ __('Disable likes') }}</label>
                                </div>
                            </div>
                       
                            <hr />

                            <div class="form-group">
                                <button type="submit" name="status" value="active" class="btn btn-primary"><i class="fas fa-share"></i> {{ __('Save and Publish') }}</button>
                                <button type="submit" name="status" value="draft" class="btn btn-secondary">{{ __('Save draft') }}</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div><!-- end row -->

        </div>
        <!-- end card-body -->

    </div>

