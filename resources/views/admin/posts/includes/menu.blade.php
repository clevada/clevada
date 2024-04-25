<nav class="nav nav-tabs" id="myTab" role="tablist">
    @can('view', App\Models\Post::class)
        <a class="nav-item nav-link @if (($menu_section ?? null) == 'posts') active @endif" href="{{ route('admin.posts.index', ['post_type' => $post_type ?? null]) }}"><i class="bi bi-card-text"></i> {{ __('Posts') }}</a>
    @endcan

    @foreach ($taxonomies as $nav_taxonomy)
        <a class="nav-item nav-link @if (($menu_section ?? null) == $nav_taxonomy->type) active @endif" href="{{ route('admin.taxonomies.index', ['type' => $nav_taxonomy->type, 'post_type' => $nav_taxonomy->post_type]) }}"><i
                class="bi bi-card-text"></i>
            {{ __(json_decode($nav_taxonomy->labels)->plural ?? 'Allsss ' . $nav_taxonomy->name) }}</a>
    @endforeach

    {{--
    @if (Auth::user()->role == 'admin')
        <a class="nav-item nav-link @if (($menu_section ?? null) == 'config') active @endif" href="{{ route('admin.posts.config') }}"><i class="bi bi-gear"></i> {{ __('Settings') }}</a>
    @endif
    --}}
</nav>
