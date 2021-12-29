@php
$block_data = block($block->id);
@endphp

@if ($block_data->content ?? null)
    <div class="container-xxl">
        <div class="block-editor">
            {!! $block_data->content !!}
        </div>
    </div>
@endif
