<div class="card-header">
	<h3><i class="bi bi-chat-left-text"></i> {{ __('Forum posts') }} ({{ $posts->total() ?? 0 }})</h3>
</div>
<!-- end card-header -->

<div class="card-body">

	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	@if ($message = Session::get('success'))
	<div class="alert alert-success">
		@if ($message=='updated') {{ __('Updated') }} @endif
		@if ($message=='deleted') {{ __('Deleted') }} @endif
	</div>
	@endif

	<section>
			<form action="{{ route('admin.forum.posts') }}" method="get" class="form-inline">
				<input type="text" name="search_terms" placeholder="{{ __('Search author') }}" class="form-control mr-2 @if($search_terms) is-valid @endif" value="<?= $search_terms;?>" />				
	
				<button class="btn btn-dark mr-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
				<a class="btn btn-light" href="{{ route('admin.forum.posts') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
			</form>
		</section>
		<div class="mb-3"></div>

	<div class="table-responsive-md">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>{{ __('Details') }}</th>
					<th width="300">{{ __('Author') }}</th>
					<th width="100">{{ __('Actions') }}</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($posts as $post)
				<tr>
					<td>
						<span class="float-right text-muted text-small">{{ date_locale($post->created_at, 'datetime') }}</span>
						<h4><a target="_blank" href="{{ route('forum.post', ['topic_id' => $post->topic_id, 'slug' => $post->topic_slug, 'post_id' => $post->id]) }}">{{ $post->topic_title }}</a></h4>
						<div class="text-small">{{ substr(strip_tags($post->content), 0, 600) }}...</div>
					</td>
				
					<td>
						@if($post->author_avatar) <img class="logged_user_avatar rounded-circle" style="max-height:20px" src="{{ thumb($post->author_avatar) }}">@endif
						{{ $post->author_name}}
					</td>
				
					<td>
						<div class="d-flex">
							<form method="POST" action="{{ route('admin.forum.posts.delete', ['id' => $post->id]) }}">
								{{ csrf_field() }}
								<button type="submit" class="float-right btn btn-danger btn-sm delete-item-{{$post->id}}"><i class="bi bi-x-square"></i></button>
							</form>
						</div>

						<script>
							$('.delete-item-{{$post->id}}').click(function(e){
									e.preventDefault() // Don't post the form, unless confirmed
									if (confirm('Are you sure to delete this post?')) {
										$(e.target).closest('form').submit() // Post the surrounding form
									}
								});
						</script>
					</td>
				</tr>
				@endforeach

			</tbody>
		</table>
	</div>

	{{ $posts->appends(['search_terms' => $search_terms])->links() }}

</div>
<!-- end card-body -->