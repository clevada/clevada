<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}">{{ __('Accounts') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit account') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">
        <h4 class="card-title">{{ $account->name }}</h4>
    </div>

    <div class="card-body">

        @include('admin.accounts.includes.menu-account')
        <div class="mb-3"></div>

        @if ($account->deleted_at)
            <div class='alert alert-danger mt-3'>{{ __('This account is deleted') }}. <a href="{{ route('admin.recycle_bin.module', ['module' => 'accounts']) }} ">{{ __('View deleted accounts') }}</a></div>
        @endif
        @if ($account->blocked_at)
            <div class='alert alert-danger mt-3'>{{ __('This account is blocked') }}.</div>
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
            <div class="alert alert-success">
                @if ($message == 'updated')
                    {{ __('Updated') }}
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

        <div class="row mb-3">
            <div class="col-12">
                @if ($account->avatar)
                    <span class="float-start me-2"><img style="max-height:120px; width:auto;" src="{{ asset('uploads/avatars/' . $account->avatar) }}" /></span>
                @endif
                {{ __('ID') }}: {{ strtoupper($account->id) }} <br>
                {{ __('Registered') }}: {{ date_locale($account->created_at, 'datetime') }} <br>
                {{ __('Last activity') }}: @if ($account->last_activity_at)
                    {{ date_locale($account->last_activity_at, 'datetime') }}
                @else
                    {{ __('never') }}
                @endif
            </div>

            {{--
                @if ($account->user_tags)
                    <div class="mt-2">
                        @foreach ((array) explode(',', $account->user_tags) as $tag)
                            <a href="{{ route('admin.accounts', ['search_tag_id' => explode('@', $tag)[0]]) }}"><span class="mr-2 small"
                                    style="background-color: {{ explode('@', $tag)[2] ?? '#b7b7b7' }}; padding: 4px 6px; display: inline; color: #fff; width: 100%;">{{ explode('@', $tag)[1] ?? null }}</span></a>
                        @endforeach
                    </div>
                @endif
                --}}

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

                @if (Auth::user()->role == 'admin')
                    <div class="col-lg-4 col-12">
                        <div class="form-group">
                            <label>{{ __('Username') }}</label>
                            <input class="form-control" name="username" type="text" required value="{{ $account->username }}" />
                            <div class="text-muted small">
                                {{ __('Lowercases. Spaces are converted to "-". Special characters are removed.') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->role == 'admin' && Auth::user()->id != $account->id)
                    <div class="col-lg-4 col-12">
                        <div class="form-group">
                            <label>{{ __('Role') }}</label>
                            <select name="role" class="form-select" required>
                                <option value="">- {{ __('select') }} -</option>
                                <option @if ($account->role == 'user') selected @endif value="user">{{ __('Registered user') }}</option>
                                <option @if ($account->role == 'contributor') selected @endif value="contributor">{{ __('Contributor') }}</option>
                                <option @if ($account->role == 'author') selected @endif value="author">{{ __('Author') }}</option>
                                <option @if ($account->role == 'editor') selected @endif value="editor">{{ __('Editor') }}</option>
                                <option disabled>_____________</option>
                                <option @if ($account->role == 'developer') selected @endif value="developer">{{ __('Developer') }}</option>
                                <option @if ($account->role == 'manager') selected @endif value="manager">{{ __('Manager') }}</option>
                                <option @if ($account->role == 'admin') selected @endif value="admin">{{ __('Administrator') }}</option>
                            </select>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-info-circle"></i> <a href="#" data-bs-toggle="modal" data-bs-target="#help-roles">{{ __('Accounts roles help') }}</a>
                            </div>
                            @include('admin.accounts.modals.help-roles')
                        </div>
                    </div>
                @else
                    <input type="hidden" name="role" value="{{ Auth::user()->role }}">
                @endif

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
                    @if ((Auth::user()->role == 'admin' || Auth::user()->role == 'manager') && Auth::user()->id != $account->id)
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="SwitchBlock" name="blocked_at" @if ($account->blocked_at) checked @endif>
                                <label class="form-check-label" for="SwitchBlock">{{ __('Block account') }}</label>
                            </div>
                            <div class="text-muted small">{{ __('Blocked users can not login into their accounts.') }}</div>
                        </div>

                        <script>
                            $('#SwitchBlock').change(function() {
                                select = $(this).prop('checked');
                                if (select)
                                    document.getElementById('hidden_div').style.display = 'block';
                                else
                                    document.getElementById('hidden_div').style.display = 'none';
                            })
                        </script>

                        <div id="hidden_div" @if ($account->blocked_at) style="display: visible" @else style="display: none" @endif>
                            <div class="form-group col-12">
                                <label>{{ __('Block reason') }} </label>
                                <textarea name="block_reason" class="form-control" rows="5">{!! $block_reason ?? null !!}</textarea>
                                <div class="text-muted small">{{ __('This text will be visible to user.') }}</div>
                            </div>
                        </div>
                    @endif


                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="customSwitch2" name="email_verified_at" @if ($account->email_verified_at) checked @endif>
                            <label class="form-check-label" for="customSwitch2">{{ __('Email verified') }}</label>
                        </div>
                        <div class="text-muted small">{{ __('Users can not use their accounts until email is verified.') }}</div>
                    </div>
                </div>


            </div>

            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>

        </form>

    </div>
    <!-- end card-body -->

</div>
