<option @if($post->categ_id==$categ->id) selected @endif value="{{ $categ->id }}">@for ($i = 1; $i < $loop->depth; ++$i)---@endfor {{ $categ->title }} @if(count(sys_langs())>1) ({{ $categ->lang_name }}) @endif</option>

@if (count($categ->children) > 0)
@foreach($categ->children as $categ)
	
	@include('admin.posts.loops.post-edit-select-loop', $categ)
	@endforeach
@endif
