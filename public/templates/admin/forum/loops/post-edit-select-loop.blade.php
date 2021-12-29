<option @if($topic->categ_id==$categ->id) selected @endif value="{{ $categ->id }}">@for ($i = 1; $i < $loop->depth; ++$i)---@endfor {{ $categ->title }}</option>

@if (count($categ->children) > 0)
@foreach($categ->children as $categ)
	@include('admin.forum.loops.post-edit-select-loop', $categ)
	@endforeach
@endif
