<!-- Tags -->
<script src="{{ asset('assets/vendors/tags-input-autocomplete/dist/jquery.tagsinput.min.js') }}"></script>
<link href="{{ asset('assets/vendors/tags-input-autocomplete/dist/jquery.tagsinput.min.css') }}" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts') }}">{{ __('Posts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
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

                    <div class="float-end"><a target="_blank" href="{{ admin_post($post->id)->url }}" class="btn btn-sm btn-secondary"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview') }}</a>
                    </div>

                    <h4 class="card-title">{{ __('Update') }} - {{ $post->title }}</h4>
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
                    @include('admin.posts.post-blocks')
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

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="checkbox_featured" type="checkbox" name="featured" class="custom-control-input" aria-describedby="featuredHelp" @if ($post->featured == 1) checked @endif>
                                    <label for="checkbox_featured" class="custom-control-label"> {{ __('Featured') }}</label>
                                    <small id="featuredHelp" class="form-text text-muted">
                                        {{ __('Featured posts are displayed first') }}
                                    </small>
                                </div>
                            </div>

                            <hr />

                            @if ($post->author_avatar)
                            <span class="float-start me-2"><img style="max-width:60px; height:auto;" src="{{ image($post->author_avatar) }}" /></span>
                            @endif
                            <b><a target="_blank" href="{{ route('admin.accounts.show', ['id' => $post->user_id]) }}">{{ $post->author_name }}</a></b>
                            <br>
                            {{ __('Posts') }}: <i class="bi bi-check text-success"></i> {{ $author_count_published_posts }} {{ __('published') }}
                            @if($author_count_pending_posts > 0), <i class="bi bi-check text-warning"></i> {{ $author_count_pending_posts }} {{ __('pending') }}@endif
                            @if($author_count_soft_reject_posts > 0), <i class="bi bi-check text-warning"></i> {{ $author_count_soft_reject_posts }} {{ __('needs modifications') }}@endif
                            @if($author_count_hard_reject_posts > 0), <i class="bi bi-check text-danger"></i> {{ $author_count_hard_reject_posts }} {{ __('permanently rejected') }}@endif

                            <div class="clearfix"></div>

                            <div class="form-group mt-3 col-md-6 col-12">
                                <label>{{ __('Post status') }}</label>
                                <select class="form-select form-select-lg" name="status" id="status" onchange="showRejected()">
                                    <option @if ($post->status == 'active') selected @endif value="active">{{ __('Published') }}</option>
                                    <option @if ($post->status == 'draft') selected @endif value="draft">{{ __('Draft') }}</option>
                                    <option @if ($post->status == 'pending') selected @endif value="pending">{{ __('Pending review') }}</option>
                                    <option @if ($post->status == 'soft_reject') selected @endif value="soft_reject">{{ __('Rejected (needs modifications)') }}</option>
                                    <option @if ($post->status == 'hard_reject') selected @endif value="hard_reject">{{ __('Permanently rejected') }}</option>
                                </select>
                            </div>

                            <script>
                                function showRejected() {
                                    var select = document.getElementById('status');
                                    var value = select.options[select.selectedIndex].value;

                                    if (value == 'soft_reject' || value == 'hard_reject') {
                                        document.getElementById('div_reject_reason').style.display = 'block';
                                    } else {
                                        document.getElementById('div_reject_reason').style.display = 'none';
                                    }
                                }
                            </script>

                            <div id="div_reject_reason" style="display: @if ($post->status == 'soft_reject' || $post->status == 'hard_reject') block @else none @endif">
                                <div class="form-group col-12">
                                    <label>{{ __('Reject reason') }}</label>
                                    <textarea name="reject_reason" class="form-control" rows="5">{{ $post->reject_reason ?? null }}</textarea>
                                    <div class="form-text">{{ __('This notes are visible for the author') }}.</div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </form>
                    </div>

                </div>

            </div><!-- end row -->

        </div>
        <!-- end card-body -->

    </div>

</section>

<script>
    $(document).ready(function() {
        'use strict';

        $('.tagsinput').tagsInput({
            'width': 'auto',
            'defaultText': "{{ __('Add a tag') }}",
            'autocomplete_url': "{{ route('admin.ajax', ['source' => 'posts_tags']) }}"
        });
    });
</script>
