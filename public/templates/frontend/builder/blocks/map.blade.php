@php
$block_data = block($block->id);
@endphp

@if ($block_extra['address'] ?? null)
    @php
    $block_header = unserialize($block_data->header ?? null);
    @endphp 

    <div class="block-map">

        @if ($block_header['add_header'] ?? null)
            <div class="block-map-header">
                @if ($block_header['title'] ?? null)
                    <div class="block-map-header-title">
                        {{ $block_header['title'] ?? null }}
                    </div>
                @endif

                @if ($block_header['content'] ?? null)
                    <div class="block-map-header-content">
                        {!! $block_header['content'] ?? null !!}
                    </div>
                @endif
            </div>
        @endif

        <!-- Map Section -->
        <div class="maparea">
            <iframe style="width: 100%" height="{{ $block_extra['height'] }}"
                src="https://maps.google.com/maps?height=400&amp;hl=en&amp;q={{ $block_extra['address'] }}&amp;ie=UTF8&amp;t=&amp;z={{ $block_extra['zoom'] }}&amp;iwloc=B&amp;output=embed"></iframe>
        </div>
        <!-- End Map Section -->
    </div>
@endif
