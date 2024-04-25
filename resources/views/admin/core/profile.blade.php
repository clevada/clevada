@include('admin.includes.trumbowyg-assets-simple')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Profile') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">


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
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'avatar-deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. This email exist') }}
                @endif
            </div>
        @endif


        <div class="row">

            <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-4 col-xs-12">

                <div id="avatar_image">
                    <img class="img-fluid rounded mb-3" src="{{ avatar(Auth::user()->id) }}" />
                    <br>

                    @if (Auth::user()->avatar)
                        <a href="#" data-bs-toggle="modal" data-bs-target=".delete-avatar" class="fw-bold text-danger">{{ __('Delete avatar') }}</a>
                        <div class="modal fade delete-avatar" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteAvatarLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ConfirmDeleteAvatarLabel">{{ __('Delete avatar') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ __('Are you sure you want to delete your avatar?ss') }}
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="{{ route('admin.profile.delete_avatar') }}">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                            <button type="submit" class="btn btn-danger">{{ __('Delete avatar') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                
                <a target="_blank" href="{{ route('profile', ['id' => Auth::user()->id, 'slug' => Auth::user()->slug]) }}">{{ __('View my public profile page') }}</a>
            </div>


            <div class="col-xxl-10 col-lg-9 col-md-8 col-sm-8 col-xs-12">

                <form method="post" enctype="multipart/form-data">
                    @csrf

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
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Password') }} ({{ __('leave empty not to change') }})</label>
                                <input class="form-control" name="password" type="password" value="" autocomplete="new-password" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="formFile" class="form-label">{{ __('Change avatar') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="file" id="formFile" name="avatar">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Bio') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="bio" rows="5">{!! $bio ?? null !!}</textarea>
                                <div class="text-muted small">{{ __('Bio is displayed in user profile page') }}</div>
                            </div>
                        </div>
                    </div>

                    @if(($config->module_forum ?? null) == 'active')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Forum signature') }} ({{ __('optional') }})</label>
                                <textarea class="form-control trumbowyg" name="forum_signature" rows="2">{!! Auth::user()->forum_signature !!}</textarea>
                                <div class="text-muted small">{{ __('Signature is displayed at the bottom of each of my topic or post.') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif 

                    <button type="submit" class="btn btn-primary">{{ __('Update profile') }}</button>

                </form>

            </div>

        </div>

    </div>
    <!-- end card-body -->

</div>
