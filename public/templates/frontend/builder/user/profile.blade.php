<div class="user-header">
    <i class="bi bi-person-bounding-box"></i> {{ __('Profile') }}
</div>

@if ($message = Session::get('error'))
<div class="alert alert-danger">
    @if ($message=='duplicate_email') {{ __('Error. There is another user with this email address') }} @endif
    @if ($message=='demo') <h4>{{ __('This action is disabled in demo mode') }}</h4> @endif
</div>
@endif

@if(logged_user()->count_unpaid_orders>0)
<div class="alert alert-danger">
    <a class="font-weight-bold text-danger" href="{{ route('user.orders', ['lang' => $lang]) }}">{{ logged_user()->count_unpaid_orders }} {{ __('unpaid orders') }}</a>
    <hr>
    {{ __('Products or services related to unpaid order will be delivered after you pay the order') }}.<br>
    {{ __('If you ordered downloadable products (software), the downloads will be available automatically right after payment') }}.<br>
</div>
@endif

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
<div class="alert alert-success mb-3">
    @if ($message=='updated') {{ __('Updated') }} @endif
    @if ($message=='avatar-deleted') {{ __('Deleted') }} @endif
</div>
@endif


<form method="post" enctype="multipart/form-data" action="{{ route('user.profile', ['lang' => $lang]) }}">
    @csrf

    <div class="row">

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">

            @if(Auth::user()->avatar)
            <div id="avatar_image">
                <img class="img-fluid avatar-rounded mb-3" src="{{ image(Auth::user()->avatar) }}" />
                <br>
                <div class="text-danger"><i class="fas fa-times mb-4"></i> <a class="text-danger" href="{{ route('user.profile.delete_avatar', ['lang' => $lang]) }}">{{ __('Delete avatar') }}</a></div>                
            </div>
            @else
            <img src="{{ asset('/assets/img/no-avatar-big.png') }}" class="img-fluid mb-3">
            @endif

        </div>


        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">

            <div class="alert alert-info mb-3">
                <b>{{ __('Your user ID is') }}: {{ strtoupper(Auth::user()->code ?? NULL) }}</b>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Full name') }} ({{ __('required') }})</label>
                        <input class="form-control" name="name" type="text" value="{{ Auth::user()->name }}" required />
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Valid Email') }} ({{ __('required') }})</label>
                        <input class="form-control" name="email" type="email" value="{{ Auth::user()->email }}" required />
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Change avatar') }} ({{ __('optional') }})</label>
                        <input class="form-control" type="file" id="formFile" name="avatar">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Change password') }} ({{ __('optional') }})</label>
                        <input class="form-control" name="password" type="password" value="" autocomplete="new-password" />
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>{{ __('Bio') }} ({{ __('optional') }})</label>
                        <textarea class="form-control" name="bio" rows="2">{{ $bio ?? null }}</textarea>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <hr>

    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>

</form>