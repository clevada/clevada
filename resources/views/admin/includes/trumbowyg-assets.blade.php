<?php header('Access-Control-Allow-Origin: *'); ?>

<style>
    .trumbowyg-button-pane button.trumbowyg-disable,
    .trumbowyg-button-pane.trumbowyg-disable button:not(.trumbowyg-not-disable):not(.trumbowyg-active),
    .trumbowyg-disabled .trumbowyg-button-pane button:not(.trumbowyg-not-disable):not(.trumbowyg-viewHTML-button) {
        opacity: 1 !important;
    }
</style>

<!-- Trumbowyg editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js" integrity="sha512-YJgZG+6o3xSc0k5wv774GS+W1gx0vuSI/kr0E0UylL/Qg/noNspPtYwHPN9q6n59CTR/uhgXfjDXLTRI+uIryg==" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" integrity="sha512-Fm8kRNVGCBZn0sPmwJbVXlqfJmPC13zRsMElZenX6v721g/H7OukJd8XzDEBRQ2FSATK8xNF9UYvzsCtUpfeJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Trumbowyg plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"
    integrity="sha512-UInqT8f+K1tkck6llPo0HDxlT/Zxv8t4OGeCuVfsIlXLrnP1ZKDGb+tBsBPMqDW15OcmV8NDfQe9+EaAG4aXeg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/allowtagsfrompaste/trumbowyg.allowtagsfrompaste.min.js"
    integrity="sha512-eN5NSu1g2mus/i1826c6tEsNVKsYc+TAs63EVtyFrnEZkdDFYRRCm5OYmuGPe9h3rezLSXJ2nDkT2i/+/TwqJw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/colors/ui/trumbowyg.colors.min.css"
    integrity="sha512-vw0LMar38zTSJghtmUo0uw000TBbzhsxLZkOgXZG+U4GYEQn+c+FmVf7glhSZUQydrim3pI+/m7sTxAsKhObFA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/colors/trumbowyg.colors.min.js" integrity="sha512-SHpxBJFbCaHlqGpH13FqtSA+QQkQfdgwtpmcWedAXFCDxAYMgrqj9wbVfwgp9+HgIT6TdozNh2UlyWaXRkiurw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/ui/trumbowyg.table.min.css"
    integrity="sha512-qIa+aUEbRGus5acWBO86jFYxOf4l/mfgb30hNmq+bS6rAqQhTRL5NSOmANU/z5RXc3NJ0aCBknZi6YqD0dqoNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/trumbowyg.table.min.js" integrity="sha512-StAj4jlQaB7+Ch81cZyms1l21bLyLjjI6YB2m2UP0cVv6ZEKs5egZYhLTNBU96SylBJEqBquyaAUfFhVUrX20Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/preformatted/trumbowyg.preformatted.min.js"
    integrity="sha512-jbGHfPlSvCf9wKx1/E61iNL+MbzEYB4PKwjlEWfZzHzfThYGqPtNdGNOu0NlxLoQdGt6Vq7PVQXJVtrtNXUy8w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!-- Import prismjs stylesheet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/highlight/ui/trumbowyg.highlight.min.css"
    integrity="sha512-OwPEnTSiICuaMJ06mtNPO0IS5xteioaN4aBI1B1TFehaTj3G20rxhiSBbAiAdd5Qk0HuvF7wA8SDFc0YxGoPSw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ config('app.cdn') }}/vendor/prism/prism.css" />

<!-- Import prismjs -->
<script src="{{ config('app.cdn') }}/vendor/prism/prism.js"></script>
<!-- Import prismjs line highlight plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/plugins/line-highlight/prism-line-highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/highlight/trumbowyg.highlight.min.js"
    integrity="sha512-3W+Ge6Xxy3oe3blsox6SH08GRYJLtZJFHAQnzIajINKo68NE4O8ni+qgdmJrMDIQ8x2+R6hfh1XZdHV+0czG3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
    $(document).ready(function() {
        'use strict';
        $('.trumbowyg').trumbowyg({
            btns: [
                ['formatting', 'strong', 'em', 'link'],
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
            }
        });
    });
</script>
