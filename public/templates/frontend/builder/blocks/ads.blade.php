@php
$block_data = block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_header = unserialize($block_data->header ?? null);
    @endphp

    <div class="container-xxl">

        <div class="block-ads">

            @if ($block_header['add_header'] ?? null)
                <div class="block-ads-header">
                    @if ($block_header['title'] ?? null)
                        <div class="block-ads-header-title">
                            {{ $block_header['title'] ?? null }}
                        </div>
                    @endif

                    @if ($block_header['content'] ?? null)
                        <div class="block-ads-header-content">
                            {!! $block_header['content'] ?? null !!}
                        </div>
                    @endif
                </div>
            @endif
            
            {!! $block_data->content !!}

        </div>
    </div>
@endif
