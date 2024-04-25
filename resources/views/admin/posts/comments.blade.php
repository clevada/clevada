<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">{{ __('Posts') }}</a></li>
                    @if ($search_post_id)
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.show', ['id' => $post->id]) }}">{{ $post->title }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts.comments') }}">{{ __('Comments') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <h4 class="card-title">{{ $comments->total() ?? 0 }} {{ __('comments') }} - @if (!$search_post_id)
                {{ __('all posts') }}
            @else
                {{ $post->title }}
            @endif
        </h4>

        @if ($count_pending_comments > 0)
            <div class="mb-2"></div>
            <a class="text-danger" href="{{ route('admin.posts.comments', ['search_status' => 'pending']) }}"><i class="bi bi-exclamation-circle"></i> {{ $count_pending_comments }}
                {{ __('pending review') }}</a>
        @endif

    </div>


    <div class="card-body">

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif

        <section>
            <form action="{{ route('admin.posts.comments') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                <div class="col-12">
                    <input type="text" name="search_terms" placeholder="{{ __('Search in comments') }}" class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif" value="<?= $search_terms ?>" />
                </div>

                <div class="col-12">
                    <select name="search_status" class="form-select me-2 mb-2 @if ($search_status) is-valid @endif">
                        <option value="">- {{ __('Any status') }} -</option>
                        <option @if ($search_status == 'active') selected="selected" @endif value="active">{{ __('Active') }}</option>
                        <option @if ($search_status == 'pending') selected="selected" @endif value="pending">{{ __('Pending review') }}</option>
                    </select>
                </div>

                <div class="col-12">
                    <button class="btn btn-secondary me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                    <a class="btn btn-light mb-2" href="{{ route('admin.posts.comments') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </section>

        <div class="mb-2"></div>

        <div class="table-responsive-md">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="460">{{ __('Post details') }}</th>
                        <th>{{ __('Comment') }}</th>
                        <th width="300">{{ __('Author') }}</th>
                        <th width="140"></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($comments as $comment)
                        <tr @if ($comment->status == 'pending') class="bg-light" @endif>
                            <td>
                                @if ($comment->post->image)
                                    <img class="img-fluid float-start me-2" style="max-width:80px; height:auto;" src="{{ thumb($comment->post->image) }}" />
                                @endif
                                <b class="small"><a target="_blank" href="{{ route('post', ['slug' => $comment->post->slug, 'categ_slug' => $comment->post->category->slug]) }}">{{ $comment->post->title }}</a></b>
                            </td>

                            <td>
                                @if ($comment->status == 'pending')
                                    <span class="float-end ms-2 badge bg-warning fs-6">{{ __('Pending') }}</span>
                                @endif

                                <div class="text-muted small fw-bold">{{ date_locale($comment->created_at, 'datetime') }}</div>

                                @if ($comment->updated_by_user_id)
                                    <div class="text-muted small mt-1">{{ __('Updated at') }}: {{ date_locale($comment->updated_at, 'datetime') }} {{ __('by') }} <a target="_blank"
                                            href="{{ route('admin.accounts.show', ['id' => $comment->updated_by_user_id]) }}">{{ $comment->updated_by_user_name ?? null }}</a></div>
                                @endif

                                <div class="mb-3"></div>

                                {!! nl2br($comment->comment) !!}
                            </td>

                            <td>
                                @if ($comment->user_id)
                                    @if ($comment->author->avatar)
                                        <img src="{{ avatar($comment->user_id) }}" class="img-fluid rounded-circle" style="max-height: 24px;">
                                    @endif
                                    <span class="author"><a target="_blank" href="{{ route('profile', ['username' => $comment->author->username]) }}"><b>{{ $comment->author->name }}</b></a></span>
                                    <div class="clearfix"></div>
                                    <a target="_blank" href="{{ route('admin.accounts.show', ['id' => $comment->user_id]) }}">{{ __('Account details') }}</a>
                                @else
                                    {{ $comment->name }} ({{ __('visitor') }})<br>
                                @endif
                                <div class="mt-2"></div>
                                IP: {{ $comment->ip }}
                                <br>
                                <small>
                                    {{ count_ip_comments($comment->ip, 'posts_comments')->all }} {{ __('comments from this IP') }}
                                    ({{ count_ip_comments($comment->ip, 'posts_comments')->pending }} {{ __('pending') }})
                                </small>
                            </td>

                            <td>
                                <div class="d-grid gap-2">
                                    <button data-bs-toggle="modal" data-bs-target="#update-comment-{{ $comment->id }}" class="btn btn-primary btn-sm btn-block">{{ __('Update') }}</button>
                                    @include('admin.posts.modals.update-comment')


                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $comment->id }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a>
                                    <div class="modal fade confirm-{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this comment?') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.posts.comments.destroy', ['id' => $comment->id, 'search_post_id' => $search_post_id]) }}">
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

        {{ $comments->appends(['search_post_id' => $search_post_id])->links() }}

    </div>
    <!-- end card-body -->

</div>
