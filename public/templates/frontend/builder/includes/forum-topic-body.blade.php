<div class="card-body">
    <div id="topic_content" class="forum_content">
        {!! strip_tags($topic->content, '<code><p><br><a><img><b><strong><i><blockquote><pre><iframe><ol><ul<li><h1><h2><h3><h4><hr>') !!}
    </div>
    
    @if(forum_attachments($topic->id, 'topic'))
    <div class="mt-3 mb-3">       
    <div class="row">
        @foreach(forum_attachments($topic->id, 'topic') as $image)
            @if(($config->forum_images_public ?? null) == 'yes')             
                <div class="col-lg-2 col-md-3 col-4 mb-4">
                    <a data-fancybox="gallery_topic" href="{{ image($image->file) }}">
                        <img class="img-fluid mb-2" src="{{ thumb($image->file) }}" alt="{{ $topic->title }} - {{ $image->file }} "
                            title="{{ $topic->title }}">
                    </a>
                </div>
            @else    
                <div class="col-12 mb-2">
                    <i class="far fa-image"></i> {{__('Image') }} #{{ $loop->iteration }} - <a href="{{ route('login') }}">{{ __('Login to see this image') }}</a>
                </div>
            @endif    
        @endforeach
    </div>
    </div>
    @endif

    @if (user_extra($topic->user_id, 'forum_signature'))
        <div class="mt-5 forum_signature">{!! user_extra($topic->user_id, 'forum_signature') !!}</div>
    @endif

    @if($topic->status=='active' and $topic->user_id != Auth::user()->id)
    
    <hr>
    
    @if(($config->forum_likes_system ?? null) !='no')
    <span class="float-right">
        @if (forum_check_like('topic', $topic->id))
            <span class="text-success small">{{ __('You like this') }} ({{ forum_check_like('topic', $topic->id) }} {{ __('votes') }})</span>
        @else
            <button class="btn btn-sm btn-success ml-2" id="like-topic-{{$topic->id}}"><i class="fas fa-thumbs-up"></i></button>

            <script>
                $('#like-topic-{{$topic->id}}').click(function(){                                                    
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('forum.like', ['type'=>'topic', 'id' => $topic->id]) }}',
                        success: function(data) {
                            if(data=='liked') {
                                var elem = document.getElementById('like-success-topic-{{ $topic->id }}');
                                var like_button = document.getElementById('like-topic-{{ $topic->id }}');
                                $(elem).show();
                                $(like_button).hide();
                            }
                            if(data=='already_liked') {
                                var elem = document.getElementById('like-error-{{ $topic->id }}');
                                var like_button = document.getElementById('like-topic-{{ $topic->id }}');
                                var elem2 = document.getElementById('like-success-{{ $topic->id }}');
                                $(elem2).hide();
                                $(like_button).hide();
                                $(elem).show();
                            }
                            if(data=='login_required') {
                                var like_button = document.getElementById('like-topic-{{ $topic->id }}');
                                var elem = document.getElementById('login-topic-{{ $topic->id }}');
                                $(elem).show();
                                $(like_button).hide();
                            }
                        }
                    });
                });
            </script>

            <span id="like-success-topic-{{ $topic->id }}" class="text-success" style="display: none; font-weight:bold">{{ __('You like this') }}</span>
            <span id="like-error-{{ $topic->id }}" class="text-danger" style="display: none; font-weight:bold">{{ __('You already like this') }}</span>

            <span id="login-topic-{{ $topic->id }}" style="display: none">
                {{ __('You must be logged') }}: <a class="text-danger" href="{{ route('login') }}">{{ __('Login') }}</a>
            </span>
        @endif
    </span>
    @endif

    <a href="{{ route('forum.report', ['type'=>'topic', 'id'=>$topic->id]) }}" class="btn btn-sm btn-light mr-2"><i class="fas fa-exclamation-triangle"></i></a>

    <a class="btn btn-sm btn-light" href="#reply" id="qoute_topic"><i class="fas fa-quote-right"></i> {{ __('Quote') }}</a>
        <script>
            $('#qoute_topic').click(function (){
                var node = document.getElementById('topic_content');
                var reply = document.getElementsByClassName('editor');
                textContent = node.textContent;
                $('.editor').trumbowyg('html', "<blockquote><small>{{ $topic->author_name }} - {{ date_locale($topic->created_at, 'datetime') }}</small><br><br>"+textContent+"</blockquote><br>");
            });   
        </script>
    @endif

</div>