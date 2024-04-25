<nav class="nav nav-tabs" id="myTab" role="tablist">
    <a class="nav-item nav-link @if (($post_menu ?? null) == 'details') active @endif" href="{{ route('admin.posts.show', ['id' => $post->id]) }}"><i class="bi bi-pencil-square"></i> {{ __('Post details') }}</a>    
    <a class="nav-item nav-link @if (($post_menu ?? null) == 'content') active @endif" href="{{ route('admin.posts.content', ['id' => $post->id]) }}"><i class="bi bi-card-text"></i> {{ __('Post content') }}</a>  
    @if($post->cf_group_id ?? null)  
    <a class="nav-item nav-link @if (($post_menu ?? null) == 'cf') active @endif" href="{{ route('admin.posts.cf', ['id' => $post->id]) }}"><i class="bi bi-check2-circle"></i> {{ __('Custom fields') }}</a>    
    @endif
</nav>
