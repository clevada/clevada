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
                @if ($post->status != 'published')
                    <div class="fw-bold text-danger mt-1 mb-2">
                        <i class="bi bi-exclamation-circle"></i> {{ __('Post is not published. Go to post details to publish this post.') }}
                        <a href="{{ route('admin.posts.show', ['id' => $post->id]) }}">{{ __('Post details') }}</a>
                    </div>
                    <div class="clearfix"></div>
                @endif

                <div class="fw-bold fs-5">
                    {{ $post->title }}
                </div>

            </div>

            <div class="clearfix"></div>

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
                @if ($message == 'post_created')
                    <i class="bi bi-info-circle"></i> {{ __('Post created. You can add content to this post. After you add content blocks, you can publish the post.') }}
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

        <div class="row gy-5">

            <div class="col-12">
                <div class="builder-col sortable" id="sortable_top">
                    @if (!$post->deleted_at)
                        @can('update', $post)
                            <div class="mb-4 text-center">
                                <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#addBlock"><i class="bi bi-plus-circle"></i> {{ __('Add content block') }}</a>
                            </div>
                        @endcan
                    @endif

                    @foreach (content_blocks('posts', $post->id, $show_hidden = 1) as $block)
                        <div class="builder-block movable" id="item-{{ $block->id }}">
                            <div class="float-end ms-2">

                                @if ($block->hide == 1)
                                    <div class="badge bg-danger fs-6 me-2">{{ __('Hidden') }}</div>
                                @endif

                                <a href="{{ route('admin.blocks.show', ['id' => $block->id]) }}" class="btn btn-primary">{{ __('Manage content') }}</a>

                                @can('update', $post)
                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $block->id }}" class="btn btn-outline-danger ms-2"><i class="bi bi-trash"></i></a>
                                    <div class="modal fade confirm-{{ $block->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to remove this block from this post? Block content will be deleted.') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.posts.content.delete', ['id' => $post->id, 'block_id' => $block->id]) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                        <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </div>

                            @if ($block->label)
                                <div class="listing">{{ $block->label }}</div>
                            @endif

                            <b>
                                @include('admin.includes.block_type_label', ['type' => $block->type])
                            </b>

                            <div class="small text-muted">
                                ID: {{ $block->id }}. @if ($block->updated_at)
                                    {{ __('Updated at') }}: {{ date_locale($block->updated_at, 'datetime') }}
                                @endif
                            </div>

                            @if ($block->type == 'editor')
                                <div class="line-clamp-4 mt-1 small">{!! $block->content !!}</div>
                            @endif

                            @if ($block->type == 'text')
                                @if ($block->content ?? null)
                                    <div class="mt-1 small">
                                        @php $content_array = unserialize($block->content) @endphp
                                        @if ($content_array['title'] ?? null)
                                            <b>{{ $content_array['title'] }}</b></small>
                                        @endif
                                        @if ($content_array['subtitle'] ?? null)
                                            <b>{{ $content_array['subtitle'] }}</b></small>
                                        @endif
                                        @if ($content_array['content'] ?? null)
                                            <div class="line-clamp-4 mt-1">{{ $content_array['content'] }}</div>
                                        @endif
                                    </div>
                                @endif
                            @endif

                            @if ($block->type == 'image')
                                @if ($block->content ?? null)
                                    <div class="mt-1 small">
                                        @php $content_array = unserialize($block->content) @endphp
                                        @if ($content_array['image'] ?? null)
                                            <img class="img-fluid" style="max-height: 100px;" src="{{ thumb($content_array['image']) }}">
                                        @endif
                                        @if ($content_array['caption'] ?? null)
                                            <br><b>{{ $content_array['caption'] }}</b>
                                        @endif
                                        @if ($content_array['url'] ?? null)
                                            <div class="mt-1"><a target="_blank" href="{{ $content_array['url'] }}">{{ $content_array['url'] }}</div>
                                        @endif
                                    </div>
                                @endif
                            @endif

                            @if ($block->type == 'gallery')
                                @if ($block->content ?? null)
                                    <div class="mt-1 small">
                                        @php $contents_array = unserialize($block->content) @endphp
                                        @foreach ($contents_array as $content_array)
                                            @if ($content_array['image'] ?? null)
                                                <img class="img-fluid me-2" style="max-height: 100px;" src="{{ thumb($content_array['image']) }}">
                                            @endif                                           
                                        @endforeach
                                    </div>
                                @endif
                            @endif


                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @include('admin.includes.modal-add-content-block', ['module' => 'posts', 'content_id' => $post->id])

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#sortable_top").sortable({
                    axis: 'y',
                    opacity: 0.8,
                    revert: true,

                    update: function(event, ui) {
                        var data = $(this).sortable('serialize');
                        $.ajax({
                            data: data,
                            type: 'POST',
                            url: "{{ route('admin.posts.sortable', ['id' => $post->id]) }}",
                        });
                    }
                });
                $("#sortable_top").disableSelection();

            });
        </script>



    </div>
    <!-- end card-body -->

</div>
