@php
$block_data = footer_block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_header = unserialize($block_data->header ?? null);
    @endphp

    <div class="container-xxl">
        <div class="py-4">

            @if ($block_header['add_header'] ?? null)
                @if ($block_header['title'] ?? null)
                    <div class="footer-heading-title">
                        {!! $block_header['title'] ?? null !!}
                    </div>
                @endif

                @if ($block_header['content'] ?? null)
                    <div class="footer-heading-description">
                        {!! $block_header['content'] ?? null !!}
                    </div>
                @endif
            @endif

            {!! $block_data->content !!}
        </div>
    </div>
@endif
