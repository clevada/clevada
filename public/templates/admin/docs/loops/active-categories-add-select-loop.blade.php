<option value="{{ $categ->id }}">@for ($i = 1; $i < $loop->depth; $i++)---@endfor {{ $categ->label }}</option>

@if (count($categ->active_children) > 0)

	@foreach($categ->active_children as $categ)
	@include('admin.docs.loops.categories-add-select-loop', $categ)
	@endforeach

@endif