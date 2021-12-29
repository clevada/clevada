@if($categ->type=='question' and $post->count_best_answer>0 and $loop->index==0)
<button class="float-right btn btn-sm btn-best-answer ml-2 mb-2">{{ __('Best answer') }} ({{ $post->count_best_answer }} {{ __('votes') }})</button>
@endif

{!! strip_tags($post->content, '<code><p><br><a><img><b><strong><i><blockquote><pre><iframe><ol><ul<li><h1><h2><h3><h4><hr>') !!}

@if(forum_attachments($post->id, 'post'))
    <div class="mt-3 mb-3">       
    <div class="row">
        @foreach(forum_attachments($post->id, 'post') as $image)
            @if(($config->forum_images_public ?? null) == 'yes')             
                <div class="col-lg-2 col-md-3 col-4 mb-4">
                <a data-fancybox="gallery_post_{{ $post->id }}" href="{{ image($image->file) }}">
                    <img class="img-fluid mb-2" src="{{ thumb($image->file) }}" alt="{{ $topic->title }} - {{ $image->file }}" title="{{ $topic->title }}">
                </a>
                </div>
            @else    
                <div class="col-12 mb-2">
                    <i class="far fa-image"></i> Image #{{ $loop->iteration }} - <a href="{{ route('login') }}">{{ __('Login to see this image') }}</a>
                </div>
            @endif    
        @endforeach
    </div>
    </div>
@endif

@if (user_extra($post->user_id, 'forum_signature'))
    <div class="mt-5 forum_signature">{!! user_extra($post->user_id, 'forum_signature') !!}</div>
@endif

@if($topic->status=='active' and $post->user_id != Auth::user()->id)

    <hr>

    <span class="float-right">
        @if(($config->forum_likes_system ?? null) !='no')

        @if (forum_check_like('post', $post->id))
               <span class="text-success small"><i class="fas fa-thumbs-up"></i> {{ __('You like this') }} ({{ forum_check_like('post', $post->id) }} {{ __('votes') }})</span>
        @else
        
        <button class="btn btn-sm btn-success ml-2" id="like-post-{{$post->id}}"><i class="fas fa-thumbs-up"></i></button>
        <script>
            $('#like-post-{{$post->id}}').click(function(){                                                    
                $.ajax({
                    type: 'GET',
                    url: '{{ route('forum.like', ['type'=>'post', 'id' => $post->id]) }}',
                    success: function(data) {
                        if(data=='liked') {
                            var elem = document.getElementById('like-success-post-{{ $post->id }}');
                            var like_button = document.getElementById('like-post-{{ $post->id }}');
                            $(elem).show();
                            $(like_button).hide();
                        }
                        if(data=='already_liked') {
                            var elem = document.getElementById('like-error-{{ $post->id }}');
                            var like_button = document.getElementById('like-post-{{ $post->id }}');
                            var elem2 = document.getElementById('like-success-{{ $post->id }}');
                            $(elem2).hide();
                            $(like_button).hide();
                            $(elem).show();
                        }
                        if(data=='login_required') {
                            var like_button = document.getElementById('like-post-{{ $post->id }}');
                            var elem = document.getElementById('like-login-{{ $post->id }}');
                            $(elem).show();
                            $(like_button).hide();
                        }
                    }
                });
            });
        </script>

        <span id="like-success-post-{{ $post->id }}" class="text-success" style="display: none; font-weight:bold"><i class="fas fa-thumbs-up"></i> {{ __('You like this') }}</span>
        <span id="like-error-{{ $post->id }}" class="text-danger" style="display: none; font-weight:bold">{{ __('You already like this') }}</span>

        <span id="like-login-{{ $post->id }}" style="display: none">
            {{ __('You must be logged') }}: <a class="text-danger" href="{{ route('login') }}">{{ __('Login') }}</a>
        </span>

        @endif

        @endif

        @if($categ->type=='question')
        @if (forum_check_best_answer($post->id))
            <span class="text-success small"><i class="fas fa-star"></i> {{ __('You voted best answer') }} ({{ forum_check_best_answer($post->id) }} {{ __('votes') }})</span>
        @else
            <button class="btn btn-sm btn-success ml-2" id="best-answer-post-{{$post->id}}"><i class="fas fa-star"></i> {{ __('Best answer') }}</button>
            <script>
                $('#best-answer-post-{{$post->id}}').click(function(){                                                    
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('forum.best_answer', ['id' => $post->id]) }}',
                        success: function(data) {
                            if(data=='voted') {
                                var elem = document.getElementById('best-answer-success-post-{{ $post->id }}');
                                var best_button = document.getElementById('best-answer-post-{{ $post->id }}');
                                $(elem).show();
                                $(best_button).hide();
                            }
                            if(data=='already_voted') {
                                var elem = document.getElementById('best-answer-error-{{ $post->id }}');
                                var best_button = document.getElementById('best-answer-post-{{ $post->id }}');
                                var elem2 = document.getElementById('best-answer_success-{{ $post->id }}');
                                $(elem2).hide();
                                $(best_button).hide();
                                $(elem).show();
                            }
                            if(data=='login_required') {
                                var best_button = document.getElementById('best-answer-post-{{ $post->id }}');
                                var elem = document.getElementById('best-login-{{ $post->id }}');
                                $(elem).show();
                                $(best_button).hide();
                            }
                        }
                    });
                });
            </script>

            <span id="best-answer-success-post-{{ $post->id }}" class="text-success" style="display: none; font-weight:bold"><i class="fas fa-star"></i> {{ __('Best answer') }}</span>
            <span id="best-answer-error-{{ $post->id }}" class="text-danger" style="display: none; font-weight:bold">{{ __('You already voted this') }}</span>

            <span id="best-login-{{ $post->id }}" style="display: none">
                {{ __('You must be logged') }}: <a class="text-danger" href="{{ route('login') }}">{{ __('Login') }}</a>
            </span>
        @endif
        @endif
    </span>

    <a href="{{ route('forum.report', ['type'=>'post', 'id'=>$post->id]) }}" class="btn btn-sm btn-light mr-2"><i class="fas fa-exclamation-triangle"></i></a>
    <a class="btn btn-sm btn-light" href="#"><i class="fas fa-quote-right"></i> {{ __('Quote') }}</a>

@endif