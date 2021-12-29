@foreach (footer_blocks($footer, 1) as $block)

    @php 
    $block_extra = unserialize($block->block_extra);
    @endphp 

    <div class="section" id="{{ $block->id }}">      
        @switch($block->type)            
            @case('ads')            
                @include("{$template_view}.blocks.footer.ads")
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
            @case('alert')
                @include("{$template_view}.blocks.footer.alert")                    
            @break
            @case('video')
                @include("{$template_view}.blocks.footer.video")                    
            @break
        @endswitch

    </div>
@endforeach
