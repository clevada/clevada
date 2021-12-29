<option @if($categ->allow_topics!=1) disabled @endif value="{{ $categ->id }}">@for ($i = 1; $i < $loop->depth; $i++)---@endfor {{ $categ->title }}</option>

@if (count($categ->children) > 0)

	@foreach($categ->children as $categ)
	@include("{$template_view}.loops.forum-categories-select-loop", $categ)
	@endforeach

@endif