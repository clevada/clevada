<tr @if ($categ->active != 1) class="table-warning" @endif>
    <td>
        @if ($categ->active != 1)
            <span class="float-end ms-2"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Inactive') }}</button></span>
        @endif

        @if($loop->depth == 1)
            <div class="listing fw-bold">@for ($i = 1; $i < $loop->depth; $i++)---@endfor {!! $categ->icon ?? null !!} {{ $categ->title }}</div>
        @else
        <div class="listing">@for ($i = 1; $i < $loop->depth; $i++)---@endfor {!! $categ->icon ?? null !!} {{ $categ->title }}</div>
        @endif
        <div class="text-muted small">
            <b>ID</b> {{ $categ->id }} | <b>{{ __('Position') }}:</b> {{ $categ->position }}
            @if ($categ->description)<br>{{ $categ->description }}@endif
            @if ($categ->badges)<br><b>{{ __('Badges') }}</b> {{ $categ->badges }}@endif        

            <div class="clearfix"></div>

            <b>{{ __('Sidebar') }}</b>:
            @if (!$categ->sidebar_id) {{ __('No sidebar') }} @else
                <a target="_blank" href="{{ route('admin.template.sidebars.show', ['id' => $categ->sidebar_id]) }}">
                    @if ($categ->sidebar_position == 'right') {{ $categ->sidebar_label }} ({{ __('right') }}) @endif
                    @if ($categ->sidebar_position == 'left')  {{ $categ->sidebar_label }} ({{ __('left') }})@endif
                </a>
            @endif

            <div class="clearfix"></div>

            <b>{{ __('Top section') }}</b>:
            @if (!$categ->top_section_id) {{ __('No content') }} @else
                <a target="_blank" href="{{ route('admin.template.global_sections.show', ['id' => $categ->top_section_id]) }}">
                    {{ $categ->top_section_label }}
                </a>
            @endif

            <div class="clearfix"></div>

            <b>{{ __('Bottom section') }}</b>:
            @if (!$categ->bottom_section_id) {{ __('No content') }} @else
                <a target="_blank" href="{{ route('admin.template.global_sections.show', ['id' => $categ->bottom_section_id]) }}">
                    {{ $categ->bottom_section_label }}
                </a>
            @endif
        </div>

    </td>

    <td>
        <a href="{{ route('admin.posts', ['search_categ_id' => $categ->id]) }}">{{ $categ->count_tree_items ?? 0 }} {{ __('posts') }}</a>
    </td>

    @if (count(sys_langs()) > 1)
        <td>
            {!! flag($categ->lang_code) !!} {{ $categ->lang_name ?? __('No language') }}           
        </td>
    @endif

    <td>
        <div class="d-grid gap-2">
            <button data-bs-toggle="modal" data-bs-target="#update-categ-{{ $categ->id }}" class="btn btn-primary btn-sm mb-2">{{ __('Update category') }}</button>
            @include('admin.posts.modals.update-categ')

            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $categ->id }}" class="btn btn-danger btn-sm">{{ __('Delete category') }}</a>
            <div class="modal fade confirm-{{ $categ->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ __('Are you sure you want to delete this category? All posts from this category will be deleted.') }}
                        </div>
                        <div class="modal-footer">
                            <form method="POST" action="{{ route('admin.posts.categ.show', ['id' => $categ->id]) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>

@if (count($categ->children) > 0)

    @foreach ($categ->children as $categ)
        @include('admin.posts.loops.categories-loop', $categ)
    @endforeach

@endif
