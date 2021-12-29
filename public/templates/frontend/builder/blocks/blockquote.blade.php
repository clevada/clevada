@php
$block_data = block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $item = unserialize($block_data->content);
    @endphp

    <div class="container-xxl">
        <div class="block-blockquote">
            <blockquote @if ($block_extra['shaddow'] ?? null) class="img-shaddow" @endif>
                <div class="block-blockquote-content">{!! $item['content'] !!}</div>

                @if ($item['source'] ?? null)
                    <div class="block-blockquote-source">
                        @if ($block_extra['avatar'] ?? null)<img class="block-blockquote-avatar img-fluid" alt="{{ $item['source'] }}" src="{{ image($block_extra['avatar']) }}">@endif
                        {!! $item['source'] !!}
                    </div>
                @endif
            </blockquote>
        </div>
    </div>
@endif
