<div class="container-xxl">
    <div class="row">

        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12">
            @foreach (content_blocks('sidebar', $sidebar_id) as $block)
                @php
                    $block_extra = unserialize($block->block_extra);
                @endphp
                <div class="section" id="{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
                    @include("{$template_view}.includes.blocks-switch")
                </div>
            @endforeach
        </div>

        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
            @foreach (content_blocks($module, $content_id) as $block)
                @php
                    $block_extra = unserialize($block->block_extra);
                @endphp
                <div class="section" id="{{ $block->id }}" @if ($block_extra['bg_color'] ?? null) style="background-color: {{ $block_extra['bg_color'] }}" @endif>
                    @include("{$template_view}.includes.blocks-switch")
                </div>
            @endforeach
        </div>

    </div>
</div>
