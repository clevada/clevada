<!-- search autocomplete -->
<script>
    function search_docs_suggest(inputString) {
        if (inputString.length == 0) {
            $('#search-suggestions').fadeOut(); // Hide the suggestions box
        } else {
            $.get("/search-docs-autocomplete", {
                s: "" + inputString + "", lang_id: "" + {{ active_lang()->id }} + ""
            }, function(data) { // Do an AJAX call
                $('#search-suggestions').fadeIn(); // Show the suggestions box
                $('#search-suggestions').html(data); // Fill the suggestions box
            });
        }
    }  
</script>

<div class="docs-top-bar" style="background-color: {{ template('docs_search_bar_bg_color') ?? '#f9f3e8' }}; color: {{ template('docs_search_bar_font_color') ?? '#3e3e3e' }}; @if (template('docs_search_bar_use_cover') ?? null) background-image: @if (template('docs_search_bar_cover_dark') ?? null) linear-gradient(rgb(0 0 0 / 50%), rgb(0 0 0 / 50%)), @endif url('{{ str_replace('\\', '/', image(template('docs_search_bar_cover_img'))) }}'); background-size: cover; @endif">
    <div class="container-xxl">

        @if(($is_docs_home ?? null) == 1)
        <div class="title" style="font-size: {{ template('search_bar_title_size') ?? '2.2em' }}; color: {{ template('docs_search_bar_font_color') ?? '#3e3e3e' }}">
            {{ __('Clevada Help Center') }}
        </div>

        <div class="subtitle" style="font-size: {{ template('search_bar_subtitle_size') ?? '1.1em' }}; color: {{ template('docs_search_bar_font_color') ?? '#3e3e3e' }}">
            {{ __('Looking for help? Ask questions. Browse articles. Find answers to your questions.') }}
        </div>
        @endif

        <div class="col-md-6 offset-md-3">

            <form methpd="get" action="{{ search_docs_url() }}">
                <div class="input-group">
                    <input type="search" class="form-control docs-search" placeholder="{{ __('Search the knowledge base') }}" aria-label="{{ __('Search the knowledge base') }}"
                        aria-describedby="docs-search-addon" @if(!(template('docs_disable_search_autocomplete') ?? null)) onkeyup="search_docs_suggest(this.value);" @endif autocomplete="off" name="s">
                    <span class="input-group-text" id="docs-search-addon"><i class="bi bi-search"></i></span>
                </div>
                <div id="search-suggestions"></div>
            </form>
        </div>
    </div>
</div>
