<?php header('Access-Control-Allow-Origin: *'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>    

<!-- Trumbowyg editor -->
<script src="{{ asset('assets/vendors/trumbowyg/trumbowyg.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/ui/trumbowyg.min.css') }}">

<!-- Trumbowyg plugins -->
<script src="{{ asset('assets/vendors/trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js') }}"></script>
<script src="{{ asset('assets/vendors/trumbowyg/plugins/allowtagsfrompaste/trumbowyg.allowtagsfrompaste.min.js') }}"></script>

<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/plugins/colors/ui/trumbowyg.colors.min.css') }}">
<script src="{{ asset('assets/vendors/trumbowyg/plugins/colors/trumbowyg.colors.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/plugins/table/ui/trumbowyg.table.min.css') }}">
<script src="{{ asset('assets/vendors/trumbowyg/plugins/table/trumbowyg.table.min.js') }}"></script>
<script src="{{ asset('assets/vendors/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') }}"></script>

<script>
$(document).ready(function() {
    'use strict';
    $('.trumbowyg').trumbowyg({
        btns: [
                    ['p', 'blockquote', 'strong', 'em', 'highlight'],
                    ['link', 'noembed'],
                    ['unorderedList', 'orderedList', 'horizontalRule', 'removeformat'],
                ],        
        autogrow: true,
        plugins: {
            allowTagsFromPaste: {
                allowedTags: ['h1', 'h2', 'h3', 'h4', 'p', 'br', 'strong', 'b', 'i', 'a', 'ul', 'li', 'blockquote', 'table', 'td', 'tr', 'thead', 'tfoot']
            },
            table: { styler:'table-responsive' },            
        }
    });
});
</script>