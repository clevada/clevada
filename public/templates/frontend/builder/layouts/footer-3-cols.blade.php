<div class="row">

    @for ($footer_col = 1; $footer_col <= 3; $footer_col++)
        <div class="col-md-4 col-12">
            @foreach (footer_blocks($footer, $footer_col) as $block)
                @php
                    $block_extra = unserialize($block->block_extra);
                @endphp

                <div class="section" id="{{ $block->id }}">
                    @switch($block->type)
                        @case('ads')
                            @include("{$template_view}.blocks.footer.ads")
                        @break
                        @case('alert')
                            @include("{$template_view}.blocks.footer.alert")
                        @break
                        @case('editor')
                            @include("{$template_view}.blocks.footer.editor")
                        @break
                        @case('image')
                            @include("{$template_view}.blocks.footer.image")
                        @break
                        @case('gallery')
                            @include("{$template_view}.blocks.footer.gallery")
                        @break
                        @case('links')
                            @include("{$template_view}.blocks.footer.links")
                        @break
                        @case('video')
                            @include("{$template_view}.blocks.footer.video")
                        @break
                    @endswitch

                </div>
            @endforeach
        </div>
    @endfor

</div>
