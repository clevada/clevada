<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts') }}">{{ __('Accounts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit account') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">
            <h4 class="card-title">{{ $account->name }}</h4>
            @if ($account->is_deleted == 1)<div class='alert alert-danger fw-bold'>{{ __('This account is deleted') }}. <a href="{{ route('admin.accounts.deleted') }} ">{{ __('View deleted accounts') }}</a></div>@endif
        </div>

        <div class="card-body">

            @include('admin.accounts.layouts.menu-account')
            <div class="mb-3"></div>

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
                    @if ($message == 'updated') {{ __('Updated') }}@endif
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This email exist') }} @endif
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-12">
                    @if ($account->avatar)
                        <span class="float-start me-2"><img style="max-height:120px; width:auto;" src="{{ asset('uploads/' . $account->avatar) }}" /></span>
                    @endif
                    {{ __('ID') }}: {{ strtoupper($account->id) }} <br>
                    {{ __('Code') }}: {{ strtoupper($account->code) ?? null }} <br>
                    {{ __('Registered') }}: {{ date_locale($account->created_at, 'datetime') }} <br>
                    {{ __('Register IP') }}: {{ $account->register_ip }}
                    @if ($account->role != 'contact')<br>{{ __('Last activity') }}: @if ($account->last_activity){{ date_locale($account->last_activity, 'datetime') }}@else {{ __('never') }}@endif @endif
                </div>

                @if ($account->user_tags)
                    <div class="mt-2">
                        @foreach ((array) explode(',', $account->user_tags) as $tag)
                            <a href="{{ route('admin.accounts', ['search_tag_id' => explode('@', $tag)[0]]) }}"><span class="mr-2 small"
                                    style="background-color: {{ explode('@', $tag)[2] ?? '#b7b7b7' }}; padding: 4px 6px; display: inline; color: #fff; width: 100%;">{{ explode('@', $tag)[1] ?? null }}</span></a>
                        @endforeach
                    </div>
                @endif

            </div>

            <form action="{{ route('admin.accounts.show', ['id' => $account->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-lg-4 col-12">
                        <div class="form-group">
                            <label>{{ __('Full name') }}</label>
                            <input class="form-control" name="name" type="text" required value="{{ $account->name }}" />
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="form-group">
                            <label>{{ __('Email') }}</label>
                            <input class="form-control" name="email" type="email" required value="{{ $account->email }}" />
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="form-group">
                            <label>{{ __('Role') }}</label>
                            <select name="role_id" class="form-select" required>
                                <option value="">- {{ __('select') }} -</option>
                                @foreach ($roles as $role)
                                    <option @if ($account->role_id == $role->id) selected="selected" @endif value="{{ $role->id }}">
                                        @switch($role->role)
                                            @case('admin')
                                                {{ __('Administrator') }}
                                            @break

                                            @case('user')
                                                {{ __('Registered user') }}
                                            @break

                                            @case('internal')
                                                {{ __('Internal') }}
                                            @break

                                            @case('vendor')
                                                {{ __('Vendor') }}
                                            @break

                                            @case('contact')
                                                {{ __('Contact (no account)') }}
                                            @break

                                            @default
                                                {{ $role->role }}
                                        @endswitch
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-lg-4 col-12">
                        <div class="form-group">
                            <label>{{ __('Change password') }} ({{ __('optional') }})</label>
                            <input class="form-control" name="password" type="password" />
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="form-group">
                            <label for="formFile" class="form-label">{{ __('Avatar image') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="file" id="formFile" name="avatar">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitch1" name="active" @if ($account->active == 1) checked @endif>
                                <label class="form-check-label" for="customSwitch1">{{ __('Active') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitch2" name="email_verified_at" @if ($account->email_verified_at) checked @endif>
                                <label class="form-check-label" for="customSwitch2">{{ __('Email verified') }}</label>
                            </div>
                        </div>

                        @if ($is_user)
                            <hr>

                            <div class="fs-5">{{ __('Posts access') }}:</div>

                            <div class="form-group mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="switchPostsContributor" name="posts_contributor" @if ($account->posts_contributor ?? null) checked @endif>
                                    <label class="form-check-label" for="switchPostsContributor">{{ __('This user is posts contributors (can create articles)') }}</label>
                                    <div class="form-text">{{ __('Notes: you can enable / disable globally this setting in registration settings') }}. <a
                                            href="{{ route('admin.config.registration') }}">{{ __('Registration settings') }}</a></div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="switchPostsContributorApprove" name="posts_auto_approve" @if ($account->posts_auto_approve ?? null) checked @endif>
                                    <label class="form-check-label" for="switchPostsContributorApprove">{{ __('Posts (articles) are automatically approved (not recommended)') }}</label>
                                    <div class="form-text">{{ __('Notes: you can enable / disable globally this setting in registration settings') }}. <a
                                            href="{{ route('admin.config.registration') }}">{{ __('Registration settings') }}</a></div>
                                </div>
                            </div>
                        @endif

                    </div>


                </div>

                @if (check_access('accounts', 'manager'))
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                @endif

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
