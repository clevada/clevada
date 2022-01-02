<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ site()->url }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ posts_url() }}">{{ __('Blog') }}</a></li>
        @foreach (breadcrumb($post->categ_id) as $categ)
            <li class="breadcrumb-item"><a href="{{ posts_url($categ->id) }}">{{ $categ->title }}</a></li>
        @endforeach
    </ol>
</nav>

<div class="post">

    <h1>{{ $post->title }}</h1>

    <div class="light small mb-3">
        @if ($post->created_at)<i class="bi bi-calendar"></i> {{ date_locale($post->created_at) }} @endif

        @if ($post->author_avatar) <img src="{{ thumb($post->author_avatar) }}" alt="{{ $post->author_name }}" class="avatar rounded-circle ms-2">
        @else <i class="bi bi-person ms-2"></i>@endif
        <a href="{{ profile_url($post->user_id) }}">{{ $post->author_name }}</a>

        @if ($post->hits)<i class="bi bi-eye ms-2"></i> {{ $post->hits }} {{ __('visits') }} @endif

        @if ($comments->total() > 0)<i class="far fa-comments ms-2"></i> <a href="#comments">{{ $comments->total() }} {{ __('comments') }}</a> @endif

        @if ($post->minutes_to_read > 0)<i class="bi bi-clock ms-2"></i> {{ $post->minutes_to_read }} {{ __('minutes read') }} @endif
    </div>

    @if ($post->image)
        <div class="main-image mb-4">
            <img class="img-fluid" src="{{ image($post->image) }}" alt="{{ $post->title }}" title="{{ $post->title }}">
        </div>
    @endif

    <div class="addthis_inline_share_toolbox mb-2"></div>

    @if ($post->summary)
        <div>
            <p class="fw-bold mb-0">{{ $post->summary }}</p>
        </div>
    @endif

    <div class="content">
        @foreach (content_blocks('posts', $post->id) as $block)
            @php
                $block_extra = unserialize($block->block_extra);
            @endphp
            <div class="section" id="block-{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
                @include("{$template_view}.includes.blocks-switch")
            </div>
        @endforeach
    </div>

    @if ($tags)
        <div class="tags mb-2">
            @foreach ($tags as $tag)
                <div class="tag me-2 mb-2 float-start"><a href="{{ $tag->slug }}">{{ $tag->tag }}</a></div>
            @endforeach
        </div>
    @endif

    @if (!(($config->posts_likes_disabled ?? null) || ($post->disable_likes ?? null)))
        <div class="post_likes mb-4">
            <button class="btn btn-light like"><i class="bi bi-hand-thumbs-up"></i> {{ $post->likes }} {{ __('likes') }}</button>

            <div id="like_success" class="text-success small mt-2" style="display: none; font-weight:bold">{{ __('You like this') }}</div>
            <div id="like_error" class="text-danger small mt-2" style="display: none; font-weight:bold">{{ __('You already like this') }}</div>
            <div id="login_required" class="text-danger small mt-2" style="display: none; font-weight:bold">{{ __('You must login to like') }}:
                <a href="{{ route('login') }}">{{ __('Login') }}</a>
            </div>
        </div>
    @endif



    @if ($comments->total() > 0)
        <p class="mt-3 mb-1">{{ $comments->total() }} {{ __('comments') }}</p>

        <a class="anchor" name="comments" id="comments"></a>

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
            <div class="alert alert-success mb-3">
                @if ($message == 'comment_added') {{ __('Comment added') }} @endif
                @if ($message == 'comment_pending') {{ __('Comment must be approved before publish') }} @endif
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger mb-3">
                @if ($message == 'login_required') {{ __('You must be logged to comment') }} @endif
                @if ($message == 'recaptcha_error') {{ __('Wrong antispam') }} @endif
            </div>
        @endif

        <ul class="comment-list">
            @foreach ($comments as $comment)
                <li class="comment p-2 bg-light mb-3">
                    <div class="comment-body">
                        @if ($comment->user_id)
                            @if ($comment->author_avatar)
                                <img src="{{ thumb($comment->author_avatar) }}" alt="{{ $comment->author_name }}" class="img-fluid rounded-circle" style="max-height: 25px;">
                            @endif
                            <span class="author"><a href="{{ profile_url($comment->user_id) }}">{{ $comment->author_name }}</a></span>
                            <span class="meta">{{ date_locale($comment->created_at, 'datetime') }}</span>
                        @else
                            <span class="author">{{ $comment->name }}</span>
                            <span class="meta">{{ date_locale($comment->created_at, 'datetime') }}</span>
                        @endif
                        <div class="comment">{!! nl2br(e($comment->comment)) !!}</div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="clearfix"></div>
        {{ $comments->fragment('comments')->links() }}
    @endif


    @if (!(($config->posts_comments_disabled ?? null) || ($post->disable_comments ?? null)))
        @if (($config->posts_comments_require_login ?? null) && !Auth::check())
            {{ __('You must login to comment') }}: <a href="{{ route('login') }}">{{ __('Login') }}</a>
        @else
            <div class="comment-form-wrap mt-2 mb-4">
                <form method="post" action="{{ posts_submit_comment_url($post->categ_slug, $post->slug, active_lang()->code) }}">
                    @csrf

                    <div class="mb-2 fs-5">{{ __('Leave a comment') }}:</div>

                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" class="form-control" name="name" required @if (Auth::user()) value="{{ Auth::user()->name }}" readonly @endif>
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" class="form-control" name="email" required @if (Auth::user()) value="{{ Auth::user()->email }}" readonly @endif>
                    </div>
                    <div class="form-group">
                        <label for="message">{{ __('Message') }}</label>
                        <textarea name="comment" rows="6" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{ $post->id }}">
                        @if ($config->posts_comments_antispam_enabled ?? null)<input type="hidden" name="recaptcha_response" id="recaptchaResponse">@endif
                        <input type="submit" value="{{ __('Post comment') }}" class="btn btn1">
                    </div>

                </form>
            </div>
        @endif
    @endif

</div>
