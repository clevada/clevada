<tr @if ($categ->active!=1) class="table-warning" @endif>
	<td>
		@if ($categ->active!=1) <span class="pull-right ml-2"><button type="button" class="btn btn-warning btn-sm disabled">{{ __('Inactive') }}</button></span> @endif
		<h4>@for ($i = 1; $i < $loop->depth; $i++)---@endfor {!! $categ->icon ?? null !!} {{ $categ->title }}</h4>
		<div class="text-muted small">
			ID: {{ $categ->id }} | {{ __('Position') }}: {{ $categ->position }}
			<br>
			{{ __('Allow topics') }}: @if($categ->allow_topics==1){{ __('Yes') }} @else {{ __('No') }}@endif
			@if($categ->description)<br>{{ $categ->description }}@endif
		</div>
	</td>

	<td>
		@if ($categ->type == 'discussion') <i class="far fa-comment-alt"></i> {{ __('Discussion') }} @endif
		@if ($categ->type == 'question') <i class="far fa-question-circle"></i> {{ __('Question & Answers') }} @endif
	</td>

	<td>
		<h5><a href="{{ route('admin.forum.topics', ['search_categ_id'=>$categ->id]) }}">{{ $categ->count_tree_topics ?? 0 }} {{ __('topics') }}</a></h5>
		<h5><a href="{{ route('admin.forum.posts', ['search_categ_id'=>$categ->id]) }}">{{ $categ->count_tree_posts ?? 0 }} {{ __('posts') }}</a></h5>
	</td>

	<td>
		<div class="d-grid gap-2">
            <button data-bs-toggle="modal" data-bs-target="#update-categ-{{ $categ->id }}" class="btn btn-primary btn-sm mb-2">{{ __('Update category') }}</button>
            @include('admin.forum.modals.update-categ')

            <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $categ->id }}" class="btn btn-danger btn-sm">{{ __('Delete category') }}</a>
            <div class="modal fade confirm-{{ $categ->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ __('Are you sure you want to delete this category? All posts and topics from this category (and subcategories) will be assigned to uncategorized category.') }}
                        </div>
                        <div class="modal-footer">
                            <form method="POST" action="{{ route('admin.forum.categ.show', ['id' => $categ->id]) }}">
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

@foreach($categ->children as $categ)
@include('admin.forum.loops.categories-loop', $categ)
@endforeach

@endif