<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true" id="create-account">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data" id="createAccountForm">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">{{ __('Create account or contact') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row bg-light mb-3 pt-3">
                        <div class="fw-bold mb-2">
                            {!! __('<b class="text-danger"><u>Internal</u></b> accounts are staff accounts (for your employees) who have access to some modules') !!}. <a href="{{ route('admin.accounts.permissions') }}">{{ __('Manage internall accounts permissions') }}</a>.                                                        
                            <div class="mb-1"></div>
                            {!! __('<b class="text-danger"><u>Registered users / clients</u></b> can login into own accounts and have access to modules activated (Example: support tickets, projects assigned, orders, invoices...)') !!}. <a href="{{ route('admin.config.general') }}">{{ __('Manage website modules') }}</a>.
                            <div class="mb-1"></div>
                            {!! __('<b class="text-danger"><u>Administrators</u></b> have full access to all modules and configurations. Be careful to add only trusted persons as administrators') !!}.
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Account type (role)') }}</label>
                                <select name="role_id" class="form-select" required onchange="showDiv(this)">
                                    <option value="">- {{ __('select') }} -</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">
                                            @switch($role->role)
                                                @case('user')
                                                    {{ __('Registered user / Client') }}
                                                @break

                                                @case('internal')
                                                    {{ __('Internal') }}
                                                @break

                                                @case('contact')
                                                    {{ __('Contact (no account)') }}
                                                @break

                                                @case('admin')
                                                    {{ __('Administrator') }}
                                                @break

                                                @default
                                                    {{ $role->role }}
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <script>
                                function showDiv(element) {
                                    var option = element.value;
                                    if (option == '{{ $role_id_contact }}') {
                                        document.getElementById('account_data').style.display = 'none';
										document.getElementsByClassName("password_class")[0].removeAttribute("required");
                                    }
									if (option != '{{ $role_id_contact }}') {
                                        document.getElementById('account_data').style.display = 'block';
                                    }
                                }							
                            </script>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Full name') }}</label>
                                <input class="form-control" name="name" type="text" required />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input class="form-control" name="email" type="email" required />
                            </div>
                        </div>
                    </div>

                    <div id="account_data">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Password') }}</label>
                                    <input class="form-control password_class" name="password" type="text" required />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="formFile" class="form-label">{{ __('Avatar image') }} ({{ __('optional') }})</label>
                                    <input class="form-control" type="file" id="formFile" name="avatar">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch1" name="active">
                                    <label class="form-check-label" for="customSwitch1">{{ __('Active') }}</label>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch2" name="email_verified_at">
                                    <label class="form-check-label" for="customSwitch2">{{ __('Email verified') }}</label>
                                </div>
                            </div>
                        </div>                        

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
