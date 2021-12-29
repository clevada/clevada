<?php header('Access-Control-Allow-Origin: *'); ?>

<!-- Trumbowyg editor -->
<script src="{{ asset('assets/vendors/trumbowyg/trumbowyg.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/ui/trumbowyg.min.css') }}">

<!-- Trumbowyg plugins -->
<script src="{{ asset('assets/vendors/trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js') }}"></script>
<script src="{{ asset('assets/vendors/trumbowyg/plugins/allowtagsfrompaste/trumbowyg.allowtagsfrompaste.min.js') }}"></script>

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