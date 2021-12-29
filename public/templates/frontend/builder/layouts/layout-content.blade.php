@foreach (content_blocks($module, $content_id) as $block)
    @php
        $block_extra = unserialize($block->block_extra);
    @endphp

    <div class="section" id="block-{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
        @include("{$template_view}.includes.blocks-switch")
    </div>
@endforeach
