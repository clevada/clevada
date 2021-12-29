@php
$data = block($block->id);
@endphp

@php
$data = unserialize($data->content);

if (($block_extra['style'] ?? null) == 'rows') {
    $cols = 1;
} else {
    $cols = $block_extra['columns'] ?? 2;
}

if ($cols == 1) {
    $class = 'col-12';
}
if ($cols == 2) {
    $class = 'col-sm-6 col-12';
}
if ($cols == 3) {
    $class = 'col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12';
}
if ($cols == 4) {
    $class = 'col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12';
}
@endphp


@if (($block_extra['style'] ?? null) == 'rows')

    @foreach (posts() as $post)

        @if ($block_extra['show_image'] ?? null)
            <div class="col-xl-3 col-lg-4 col-md-5 col-12">
                <a title="{{ $post->title }}" href="{{ post($post->id)->url }}">
                    @if ($post->image)<img src="{{ thumb($post->image) }}" class="img-fluid @if ($block_extra['img-shaddow'] ?? null) img-shaddow @endif mb-3" alt="{{ $post->title }}">@endif
                </a>
            </div>
        @endif

        <div class="@if ($block_extra['show_image'] ?? null) col-xl-9 col-lg-8 col-md-7 col-12 @else col-12 @endif">
            <div class="title" style="font-size: {{ $block_extra['titles_font_size'] ?? '1em' }}">
                <a href="{{ post($post->id)->url }}">{{ $post->title }}</a>
            </div>

            @if ($block_extra['show_summary'] ?? null)
                <div class="summary">
                    {{ $post->summary }}
                </div>
            @endif

            <div class="meta light">
                @if (($block_extra['show_author'] ?? null) != 'no')
                    @if (($block_extra['show_author'] ?? null) == 'name_avatar')
                        @if ($post->author_avatar) <img src="{{ thumb($post->author_avatar) }}" alt="{{ $post->author_name }}" class="img-fluid rounded-circle float-start me-2">@endif
                    @endif
                    <a href="{{ profile_url($post->user_id) }}"> {{ $post->author_name }}</a>
                    <span class="me-2"></span>
                @endif

                @if (($block_extra['show_date'] ?? null) != 'no')
                    <i class="bi bi-calendar"></i>
                    @if (($block_extra['show_date'] ?? null) == 'date'){{ date_locale($post->created_at, 'datetime') }} @endif
                    @if (($block_extra['show_date'] ?? null) == 'datetime'){{ date_locale($post->created_at, 'datetime') }} @endif
                    <span class="me-2"></span>
                @endif

                @if (($block_extra['show_comments_count'] ?? null) == 1)
                    <i class="bi bi-chat"></i> {{ $post->count_comments }} {{ __('comments') }}
                    <span class="me-2"></span>
                @endif

                @if ($block_extra['show_time_read'] ?? null)
                    <i class="bi bi-clock"></i> {{ $post->minutes_to_read }} {{ __('minutes read') }}
                @endif
            </div>
        </div>
    @endforeach

@endif


@if (($block_extra['style'] ?? null) == 'columns')

    @foreach (posts() as $post)
        <div class="{{ $class ?? 'col-12' }}">
            <div class="listing-box mb-4">
                @if ($block_extra['show_image'] ?? null)
                    <a title="{{ $post->title }}" href="{{ post($post->id)->url }}">
                        @if ($post->image)<img src="{{ thumb($post->image) }}" class="img-fluid @if ($block_extra['img-shaddow'] ?? null) img-shaddow @endif mb-3" alt="{{ $post->title }}">@endif
                    </a>
                @endif

                <div class="title">
                    <a href="{{ post($post->id)->url }}">{{ $post->title }}</a>
                </div>

                @if ($block_extra['show_summary'] ?? null)
                    <div class="summary">
                        {{ $post->summary }}
                    </div>
                @endif

                <div class="meta light">
                    @if (($block_extra['show_author'] ?? null) != 'no')
                        @if (($block_extra['show_author'] ?? null) == 'name_avatar')
                            @if ($post->author_avatar) <img src="{{ thumb($post->author_avatar) }}" alt="{{ $post->author_name }}" class="img-fluid rounded-circle float-start me-2">@endif
                        @endif
                        <a href="{{ profile_url($post->user_id) }}"> {{ $post->author_name }}</a>
                        <span class="me-2"></span>
                    @endif

                    @if (($block_extra['show_date'] ?? null) != 'no')
                        <i class="bi bi-calendar"></i>
                        @if ($block_extra['show_date'] == 'date') {{ date_locale($post->created_at) }} @endif
                        @if ($block_extra['show_date'] == 'datetime') {{ date_locale($post->created_at, 'datetime') }} @endif
                        <span class="me-2"></span>
                    @endif

                    @if (($block_extra['show_comments_count'] ?? null) == 1)
                        <i class="bi bi-chat"></i> {{ $post->count_comments }} {{ __('comments') }}
                        <span class="me-2"></span>
                    @endif

                    @if ($block_extra['show_time_read'] ?? null)
                        <i class="bi bi-clock"></i> {{ $post->minutes_to_read }} {{ __('minutes read') }}
                    @endif
                </div>
            </div>
        </div>
    @endforeach

@endif
