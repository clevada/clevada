<option value="{{ $categ->id }}">@for ($i = 1; $i < $loop->depth; $i++)---@endfor {{ $categ->title }}</option>

@if (count($categ->children) > 0)

	@foreach($categ->children as $categ)
	@include('admin.forum.loops.categories-add-select-loop', $categ)
	@endforeach

@endif