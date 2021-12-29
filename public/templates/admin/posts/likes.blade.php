<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts') }}">{{ __('Posts') }}</a></li>
					@if($search_post_id && $post) <li class="breadcrumb-item"><a href="{{ route('admin.posts.show', ['id' => $search_post_id]) }}">{{ $post->title }}</a></li>@endif
					<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.posts.likes') }}">{{ __('Likes') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-12">
                    <h4 class="card-title">{{ $likes -> total() ?? 0 }} {{ __('likes') }} - @if($search_post_id && $post) {{ $post->title }} @else {{ __('all posts') }} @endif</h4>
                </div>                

            </div>

        </div>


		<div class="card-body">	
	
			@if ($message = Session::get('success'))
			<div class="alert alert-success">
				@if ($message=='deleted') {{ __('Deleted') }} @endif
			</div>
			@endif

			<div class="table-responsive-md">

				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>{{ __('Details') }}</th>
							<th width="250">{{ __('Like details') }}</th>
							<th width="50"></th>
						</tr>
					</thead>

					<tbody>

						@foreach ($likes as $like)
						<tr>
							<td>
								@if ($like->post_image)
								<img class="img-fluid float-start me-3" style="max-width:110px; height:auto;" src="{{ thumb($like->post_image) }}" />
								@endif
								<h5><a target="_blank" href="{{ admin_post($like->post_id)->url }}">{{ $like->post_title }}</a></h5>
								<div class="mt-2">{{ $like->post_count_likes ?? 0 }} {{ __('likes') }}</div>
							</td>

							<td>
								{{ date_locale($like->created_at) }}
								<br>
								IP: {{ $like->ip }}
							</td>

							<td>
								<form method="POST" action="{{ route('admin.posts.likes.show', ['id' => $like->id, 'search_post_id' => $search_post_id]) }}">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}
									<button type="submit" class="btn btn-danger btn-sm delete-item-{{$like->id}}"><i class="bi bi-trash"></i></button>
								</form>

								<script>
									$('.delete-item-{{$like->id}}').click(function(e){
												e.preventDefault() // Don't post the form, unless confirmed
												if (confirm('Are you sure to delete this item?')) {
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

			{{ $likes->appends(['search_post_id' => $search_post_id])->links() }}

		</div>
		<!-- end card-body -->

	</div>

</section>