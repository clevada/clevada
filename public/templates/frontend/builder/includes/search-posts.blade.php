<section class="bar bg-search no-mb">
    <div class="col-md-4 offset-md-4">
        <form methpd="get" action="{{ posts_search_url() }}">
            <input type="text" class="form-control main-search" name="s" required placeholder="{{ __('Search in posts') }}">
        </form>
    </div>
</section>