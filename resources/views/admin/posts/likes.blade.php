<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">{{ __('Posts') }}</a></li>
                    @if ($search_post_id && $post)
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.show', ['id' => $search_post_id]) }}">{{ $post->title }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts.likes') }}">{{ __('Likes') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <h4 class="card-title">{{ $likes->total() ?? 0 }} {{ __('likes') }} - @if ($search_post_id && $post)
                {{ $post->title }}
            @else
                {{ __('all posts') }}
            @endif
        </h4>

    </div>


    <div class="card-body">

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        <div class="table-responsive-md">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Post details') }}</th>
                        <th width="250">{{ __('Details') }}</th>
                        <th width="140"></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($likes as $like)
                        <tr>
                            <td>
                                @if ($like->post->image)
                                    <img class="img-fluid float-start me-3" style="max-width:110px; height:auto;" src="{{ thumb($like->post->image) }}" />
                                @endif
                                <div class="fw-bold"><a target="_blank" href="{{ route('post', ['slug' => $like->post->slug, 'categ_slug' => $like->post->category->slug]) }}">{{ $like->post->title }}</a></div>
                                <div class="mt-1">{{ $like->post->likes ?? 0 }} {{ __('likes') }}</div>
                            </td>

                            <td>
                                <div class="text-muted small">
                                    <b>{{ date_locale($like->created_at, 'datetime') }}</b>
                                    <br>
                                    IP: {{ $like->ip }}
                                </div>
                            </td>

                            <td>
                                <div class="d-grid gap-2">
                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $like->id }}" class="btn btn-danger btn-sm">{{ __('Delete like') }}</a>
                                    <div class="modal fade confirm-{{ $like->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="fw-bold mb-2">{{ __('Are you sure you want to delete this like?') }}</div>
                                                    <div class="small text-muted"><i class="bi bi-info-circle"></i> {{ __('Note: likes counter will be recalculated, but the user can not like again this post.') }}</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.posts.likes.destroy', ['id' => $like->id, 'search_post_id' => $search_post_id]) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                        <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{ $likes->appends(['search_post_id' => $search_post_id])->links() }}

    </div>
    <!-- end card-body -->

</div>
