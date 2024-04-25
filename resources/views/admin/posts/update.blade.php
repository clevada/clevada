<!-- Tags -->
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

<style>
    .tagify__tag {
        line-height: 1.2em !important;
    }
</style>


@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index', ['post_type' => $post_type]) }}">{{ __('Posts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-3">
                @include('admin.posts.includes.menu-post')
            </div>

            <div class="col-12">

                <div class="float-end">
                    @if ($post->status == 'published')
                        <a target="_blank" href="{{ route('post', ['slug' => $post->slug, 'categ_slug' => $post->category->slug]) }}" class="btn btn-sm btn-secondary"><i class="bi bi-box-arrow-up-right"></i>
                            {{ __('Preview') }}</a>
                    @endif
                </div>

                <h4 class="card-title">
                    @if ($post->status != 'published')
                        <div class="badge bg-warning float-end">{{ __('Not published') }}</div>
                    @endif {{ $post->title }}
                </h4>
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
                @if ($message == 'main_image_deleted')
                    {{ __('Deleted') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        @if (Session::get('upload_fails') == true)
            <div class="alert alert-warning">
                {{ __('Warning: Image was not uploaded.') }}
            </div>
        @endif

        @if ($post->deleted_at)
            <div class="text-danger fw-bold mb-2">
                {{ __('This item is in the Trash.') }}
            </div>
        @endif

        <form method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="form-group col-xl-8 col-md-7 col-sm-12">
                    <div class="p-3 bg-light">
                        <div class="form-group">
                            <label>{{ __('Post title') }}</label>
                            <textarea rows="2" class="form-control" name="title">{{ $post->title }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Summary') }}</label>
                            <textarea rows="5" class="form-control" name="summary">{{ $post->summary }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Search terms') }} ({{ __('separated by comma') }})</label>
                            <input type="text" class="form-control" name="search_terms" aria-describedby="searchHelp" value="{{ $post->search_terms }}">
                            <small id="searchHelp" class="form-text text-muted">
                                {{ __("Search terms don't appear on website but they are used to find post in search form") }}
                                <br>
                                <i class="bi bi-info-circle"></i> {{ __("Note: you don't need to add terms which are in the post title or tags") }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="formFile" class="form-label">{{ __('Change image') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="file" id="formFile" name="image">
                            <div class="text-muted small">{{ __('Image file. Maximum 5 MB') }}</div>

                            @if ($post->image)
                                <div class="mt-3"></div>

                                <div class="float-start me-2"><img style="max-width:25px; height:auto;" src="{{ thumb($post->image) }}" /></div>

                                <a target="_blank" href="{{ image($post->image) }}">{{ __('View image') }}</a>
                                @can('update', $post)
                                    | <a class="text-danger" href="{{ route('admin.posts.delete_main_image', ['id' => $post->id]) }}">{{ __('Delete image') }}</a>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group col-xl-4 col-md-5 col-sm-12">
                    <div class="p-3 bg-light mb-3">
                        @if ($post->author->avatar)
                            <span class="float-start me-2"><img class="img-fluid rounded rounded-circle" style="width:25px;" src="{{ avatar($post->user_id) }}" /></span>
                        @endif
                        <b><a target="_blank" href="{{ route('admin.accounts.show', ['id' => $post->user_id]) }}">{{ $post->author->name }}</a></b>

                        <div class="clearfix"></div>
                        <hr>

                        <div class="form-group col-md-6 col-12">
                            <label>{{ __('Post status') }} </label>
                            <select class="form-select form-select-lg" name="status">
                                <option @if ($post->status == 'published') selected @endif value="published">{{ __('Published') }}</option>
                                <option @if ($post->status == 'draft') selected @endif value="draft">{{ __('Draft') }}</option>
                            </select>
                            </select>
                        </div>


                        @foreach ($taxonomies as $taxonomy)
                            <div class="col-12">
                                <div class="form-group">
                                    @if ($taxonomy->filter_type == 'select')
                                        <label> {{ __(json_decode($taxonomy->labels)->plural ?? $taxonomy->name) }}</label>
                                        <select class="form-select me-2 mb-2 @if ($search_taxonomy_id ?? null) is-valid @endif" name="search_taxonomy_id">
                                            <option selected="selected" value="">- {{ __('select') }} -</option>
                                            @foreach ($taxonomy->items as $taxonomy_item)
                                                @include('admin.posts.loops.posts-filter-taxonomies-loop', $taxonomy_item)
                                            @endforeach
                                        </select>
                                        @if ($taxonomy->items_count == 0)
                                            <div class="form-text">{{ __('No item') }}</div>
                                        @endif
                                    @else
                                        <input type="text" name="search_taxonomy_term" placeholder="{{ __(json_decode($taxonomy->labels)->search ?? 'Add ' . $taxonomy->name) }}"
                                            class="form-control me-2 mb-2 @if ($search_taxonomy_term ?? null) is-valid @endif" value="<?= $search_taxonomy_term ?? null ?>" />
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{--
                        <div class="form-group col-md-6 col-12">
                            <label>{{ __('Category') }}</label>
                            <select name="categ_id" class="form-select form-select-lg" required>
                                <option selected="selected" value="">- {{ __('select') }} -</option>
                                @foreach ($categories as $categ)
                                    @include('admin.posts.loops.post-edit-select-loop', $categ)
                                @endforeach
                            </select>
                        </div>
                        --}}

                        <div class="form-group">
                            <label>{{ __('Tags') }}</label>
                            <input type="text" class="form-control tagsinput" name="tags" id="tags" placeholder='{{ __('Write some tags') }}' value="{{ $post->tags }}">
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitchComments" name="disable_comments" @if ($post->disable_comments) checked @endif
                                    @if ($config->posts_comments_disabled ?? null) disabled @endif>
                                <label class="form-check-label" for="customSwitchComments">{{ __('Disable comments for this post') }}</label>
                            </div>
                            @if ($config->posts_comments_disabled ?? null)
                                <div class="text-danger">{{ __('The commenting system is disabled globally.') }} <a target="_blank" href="{{ route('admin.posts.config') }}">{{ __('Change') }}</a></div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitchLikes" name="disable_likes" @if ($post->disable_likes) checked @endif
                                    @if ($config->posts_likes_disabled ?? null) disabled @endif>
                                <label class="form-check-label" for="customSwitchLikes">{{ __('Disable likes for this post') }}</label>
                            </div>
                            @if ($config->posts_likes_disabled ?? null)
                                <div class="text-danger">{{ __('The like system is disabled globally.') }} <a target="_blank" href="{{ route('admin.posts.config') }}">{{ __('Change') }}</a></div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitchFeatured" name="featured" @if ($post->featured) checked @endif>
                                <label class="form-check-label" for="customSwitchFeatured">{{ __('Featured') }}</label>
                                <span class="form-text text-muted small">({{ __('Featured posts are displayed first') }})</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 my-3">
                            <a class="btn btn-secondary fw-bold" data-bs-toggle="collapse" href="#collapseSettings" role="button" aria-expanded="false" aria-controls="collapseExample">
                                {{ __('More settings') }} <i class="bi bi-chevron-down"></i>
                            </a>
                        </div>

                        <div class="collapse" id="collapseSettings">
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
                        </div>


                        <div class="clearfix"></div>

                        @can('update', $post)
                            <button type="submit" class="btn btn-primary mt-3">{{ __('Update') }}</button>
                        @endcan
                    </div>

                </div>

            </div><!-- end row -->
        </form>


        @can('delete', $post)
            @if (!$post->deleted_at)
                <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $post->id }}" class="btn btn-danger">{{ __('Delete post') }}</a>
                <div class="modal fade confirm-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{ __('Are you sure you want to move this item to trash?') }}

                                <div class="mt-2 fw-bold">
                                    <i class="bi bi-info-circle"></i> {{ __('This item will be moved to trash. You can recover it or permanently delete from recycle bin.') }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{ route('admin.posts.show', ['id' => $post->id]) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                    <button type="submit" class="btn btn-danger">{{ __('Yes. Move to trash') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endcan

    </div>
    <!-- end card-body -->

</div>

{{--
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
--}}


<script>
    $(document).ready(function() {

        var input = document.querySelector('#tags'),
            tagify = new Tagify(input, {
                whitelist: [{!! $all_tags ?? null !!}],
                dropdown: {
                    maxItems: 30, // <- mixumum allowed rendered suggestions
                    classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0, // <- show suggestions on focus
                    closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                }
            })
    });
</script>
