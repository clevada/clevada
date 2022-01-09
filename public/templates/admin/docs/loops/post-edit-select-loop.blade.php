<option @if($doc->categ_id==$categ->id) selected @endif value="{{ $categ->id }}">@for ($i = 1; $i < $loop->depth; ++$i)---@endfor {{ $categ->label }}</option>

@if (count($categ->children) > 0)
@foreach($categ->children as $categ)
	
	@include('admin.docs.loops.post-edit-select-loop', $categ)
	@endforeach
@endif
