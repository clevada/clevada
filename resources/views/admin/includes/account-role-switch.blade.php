@switch($role)
    @case('admin')
        {{ __('Administrator') }}
    @break

    @case('manager')
        {{ __('Manager') }}
    @break

    @case('editor')
        {{ __('Editor') }}
    @break

    @case('author')
        {{ __('Author') }}
    @break

    @case('contributor')
        {{ __('Contributor') }}
    @break

    @case('user')
        {{ __('Registered user') }}
    @break

    @case('developer')
        {{ __('Developer') }}
    @break

    @default
        {{ $role }}
@endswitch
