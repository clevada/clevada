<div class="row">

    @for ($footer_col = 1; $footer_col <= 2; $footer_col++)
        <div class="col-md-6 col-12">
            @foreach (footer_blocks($footer, $footer_col) as $block)
                @php
                    $block_extra = unserialize($block->block_extra);
                @endphp

                <div class="section" id="footer-block-{{ $block->id }}">
                    @switch($block->type)
                        @case('custom')
                            @include("{$template_view}.blocks.footer.custom")
                        @break
                        @case('editor')
                            @include("{$template_view}.blocks.footer.editor")
                        @break
                        @case('forum')
                            @include("{$template_view}.blocks.footer.forum")
                        @break
                        @case('image')
                            @include("{$template_view}.blocks.footer.image")
                        @break
                        @case('links')
                            @include("{$template_view}.blocks.footer.links")
                        @break
                        @case('map')
                            @include("{$template_view}.blocks.footer.map")
                        @break
                        @case('posts')
                            @include("{$template_view}.blocks.footer.posts")
                        @break
                        @case('search')
                            @include("{$template_view}.blocks.footer.search")
                        @break
                    @endswitch
                </div>
            @endforeach
        </div>
    @endfor

</div>
