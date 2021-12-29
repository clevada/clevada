<div class="account-header">
    <h3><i class="fas fa-cog"></i> {{ __('Forum settings') }}</h3>
</div>


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
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger">

</div>
@endif

<form method="post" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-12">

            @if(($config->forum_signatures_enabled ?? null) =='no')
            <div class="text-danger">{{ __('Forum signature is disabled') }}</div>
            @elseif(isset($config->forum_signature_min_posts_required) and ($config->forum_signature_min_posts_required ?? null) > $count_forum_posts)
            <div class="text-danger">{{ __('You can not add signature. Minimum posts required' ) }}: {{ $config->forum_signature_min_posts_required }}</div>
            @elseif(isset($config->forum_signature_min_topics_required) and ($config->forum_signature_min_topics_required ?? null) > $count_forum_topics)
            <div class="text-danger">{{ __('You can not add signature. Minimum topics required' ) }}: {{ $config->forum_signature_min_topics_required }}</div>
            @elseif(isset($config->forum_signature_min_likes_required) and ($config->forum_signature_min_likes_required ?? null) > $count_forum_likes_received)
            <div class="text-danger">{{ __('You can not add signature. Minimum likes received required' ) }}: {{ $config->forum_signature_min_likes_required }}</div>
            @elseif(isset($config->forum_signature_min_days_required) and ($config->forum_signature_min_days_required ?? null) > $registration_days)
            <div class="text-danger">{{ __('You can not add signature. Minimum days from registration' ) }}: {{ $config->forum_signature_min_days_required }}</div>
            @else

            <div class="form-group">
                <label>{{ __('Forum signature') }}</label>
                <textarea class="form-control" name="signature" aria-describedby="fHelp" rows="3">{!! $signature !!}</textarea>
                <small id="fHelp" class="form-text text-muted">{{ __('HTML tags permited') }}: {!! htmlspecialchars('<p>, <a>, <br>, <b>, <i>') !!}</small>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            @endif


            @if($signature)
            <div class="mt-3"></div>
            {{ __('Preview') }}:<br>
            {!! $signature !!}
            @endif
        </div>
    </div>

</form>