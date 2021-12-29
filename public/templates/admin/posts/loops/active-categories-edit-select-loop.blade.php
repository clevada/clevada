<option @if($product->categ_id>0) @if($product->categ_id==$cat->id) selected @endif @endif value="{{ $cat->id }}">@for ($i = 1; $i < $level; ++$i)---@endfor {{ $cat->title }}</option>

@if (count($cat->active_children) > 0)
{{ $level = $level + 1 }}	
@foreach($cat->active_children as $cat)
	
	@include('admin.posts.loops.active-categories-edit-select-loop', $cat)
	@endforeach
@endif
