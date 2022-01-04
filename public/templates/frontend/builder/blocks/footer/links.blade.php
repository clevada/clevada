@php
$block_data = footer_block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_items = unserialize($block_data->content);
        $block_header = unserialize($block_data->header ?? null);
    @endphp

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

        @if (count($block_items) > 0)

            @if (($block_extra['display_style'] ?? null) != 'multiple') <ul>@endif

            @foreach ($block_items as $item)

                @if (($block_extra['display_style'] ?? null) != 'multiple')
                    <li class="mb-2">@if ($item['icon']) {!! $item['icon'] !!} @endif <a href="{{ $item['url'] }}" title="{{ $item['title'] }}">{{ $item['title'] }}</a> </li>

                @else
                    @if ($item['icon']) {!! $item['icon'] !!} @endif <a href="{{ $item['url'] }}" title="{{ $item['title'] }}">{{ $item['title'] }}</a><span class="me-3"></span>
                @endif

            @endforeach

        @endif

    </div>
@endif
