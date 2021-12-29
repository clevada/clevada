@php
$block_data = block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_items = unserialize($block_data->content ?? null);
        $block_header = unserialize($block_data->header ?? null);
    @endphp

    <div class="container-xxl">
        <div class="block-accordion">

            @if ($block_header['add_header'] ?? null)
                <div class="block-accordion-header">
                    @if ($block_header['title'] ?? null)
                        <div class="block-accordion-header-title">
                            {{ $block_header['title'] ?? null }}
                        </div>
                    @endif

                    @if ($block_header['content'] ?? null)
                        <div class="block-accordion-header-content">
                            {!! $block_header['content'] ?? null !!}
                        </div>
                    @endif
                </div>
            @endif

            <div class="accordion" id="accordion_{{ $block->id }}">
                @foreach ($block_items as $block_item)
                    <div class="accordion-item">
                        <div class="accordion-header" id="heading_{{ $loop->index }}">
                            <button class="accordion-button collapsed block-accordion-title" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $loop->index }}" aria-expanded="@if ($loop->index == 0) true @else false @endif"
                                aria-controls="collapse_{{ $loop->index }}" style="color: {{ $block_extra['title_color'] ?? null }}; background-color: {{ $block_extra['title_bg_color'] ?? null }}">
                                <b>{{ $block_item['title'] }}</b>
                            </button>
                        </div>

                        <div id="collapse_{{ $loop->index }}" class="accordion-collapse collapse @if ($loop->index == 0 && ($block_extra['collapse_first_item'] ?? null)) show @endif" aria-labelledby="heading_{{ $loop->index }}"
                            data-bs-parent="#accordion_{{ $block->id }}">
                            <div class="accordion-body block-accordion-content">
                                {!! $block_item['content'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
@endif
