<div class="card mb-5">

    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <div class="user-header">
                    <i class="bi bi-card-text"></i> {{ __('My forum posts') }} ({{ $posts->total() ?? 0 }})
                </div>
            </div>
        </div>
    </div>


    <div class="card-body">

@if(count($posts)==0)
{{ __('No post') }}
@else
<div class="table-responsive-md">

	<table class="table table-bordered table-hover">
		<tbody>

			@foreach ($posts as $post)
			<tr>
				<td>
					<h4><a href="{{ route('forum.topic', ['id' => $post->topic_id, 'slug' => $post->topic_slug]) }}">{{ $post->topic_title }}</a></h4>
					<div class="text-muted text-small">
						{{ date_locale($post->created_at, 'datetime') }}
					</div>
					<p class="text-muted text-small">{{ strip_tags(substr($post->content, 0, 350)) }}...</p>
				</td>

			</tr>
			@endforeach

		</tbody>
	</table>
</div>
@endif

{{ $posts->links() }}

	</div>

</div>