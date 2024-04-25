<tr @if ($categ->active != 1) class="table-warning" @endif>
    <td>
        @if ($categ->active != 1)
            <span class="float-end ms-2"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Inactive') }}</button></span>
        @endif

        @if ($loop->depth == 1)
            <div class="listing fw-bold">
                @for ($i = 1; $i < $loop->depth; $i++)
                    ---
                @endfor {!! $categ->icon ?? null !!} {{ $categ->title }}
            </div>
        @else
            <div class="listing">
                @for ($i = 1; $i < $loop->depth; $i++)
                    ---
                @endfor {!! $categ->icon ?? null !!} {{ $categ->title }}
            </div>
        @endif
        <div class="text-muted small">
            <b>ID</b> {{ $categ->id }} | <b>{{ __('Position') }}:</b> {{ $categ->position }}
            @if ($categ->description)
                <br>{{ $categ->description }}
            @endif
            @if ($categ->image)
                <br>
                <a target="_blank" href="{{ image($categ->image) }}"><img src="{{ thumb_rectangle($categ->image) }}" class="img-fluid" style="max-height: 35px; max-width: 100px" alt="Image"></a>
            @endif

        </div>

    </td>

    <td>
        <a href="{{ route('admin.posts.index', ['search_categ_id' => $categ->id]) }}">{{ $categ->count_tree_items ?? 0 }} {{ __('posts') }}</a>
    </td>   

    <td>
        <div class="d-grid gap-2">
            @can('view', $categ)
                <button data-bs-toggle="modal" data-bs-target="#update-categ-{{ $categ->id }}" class="btn btn-primary btn-sm mb-2">{{ __('Manage category') }}</button>
                @include('admin.posts.modals.update-categ')
            @endcan

            @can('delete', $categ)
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
            @endcan
        </div>
    </td>
</tr>

@if (count($categ->children) > 0)

    @foreach ($categ->children as $categ)
        @include('admin.posts.loops.categories-loop', $categ)
    @endforeach

@endif
