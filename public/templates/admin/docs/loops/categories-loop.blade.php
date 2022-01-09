<tr @if ($categ->active != 1) class="table-warning" @endif>
    <td>
        @if ($categ->active != 1) <span class="pull-right ml-2"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Inactive') }}</button></span> @endif
        <h6 @if (!$categ->parent_id) class="fw-bold" @endif>@for ($i = 1; $i < $loop->depth; $i++)---@endfor {!! $categ->icon ?? null !!} {{ $categ->label }}</h6>
            <div class="text-muted small">
                ID: {{ $categ->id }} | {{ __('Position') }}: {{ $categ->position }}
            </div>
    </td>

    <td>
        @if ($categ->active == 0)
            <span class="float-end ms-2"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Inactive') }}</button></span>
        @endif

        @foreach (docs_categ_contents($categ->id) as $content)

            @if (count(sys_langs()) > 1)
                {!! flag($content->lang_code) !!}
            @endif

            @if ($categ->active == 1)<a target="_blank" href="{{ docs_categ($categ->id, $content->lang_id)->url }}"><b>{{ $content->title }}</b></a>
            @else <b>{{ $content->title }}</b>@endif

            {{-- <br>
            {{ __('Description') }}: @if ($content->description) {{ $content->description }} @else <span class="text-danger">{{ __('not set') }}</span>@endif<br>
            {{ __('Meta title') }}:  @if ($content->meta_title) {{ $content->meta_title }} @else <span class="text-danger">{{ __('not set') }}</span>@endif<br>
            {{ __('Meta description') }}: @if ($content->meta_description) {{ $content->meta_description }} @else <span class="text-danger">{{ __('not set') }}</span>@endif<br> --}}
            <div class="mb-1"></div>

        @endforeach
    </td>

    <td>
        <h5><a href="{{ route('admin.docs', ['search_categ_id' => $categ->id]) }}">{{ $categ->count_tree_items ?? 0 }} {{ __('articles') }}</a></h5>
    </td>

    <td>
        <div class="d-grid gap-2">
            <a href="{{ route('admin.docs.categ.show', ['id' => $categ->id]) }}" class="btn btn-primary btn-sm mb-2">{{ __('Update category') }}</a>

            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $categ->id }}" class="btn btn-danger btn-sm">{{ __('Delete category') }}</a>
            <div class="modal fade confirm-{{ $categ->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ __('Are you sure you want to delete this category?') }}
                        </div>
                        <div class="modal-footer">
                            <form method="POST" action="{{ route('admin.docs.categ.show', ['id' => $categ->id]) }}">
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
        @include('admin.docs.loops.categories-loop', $categ)
    @endforeach

@endif
