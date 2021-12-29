@if ($message = Session::get('success'))
    <div class="alert alert-success">
        @if ($message == 'topic_created') {{ __('Topic created') }} @endif
    </div>
@endif

<div class="row">

    <div class="col-12 mb-1">

        <div class="float-end">
            <a class="btn btn1 ms-4" href="{{ route('forum.topic.create') }}"><i class="bi bi-plus-circle"></i> {{ __('New topic') }}</a>
        </div>

        <div class="float-start">
            <form method="get" action="{{ route('forum.search_results') }}">
                <input class="form-control" name="s" placeholder="{{ __('Search in forum') }}" required>
            </form>
        </div>

        <div class="clearfix mb-3"></div>

        <nav aria-label="breadcrumb" class="forum-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ site()->url }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active"><a href="{{ forum_url() }}">{{ __('Forum') }}</a></li>
            </ol>
        </nav>
    </div>

    @foreach (forum_categ_tree() as $section)
        <div class="col-12 mb-4">
            <div class="forum-section">

                <div class="card-header forum-card-header">
                    {!! $section->icon ?? null !!} <a class="section-title" title="{{ $section->title }}" href="{{ route('forum.categ', ['slug' => $section->slug]) }}">{{ $section->title }}</a>
                    @if ($section->description)<div class="mb-1"></div><small>{{ $section->description }}</small>@endif
                </div>

                <div class="card-body forum-categ-card-body">
                    <div class="table-responsive-md">
                        <table class="table table-forum">
                            <tbody>
                                @foreach ($section->children as $categ)
                                    <tr>
                                        <td>
                                            {!! $categ->icon ?? null !!} <a class="forum-categ-link" title="{{ $categ->title }}"
                                                href="{{ route('forum.categ', ['slug' => $categ->slug]) }}">{{ $categ->title }}</a>

                                            <div class="mb-1"></div>

                                            @if ($categ->description)<small>{{ $categ->description }}</small><div class="mb-1"></div>@endif

                                            @foreach ($categ->children as $subcateg)
                                                <i class="bi bi-dot"></i> <a class="forum-subcateg-link me-2" title="{{ $subcateg->title }}"
                                                    href="{{ route('forum.categ', ['slug' => $subcateg->slug]) }}">{{ $subcateg->title }}</a>
                                            @endforeach
                                        </td>

                                        <td width="130">
                                            <b>{{ $categ->count_tree_topics ?? 0 }}</b> {{ __('subjects') }}

                                            <div class="mb-2"></div>
                                            <b>{{ $categ->count_tree_posts ?? 0 }}</b> {{ __('responses') }}
                                        </td>

                                        <td width="400">
                                            @if (!$categ->latest_activity)
                                                {{ __('No activity') }}
                                            @endif

                                            @if ($categ->latest_activity == 'topic')
                                                <small>

                                                    @if ($categ->latest_topic->author_avatar)
                                                        <img src="{{ thumb($categ->latest_topic->author_avatar) }}" class="avatar rounded-circle">
                                                    @else
                                                        <img src="{{ asset('uploads/default/no-avatar-icon.png') }}" class="avatar rounded-circle">
                                                    @endif
                                                    <b>{{ $categ->latest_topic->author_name }}</b>

                                                    {{ __('created new topic') }}:<br>
                                                    <a class="forum-link" title="{{ $categ->latest_topic->title }}"
                                                        href="{{ route('forum.topic', ['id' => $categ->latest_topic->id, 'slug' => $categ->latest_topic->slug]) }}">{{ substr($categ->latest_topic->title, 0, 40) }}@if (strlen($categ->latest_topic->title) > 40)...@endif
                                                    </a> {{ __('at') }}
                                                    <span class="small">{{ date_locale($categ->latest_topic->created_at, 'datetime') }}</span>
                                                </small>
                                                <div class="mb-2"></div>
                                            @endif


                                            @if ($categ->latest_activity == 'post')

                                                <a class="forum-link" title="{{ $categ->latest_post->topic_title }}"
                                                    href="{{ route('forum.topic', ['id' => $categ->latest_post->topic_id, 'slug' => $categ->latest_post->topic_slug]) }}">{{ substr($categ->latest_post->topic_title, 0, 48) }}@if (strlen($categ->latest_post->topic_title) > 48)...@endif
                                                </a>

                                                <div class="mt-1"></div>

                                                @if ($categ->latest_post->author_avatar)
                                                    <img src="{{ thumb($categ->latest_post->author_avatar) }}" class="avatar rounded-circle">
                                                @else
                                                    <img src="{{ asset('uploads/default/no-avatar-icon.png') }}" class="avatar rounded-circle">
                                                @endif
                                                <a href="{{ profile_url($categ->latest_post->author_user_id) }}">{{ $categ->latest_post->author_name }}</a> <span
                                                    class="small">{{ date_locale($categ->latest_post->created_at, 'datetime') }}</span>

                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
