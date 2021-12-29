<div class="card-header forum-card-header">
    {{ date_locale($topic->created_at, 'datetime') }}
    <span class="float-end fw-bold"><a href="#">#{{ $topic->id }}</a></span>
</div>

<div class="card-header forum-topic-header-info">
    <span class="float-end">
        {{ __('Registered') }}: {{ date_locale($topic->author_created_at) }}
        <br>
        {{ __('Topic') }}: {{ date_locale($topic->user_id, 'count_forum_topics', 'datetime') ?? 0 }}
        <br>
        {{ __('Posts') }}: {{ date_locale($topic->user_id, 'count_forum_posts', 'datetime') ?? 0 }}
    </span>

    @if($topic->author_avatar)
    <span class="float-start me-2">
        <img src="{{ thumb($topic->author_avatar) }}" class="img-fluid" style="max-width: 90px;">
    </span>
    @endif
    <b>{{ $topic->author_name }}</b>
    <br>
    {{ __('Helpful posts') }}: <span @if(user_extra($topic->user_id, 'count_forum_likes_received')>10) class="bold text-success" @endif>{{ user_extra($topic->user_id, 'count_forum_likes_received') }}</span><br>
    {{ __('Best answer posts') }}: <span @if(user_extra($topic->user_id, 'count_forum_best_answers_received')>10) class="bold text-success" @endif>{{ user_extra($topic->user_id, 'count_forum_best_answers_received') }}</span>
</div>