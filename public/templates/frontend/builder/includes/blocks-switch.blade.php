@switch($block->type)
    @case('accordion')
        @include("{$template_view}.blocks.accordion")
    @break

    @case('ads')
        @include("{$template_view}.blocks.ads")
    @break

    @case('alert')
        @include("{$template_view}.blocks.alert")
    @break

    @case('blockquote')
        @include("{$template_view}.blocks.blockquote")
    @break

    @case('custom')
        @include("{$template_view}.blocks.custom")
    @break

    @case('download')
        @include("{$template_view}.blocks.download")
    @break

    @case('editor')
        @include("{$template_view}.blocks.editor")
    @break

    @case('form')
        @include("{$template_view}.blocks.form")
    @break

    @case('gallery')
        @include("{$template_view}.blocks.gallery")
    @break

    @case('hero')
        @include("{$template_view}.blocks.hero")
    @break

    @case('image')
        @include("{$template_view}.blocks.image")
    @break

    @case('include')       
    @if($block_extra['file'] ?? null) 
    @php
    $include_file = str_replace('.blade.php', '', $block_extra['file']);
    @endphp
    @include("custom-files.{$include_file}")@endif
    @break

    @case('links')
        @include("{$template_view}.blocks.links")
    @break

    @case('map')
        @include("{$template_view}.blocks.map")
    @break    

    @case('posts')
        @include("{$template_view}.blocks.posts")
    @break

    @case('slider')
        @include("{$template_view}.blocks.slider")
    @break

    @case('spacer')
        @include("{$template_view}.blocks.spacer")
    @break

    @case('video')
        @include("{$template_view}.blocks.video")
    @break
 
@endswitch
