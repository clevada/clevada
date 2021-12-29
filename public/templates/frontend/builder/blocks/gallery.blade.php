@php
$block_data = block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_items = unserialize($block_data->content);
        $block_header = unserialize($block_data->header ?? null);
        
        if (!$block_extra['cols'] ?? null) {
            $cols = 4;
        } else {
            $cols = $block_extra['cols'];
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
        if ($cols == 6) {
            $class = 'col-xl-2 col-lg-2 col-md-4 col-sm-6 col-12';
        }
        
    @endphp

    <div class="container-xxl">
        <div class="block-gallery">
            <div class="row">

                @if ($block_header['add_header'] ?? null)
                    <div class="block-gallery-header">
                        @if ($block_header['title'] ?? null)
                            <div class="block-gallery-header-title">
                                {{ $block_header['title'] ?? null }}
                            </div>
                        @endif

                        @if ($block_header['content'] ?? null)
                            <div class="block-gallery-header-content">
                                {!! $block_header['content'] ?? null !!}
                            </div>
                        @endif
                    </div>
                @endif

                @if (count($block_items) > 0)
                    @foreach ($block_items as $item)

                        @if ($item['image'])

                            <div class="{{ $class }} mb-2">
                                <div class="block-gallery-image-box">

                                    @if ($item['url'])
                                        <a href="{{ $item['url'] }}"><img class="img-fluid @if ($block_extra['shaddow'] ?? null) img-shaddow @endif" alt="{{ $item['title'] ?? $item['image'] }}"
                                                title="{{ $item['title'] ?? $item['image'] }}" src="{{ thumb($item['image']) }}"></a>
                                    @else
                                        <a data-fancybox="gallery-{{ $block->id }}" class="gallery" href="{{ image($item['image']) }}"><img class="img-fluid @if ($block_extra['shaddow'] ?? null) img-shaddow @endif"
                                                alt="{{ $item['title'] ?? $item['image'] }}" title="{{ $item['title'] ?? $item['image'] }}" src="{{ thumb($item['image']) }}"></a>
                                    @endif
                                </div>

                                @if ($item['caption'] ?? null)<div class="block-gallery-caption">{{ $item['caption'] }}</div>@endif
                            </div>

                        @endif

                    @endforeach
                @endif

            </div>
        </div>
    </div>
@endif
