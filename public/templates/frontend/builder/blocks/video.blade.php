@php
$block_data = block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_item = unserialize($block_data->content);
        $block_header = unserialize($block_data->header ?? null);
    @endphp

    <div class="container-xxl">

        <div class="block-video">

            @if ($block_header['add_header'] ?? null)
                <div class="block-image-header">
                    @if ($block_header['title'] ?? null)
                        <div class="block-image-header-title">
                            {{ $block_header['title'] ?? null }}
                        </div>
                    @endif

                    @if ($block_header['content'] ?? null)
                        <div class="block-image-header-content">
                            {!! $block_header['content'] ?? null !!}
                        </div>
                    @endif
                </div>
            @endif

            @if ($block_item['embed'] ?? null)
                <div @if($block_extra['full_width_responsive'] ?? null) class="ratio ratio-16x9" @endif>
                    {!! $block_item['embed'] !!}
                </div>
                @if ($block_item['caption'] ?? null)<div class="block-video-caption">{{ $block_item['caption'] }}</div>@endif
            @endif
        </div>
    </div>
@endif
