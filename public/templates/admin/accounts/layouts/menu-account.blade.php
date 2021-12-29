<nav class="nav nav-tabs" id="myTab" role="tablist">
    @if(check_access('accounts'))
    <a class="nav-item nav-link @if ($menu_section=='details') active @endif" href="{{ route('admin.accounts.show', ['id' => $account->id]) }}"><i class="far fa-user" aria-hidden="true"></i> {{ __('Details') }}</a>
    @endif

    @if(check_access('accounts'))
    <a class="nav-item nav-link @if ($menu_section=='tags') active @endif" href="{{ route('admin.account.tags', ['id' => $account->id]) }}"><i class="fas fa-tags" aria-hidden="true"></i> {{ __('Tags') }}</a>    
    @endif

    @if($is_user)
    @if(check_access('accounts'))
    <a class="nav-item nav-link @if ($menu_section=='notes') active @endif" href="{{ route('admin.account.notes', ['id' => $account->id]) }}"><i class="fas fa-exclamation-circle" aria-hidden="true"></i> {{ __('Internal notes') }}</a>     
    @endif
    @endif   

    @if($is_internal && logged_user()->role == 'admin')
    <a target="_blank" class="nav-item nav-link" href="{{ route('admin.accounts.permissions', ['search_user_id' => $account->id]) }}"><i class="fas fa-user-cog" aria-hidden="true"></i> {{ __('Permissions') }}</a>
    @endif
</nav>