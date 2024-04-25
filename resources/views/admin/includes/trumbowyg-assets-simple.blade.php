<?php header('Access-Control-Allow-Origin: *'); ?>

<!-- Trumbowyg editor -->
<script src="{{ config('app.cdn') }}/vendor/trumbowyg/trumbowyg.min.js"></script>
<link rel="stylesheet" href="{{ config('app.cdn') }}/vendor/trumbowyg/ui/trumbowyg.min.css">

<!-- Trumbowyg plugins -->
<script src="{{ config('app.cdn') }}/vendor/trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>
<script src="{{ config('app.cdn') }}/vendor/trumbowyg/plugins/allowtagsfrompaste/trumbowyg.allowtagsfrompaste.min.js"></script>

<script>
$(document).ready(function() {
    'use strict';
    $('.trumbowyg').trumbowyg({
        btns: [['formatting', 'strong', 'em', 'link'], ['unorderedList', 'orderedList'], ['removeformat','viewHTML', 'fullscreen']],
        autogrow: true,
        plugins: {
            allowTagsFromPaste: {
                allowedTags: ['h1', 'h2', 'h3', 'h4', 'p', 'br', 'strong', 'b', 'i', 'a', 'ul', 'li']
            },            
            
        }
    });
});
</script>