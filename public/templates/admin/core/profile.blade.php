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


<section class="section">

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
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'avatar-deleted') {{ __('Deleted') }} @endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This email exist') }} @endif
                </div>
            @endif


            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">

                        @if (Auth::user()->avatar)
                            <div id="avatar_image">
                                <img class="img-fluid avatar-rounded mb-3" src="/uploads/{{ Auth::user()->avatar }}" />
                                <br>
                                <div class="text-danger"><i class="fas fa-times mb-4"></i> <a class="delete_image text-danger" href="/admin/profile/delete-avatar">{{ __('Delete avatar') }}</a></div>
                                <script>
                                    $(function() {
                                        $('.delete_image').click(function() {
                                            var id = $(this).attr('id');

                                            $.ajax({
                                                type: "GET",
                                                url: "{{ route('admin.profile.delete_avatar') }}",

                                                success: function() {
                                                    $('#avatar_image').hide();
                                                    $("#image_deleted_text").html("Deleted").css('color', 'red');
                                                }
                                            });
                                            return false;
                                        });
                                    });
                                </script>
                            </div>
                            <div id="image_deleted_text"></div>
                        @else
                            <img src="{{ asset('/assets/img/no-avatar-big.png') }}" class="img-fluid mb-3">
                        @endif

                        <div class="form-group">
                            <label for="formFile" class="form-label">{{ __('Avatar image') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="file" id="formFile" name="avatar">
                        </div>
                    </div>


                    <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">


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
                        </div>


                    </div>

                </div>


                <hr>

                <button type="submit" class="btn btn-primary">{{ __('Update profile') }}</button>

            </form>


        </div>
        <!-- end card-body -->

    </div>

</section>
