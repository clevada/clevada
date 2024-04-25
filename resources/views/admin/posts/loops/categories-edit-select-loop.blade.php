<option @if($categ->parent_id>0) @if($categ->parent_id==$cat->id) selected @endif @endif value="{{ $cat->id }}">@for ($i = 1; $i < $level; ++$i)---@endfor {{ $cat->title }}</option>

@if (count($cat->children) > 0)
{{ $level = $level + 1 }}	
@foreach($cat->children as $cat)	
	@include('admin.posts.loops.categories-edit-select-loop', $cat)
	@endforeach
@endif
