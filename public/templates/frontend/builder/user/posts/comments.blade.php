<div class="user-header">
	<i class="far fa-comment-alt"></i> {{ __('Articles comments') }} ({{ $comments->total() ?? 0 }})
</div>

<div class="table-responsive-md">

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="400">{{ __('Article details') }}</th>
				<th>{{ __('Comment') }}</th>
			</tr>
		</thead>

		<tbody>

			@foreach ($comments as $comment)
			<tr>
				<td>
					@if ($comment->post_image)
					<img class="img-fluid float-left mr-2" style="max-width:60px; height:auto;" src="{{ asset('uploads/'.nura_thumb($comment->post_image)) }}" />
					@endif
					<a style="display: block" target="_blank" href="{{ route('blog.post', ['id' => $comment->post_id, 'slug' => $comment->post_slug]) }}">{{ $comment->post_title }}</a>
				</td>

				<td>
					<div class="text-muted mb-3">{{ nura_datetime_format($comment->created_at) }}</div>
					{!! nl2br(e($comment->comment)) !!}
				</td>

				<td>
					<form method="POST" action="{{ route('user.blog.comments.show', ['id'=>$comment->id]) }}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}
						<button type="submit" class="btn btn-danger btn-sm delete-item-{{$comment->id}}"><i class="fas fa-trash-alt"></i></button>
					</form>

					<script>
						$('.delete-item-{{$comment->id}}').click(function(e){
							e.preventDefault() // Don't post the form, unless confirmed
							if (confirm(" {{ __('Are you sure to delete this item?') }}")) {
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

{{ $comments->links() }}