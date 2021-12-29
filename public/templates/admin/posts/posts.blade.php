<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts') }}">{{ __('Posts') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Posts') }} ({{ $posts->total() ?? 0 }} {{ __('posts') }})
                    @if($count_pending > 0)<a class="text-danger" href="{{ route('admin.posts', ['search_status' => 'pending']) }}">{{ $count_pending }} {{ __('pending review') }}</a>@endif
                    </h4>
                </div>

                <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                    @if (check_access('posts'))
                        <div class="float-end">

                            @if (check_access('posts'))
                                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary me-1 mb-1"><i class="bi bi-plus-circle"></i> {{ __('New post') }}</a>
                            @endif

                            @if (logged_user()->role == 'admin')
                                <a href="{{ route('admin.posts.categ') }}" class="btn btn-secondary me-1 mb-1"><i class="bi bi-diagram-3"></i> {{ __('Categories') }}</a>
                            @endif

                            @if (check_access('posts', 'manager'))
                                <a href="{{ route('admin.posts.comments') }}" class="btn btn-secondary me-1 mb-1"><i class="bi bi-chat-dots"></i> {{ __('Comments') }}</a>
                                <a href="{{ route('admin.posts.likes') }}" class="btn btn-secondary me-1 mb-1"><i class="bi bi-hand-thumbs-up"></i> {{ __('Likes') }}</a>
                            @endif

                            @if (logged_user()->role == 'admin')
                                <a href="{{ route('admin.posts.config') }}" class="btn btn-secondary mb-1"><i class="bi bi-gear"></i> {{ __('Config') }}</a>
                            @endif

                        </div>
                    @endif
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

            <section>
                <form action="{{ route('admin.posts') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="{{ __('Search in posts') }}" class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif" value="<?= $search_terms ?>" />
                    </div>

                    @if (count(sys_langs()) > 1)
                        <div class="col-12">
                            <select name="search_lang_id" class="form-select @if ($search_lang_id) is-valid @endif me-2 mb-2">
                                <option selected="selected" value="">- {{ __('Any language') }} -</option>
                                @foreach (sys_langs() as $sys_lang)
                                    <option @if ($search_lang_id == $sys_lang->id) selected @endif value="{{ $sys_lang->id }}"> {{ $sys_lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-12">
                        <select name="search_status" class="form-select me-2 mb-2 @if ($search_status) is-valid @endif">
                            <option value="">- {{ __('Any status') }} -</option>
                            <option @if ($search_status == 'active') selected="selected" @endif value="active">{{ __('Active') }}</option>
                            <option @if ($search_status == 'pending') selected="selected" @endif value="pending">{{ __('Pending review') }}</option>
                            <option @if ($search_status == 'draft') selected="selected" @endif value="draft">{{ __('Draft') }}</option>
                            <option @if ($search_status == 'soft_reject') selected="selected" @endif value="soft_reject">{{ __('Rejected (needs modifications)') }}</option>
                            <option @if ($search_status == 'hard_reject') selected="selected" @endif value="hard_reject">{{ __('Permanently rejected') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select class="form-select me-2 mb-2 @if ($search_categ_id) is-valid @endif" name="search_categ_id">
                            <option selected="selected" value="">- {{ __('All categories') }} -</option>
                            @foreach ($categories as $categ)
                                @include('admin.posts.loops.posts-filter-categories-loop', $categ)
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-secondary me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light mb-2" href="{{ route('admin.posts') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </section>

            <div class="mb-2"></div>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            @if (count(sys_langs()) > 1)
                                <th width="180">{{ __('Language') }}</th>
                            @endif
                            <th width="320">{{ __('Author') }}</th>
                            <th width="190">{{ __('Interractions') }}</th>
                            <th width="170">{{ __('Actions') }}</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($posts as $post)
                            <tr @if ($post->status != 'active') class="table-warning" @endif>

                                <td>
                                    @if ($post->status == 'draft') <div class="float-end ms-2 badge bg-warning fs-6">{{ __('Draft') }}</div> @endif
                                    @if ($post->status == 'pending') <div class="float-end ms-2 badge bg-danger fs-6">{{ __('Pending review') }}</div> @endif
                                    @if ($post->status == 'soft_reject') <div class="float-end ms-2 badge bg-info fs-6">{{ __('Rejected (needs modifications)') }}</div> @endif
                                    @if ($post->status == 'hard_reject') <div class="float-end ms-2 badge bg-dark fs-6">{{ __('Permanently rejected') }}</div> @endif

                                    @if ($post->featured == 1)
                                        <div class="float-end ms-2 badge bg-success fs-6"><i class="bi bi-pin"></i> {{ __('Featured') }}</div>
                                    @endif

                                    @if ($post->image)
                                        <div class="float-start me-2 mb-2"><img class="img-fluid" style="max-width:150px; max-height: 150px;" src="{{ thumb($post->image) }}" /></div>
                                    @endif

                                    <div class="listing">
                                        @if ($post->status == 'active' && $post->lang_status == 'active')<a target="_blank"
                                                href="{{ admin_post($post->id)->url }}">{{ $post->title }}</a>
                                        @else {{ $post->title }} @endif
                                    </div>

                                    <span class="text-muted small">
                                        {{ __('Created') }}: {{ date_locale($post->created_at, 'datetime') }}
                                        @if ($post->updated_at) | {{ __('Updated') }}: {{ date_locale($post->updated_at, 'datetime') }} | @endif
                                        {{ $post->hits }} {{ __('hits') }}
                                    </span>

                                    @if ($post->categ_id)
                                        <div class="mb-1"></div>
                                        {{ __('Category') }}:
                                        @foreach (breadcrumb($post->categ_id, 'posts') as $item)
                                            <a @if ($item->active != 1) class="text-danger" @endif target="_blank" href="{{ admin_posts_url($item->id) }}">{{ $item->title }}</a> @if (!$loop->last) / @endif @endforeach @endif </td>

                                @if (count(sys_langs()) > 1)
                                <td>
                                    {!! flag($post->lang_code) !!} {{ $post->lang_name ?? __('No language') }}
                                    @if ($post->lang_status != 'active') <span class="small text-danger">({{ __('inactive') }})</span>@endif
                                </td>
                                @endif

                                <td>
                                    @if ($post->author_avatar)
                                        <span class="float-start me-2"><img style="max-width:50px; height:auto;" src="{{ image($post->author_avatar) }}" /></span>
                                    @endif
                                    <b><a target="_blank" href="{{ route('admin.accounts.show', ['id' => $post->user_id]) }}">{{ $post->author_name }}</a></b>
                                    <br>{{ $post->author_email }}
                                </td>

                                <td>
                                    @if ($post->count_likes > 0)
                                        <a href="{{ route('admin.posts.likes', ['search_post_id' => $post->id]) }}"><i class="bi bi-hand-thumbs-up"></i> {{ $post->count_likes ?? 0 }}
                                                {{ __('likes') }}</a>                                        
                                    @else
                                        <i class="bi bi-hand-thumbs-up"></i> {{ __('No likes') }}
                                    @endif
                                    <div class="mb-2"></div>
                                    @if ($post->count_comments > 0)
                                        <div class="mt-2"><a href="{{ route('admin.posts.comments', ['search_post_id' => $post->id]) }}"><i class="bi bi-chat-dots"></i>
                                                {{ $post->count_comments ?? 0 }} {{ __('comments') }}</a>
                                        </div>
                                    @else
                                        <i class="bi bi-chat-dots"></i> {{ __('No comments') }}
                                    @endif

                                    @if ($post->disable_comments)<div class="mt-2 text-danger small">{{ __('Comments disabled') }}</div>@endif
                                    @if ($post->disable_likes)<div class="mt-2 text-danger small">{{ __('Likes disabled') }}</div>@endif
                                </td>

                                <td>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.posts.show', ['id' => $post->id]) }}" class="btn btn-primary btn-sm mb-2">{{ __('Update post') }}</a>

                                        @if (check_access('posts', 'manager'))
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $post->id }}" class="btn btn-danger btn-sm">{{ __('Delete post') }}</a>
                                            <div class="modal fade confirm-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this post?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.posts.show', ['id' => $post->id]) }}">
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

            {{ $posts->appends(['search_terms' => $search_terms, 'search_status' => $search_status, 'search_categ_id' => $search_categ_id, 'search_lang_id' => $search_lang_id])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
