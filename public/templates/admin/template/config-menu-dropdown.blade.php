<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.template.menu') }}">{{ __('Website menu links') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Dropdown links') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 mb-3">
                    @include('admin.template.layouts.menu-template')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Dropdown links') }} - {{ get_menu_link_label($parent_link->id) }}</h4>
                </div>

                <div class="col-12 col-sm-7 col-md-6 order-md-2 order-last">
                    <div class="float-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-menu-link"><i class="bi bi-plus-circle"></i> {{ __('Add link') }}</button>
                        @include('admin.template.modals.create-menu-link')
                    </div>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'created') {{ __('Creates') }} @endif
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This menu exist') }} @endif
                    @if ($message == 'error_delete') {{ __('Error. This menu can not be deleted') }} @endif
                </div>
            @endif

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover sortable">
                    <thead>
                        <tr>
                            <th width="40"><i class="bi bi-arrow-down-up"></i></th>
                            <th>{{ __('Details') }}</th>
                            <th width="180">{{ __('Type') }}</th>
                            <th width="160">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody id="sortable">
                        @foreach ($links as $link)
                            <tr id="item-{{ $link->id }}">

                                <td class="movable">
                                    <i class="bi bi-arrow-down-up"></i>
                                </td>

                                <td>
                                    @foreach ($langs as $lang)
                                        <b>
                                            @if (!get_menu_link_label($link->id, $lang->id))<span class="text-danger">{{ __('Not set') }}</span>
                                            @else
                                                @if (count(sys_langs()) > 1){!! flag($lang->code) !!} @endif
                                                {{ get_menu_link_label($link->id, $lang->id) }}@endif
                                        </b>
                                        <br>

                                        @if ($link->type == 'homepage')
                                            <a target="_blank"
                                                href="{{ route('homepage', ['lang' => $lang->id == default_lang()->id ? null : $lang->code]) }}">{{ route('homepage', ['lang' => $lang->id == default_lang()->id ? null : $lang->code]) }}</a>

                                        @elseif($link->type == 'custom')
                                            <a target="_blank" href="{{ $link->value }}">{{ $link->value }}</a>

                                        @elseif($link->type == 'module')
                                            @php
                                                $permalinks = DB::table('sys_lang')
                                                    ->where('id', $lang->id)
                                                    ->value('permalinks');
                                                $permalinks = unserialize($permalinks);
                                                
                                                $this_lang = $lang->id == default_lang()->id ? null : '/' . lang($lang->id)->code;
                                                $link_url = route('homepage') . $this_lang . '/' . $permalinks[$link->value];
                                            @endphp
                                            <a target="_blank" href="{{ $link_url }}">{{ $link_url }}</a>

                                        @elseif($link->type == 'page' && $link->value)
                                            @php
                                                $link_url = page((int) $link->value, $lang->id)->url;
                                            @endphp
                                            <a target="_blank" href="{{ $link_url }}">{{ $link_url }}</a>
                                        @endif

                                        <div class="mb-2"></div>
                                    @endforeach
                                </td>

                                <td>
                                    @if ($link->type == 'homepage') {{ __('Homepage') }}
                                    @elseif($link->type == 'custom') {{ __('Custom link') }}
                                    @elseif($link->type == 'dropdown')                                                                           
                                        <a class="btn btn-primary mb-2" href="{{ route('admin.template.menu.dropdown', ['link_id' => $link->id]) }}">{{ __('Manage dropdown links') }}</a>                                

                                        {{ __('Dropdown submenu') }} 
                                    @elseif($link->type == 'module') {{ __('Module') }}
                                    @elseif($link->type == 'page') {{ __('Page') }}
                                    @endif
                                </td>
                               
                                <td>
                                    <div class="d-grid gap-2">

                                        <button data-bs-toggle="modal" data-bs-target="#update-menu-link-dropdown-{{ $link->id }}" class="btn btn-primary btn-sm mb-2">{{ __('Update link') }}</button>
                                        @include('admin.template.modals.update-menu-link-dropdown')

                                        @if (check_access('developer'))
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $link->id }}" class="btn btn-danger btn-sm">{{ __('Delete link') }}</a>
                                            <div class="modal fade confirm-{{ $link->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('Are you sure you want to delete this link?') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="POST" action="{{ route('admin.template.menu.dropdown', ['id' => $link->id, 'parent_id' => $parent_link->id ?? null]) }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                                <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
        <!-- end card-body -->

    </div>

</section>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#sortable").sortable({
            revert: true,
            axis: 'y',
            opacity: 0.5,
            revert: true,
            handle: ".movable",

            update: function(event, ui) {
                var data = $(this).sortable('serialize');
                // POST to server using $.post or $.ajax
                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{{ route('admin.template.menu.sortable_dropdowns', ['parent_link_id' => $parent_link->id]) }}',
                });
            }
        });

        $("ul, li, .actions").disableSelection();
    });
</script>
