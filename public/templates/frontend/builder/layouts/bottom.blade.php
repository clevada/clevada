@if ($bottom_section_id ?? null)
    @foreach (global_blocks($bottom_section_id) as $block)
        @php
            $block_extra = unserialize($block->block_extra);
        @endphp

        <div class="section" id="{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
            @include("{$template_view}.includes.blocks-switch")
        </div>
    @endforeach
@endif
