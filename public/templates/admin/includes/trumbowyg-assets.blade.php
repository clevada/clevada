<?php header('Access-Control-Allow-Origin: *'); ?>

<style>
    .trumbowyg-button-pane button.trumbowyg-disable, .trumbowyg-button-pane.trumbowyg-disable button:not(.trumbowyg-not-disable):not(.trumbowyg-active), .trumbowyg-disabled .trumbowyg-button-pane button:not(.trumbowyg-not-disable):not(.trumbowyg-viewHTML-button) {
    opacity: 1 !important;
    }
</style>

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
<script src="{{ asset('assets/vendors/trumbowyg/plugins/upload/trumbowyg.upload.js') }}"></script>
<script src="{{ asset('assets/vendors/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') }}"></script>

<!-- Import prismjs stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/plugins/highlight/ui/trumbowyg.highlight.min.css') }}">
<link rel="stylesheet" href='{{ asset("assets/vendors/prism/prism.css") }}' />

<!-- Import prismjs -->
<script src="{{ asset("assets/vendors/prism/prism.js") }}"></script>
<!-- Import prismjs line highlight plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/plugins/line-highlight/prism-line-highlight.min.js"></script>
<script src="{{ asset('assets/vendors/trumbowyg/plugins/highlight/trumbowyg.highlight.min.js') }}"></script>


<script>
    $(document).ready(function() {
        'use strict';
        $('.trumbowyg').trumbowyg({
            btns: [
                ['formatting', 'strong', 'em', 'link', 'upload'],
                ['foreColor', 'backColor', 'table', 'horizontalRule', 'preformatted', 'highlight'],
                ['unorderedList', 'orderedList', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', 'undo', 'redo'],
                ['removeformat', 'viewHTML', 'fullscreen']
            ],
            autogrow: true,
            plugins: {
                allowTagsFromPaste: {
                    allowedTags: ['h1', 'h2', 'h3', 'h4', 'p', 'br', 'strong', 'b', 'i', 'a', 'ul', 'li', 'blockquote', 'table', 'td', 'tr', 'thead', 'tfoot']
                },
                table: {
                    styler: 'table-responsive'
                },
                upload: {
                    serverPath: "{{ route('admin.ajax.editor-upload') }}",
                    data: [{
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }],
                    imageWidthModalEdit: true,
                }
            }
        });
    });
</script>
