<div class="card mb-5">

    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <div class="user-header">
                    <a class="btn btn1 btn-sm float-end" href="{{ route('user.posts.create') }}"><i class="bi bi-plus-circle"></i> {{ __('Create new article') }}</a>
                    <i class="bi bi-card-text"></i> {{ __('My articles') }} ({{ $posts->total() ?? 0 }})
                </div>
            </div>
        </div>
    </div>


    <div class="card-body">

        @if (count($posts) == 0)
            {{ __("You don't have any post created") }}
        @else

            <div class="table-responsive-md">

                <table class="table table-bordered table-hover">
                    <tbody>

                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    @if ($post->status == 'draft' || $post->status == 'soft_reject')<a class="btn btn1 float-end btn-sm ms-2" href="{{ route('user.posts.show', ['id' => $post->id]) }}"><i class="bi bi-pencil-square"></i> {{ __('Update post') }}</a>@endif

                                    @if ($post->status == 'active')<button class="btn btn-success float-end btn-sm">{{ __('Published') }}</button>@endif
                                    @if ($post->status == 'draft')<button class="btn btn-warning text-white float-end btn-sm">{{ __('Draft') }}</button>@endif
                                    @if ($post->status == 'pending')<button class="btn btn-info text-white float-end btn-sm">{{ __('Pending review') }}</button>@endif
                                    @if ($post->status == 'soft_reject')<button class="btn btn-warning text-white float-end btn-sm">{{ __('Rejected') }}</button>@endif
                                    @if ($post->status == 'hard_reject')<button class="btn btn-danger text-white float-end btn-sm">{{ __('permanently rejected') }}</button>@endif


                                    <div class="fs-6 fw-bold"><a href="#">{{ $post->title }}</a></div>
                                    <div class="text-muted small">{{ __('Created') }}: {{ date_locale($post->created_at, 'datetime') }}</div>

                                    @if ($post->status == 'soft_reject' && $post->reject_reason)
                                        <div class="alert alert-warning mt-2"><b>{{ __('This post needs more changes to be published') }}</b>
                                            <br>{{ $post->reject_reason }}
                                        </div>
                                    @endif

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        @endif

        {{ $posts->links() }}

    </div>

</div>
