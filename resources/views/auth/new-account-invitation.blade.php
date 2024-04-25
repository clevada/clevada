<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('New account invitation') }}</title>

    @include('auth.includes.global-head')
</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row py-5 mt-5 align-items-center">

            <div class="col-md-6 offset-md-3">

                <div class="text-center mb-4">
                    <img src="{{ config('app.cdn') }}/img/logo-auth.png" class="img-fluid" alt="Clevada Suite" title="Clevada Suite">
                    <hr>
                </div>


                <div class='fs-5 mb-1'>
                    {{ __('New account invitation') }}
                    @if ($invitation->role == 'admin')
                        <span class="badge ms-2 bg-danger fw-normal">{{ __('Administrator account') }}</span>
                    @endif
                    @if ($invitation->role == 'internal')
                        <span class="badge ms-2 bg-danger fw-normal">{{ __('Internal account') }}</span>
                    @endif
                </div>

                <div class='fs-6 mb-3 fw-bold'>{{ __('You received an invitation to create an account. Please fill details below to create a new account.') }}</div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        @if ($message == 'no_name')
                            {{ __('Error. Input name') }}
                        @endif
                        @if ($message == 'error_password')
                            {{ __('Error. Password must be the same in both fields. Password must have minimum 8 characters') }}
                        @endif
                    </div>
                @endif

                <form method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <!-- Email Address -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonEmail"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ __('Email') }}" aria-label="{{ __('Email') }}" aria-describedby="addonEmail"
                                    @error('email') is-invalid @enderror required autocomplete="email" value="{{ $invitation->email }}" @if ($invitation->method == 'email') disabled readonly @endif>
                            </div>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonName"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control form-control-lg" placeholder="{{ __('Full name') }}" aria-label="{{ __('Full name') }}" aria-describedby="addonName"
                                    @error('name') is-invalid @enderror required autocomplete="name" value="{{ $invitation->name }}">
                            </div>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonUsername"><i class="bi bi-person-gear"></i></span>
                                <input type="text" name="name" class="form-control form-control-lg" placeholder="{{ __('Username') }}" aria-label="{{ __('Username') }}" aria-describedby="addonUsername"
                                    @error('username') is-invalid @enderror required autocomplete="name" value="{{ $invitation->name }}">
                            </div>
                            <div class="text-muted small">
                                {{ __('Only letters, numbers, "-", "_" and "." signs. Username can not be changed.') }}
                            </div>

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonPw"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="{{ __('Password') }}" aria-label="{{ __('Password') }}" aria-describedby="addonPw"
                                    @error('password') is-invalid @enderror required minlength="8" id="pwInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                    title="{{ __('Must contain at least one number and one uppercase and lowercase letter, and minimum 8 characters') }}">
                            </div>
                            <div class="small text-muted">{{ __('Must contain at least one number and one uppercase and lowercase letter, and minimum 8 characters') }}</div>

                            <div class="form-check mt-2">
                                <input type="checkbox" onclick="showPassword()" class="form-check-input mt-1" id="showPw"> <label for="showPw" class="fw-normal">{{ __('Show Password') }}</label>
                            </div>

                            <script>
                                function showPassword() {
                                    var x = document.getElementById("pwInput");
                                    var x2 = document.getElementById("pw2Input");
                                    if (x.type === "password") {
                                        x.type = "text";
                                        x2.type = "text";
                                    } else {
                                        x.type = "password";
                                        x2.type = "password";
                                    }
                                }
                            </script>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



                        <!-- Password -->
                        <div class="input-group col-12 mb-4">
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="addonPw2"><i class="bi bi-lock"></i></span>
                                <input type="password" minlength="8" name="password_repeat" class="form-control form-control-lg" placeholder="{{ __('Repeat password') }}" aria-label="{{ __('Repeat password') }}"
                                    aria-describedby="addonPw2" @error('password_repeat') is-invalid @enderror id="pw2Input" required>
                            </div>

                            @error('password_repeat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <!-- Submit Button -->
                        <div class="form-group col-lg-12 mx-auto mb-0">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-block py-2">
                                    <span class="font-weight-bold">{{ __('Create account') }}</span>
                                </button>
                            </div>
                        </div>


                        <!-- Divider Text -->
                        <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                            <div class="border-bottom w-100 ml-5"></div>
                            <span class="px-2 small text-muted font-weight-bold text-muted">{{ __('OR') }}</span>
                            <div class="border-bottom w-100 mr-5"></div>
                        </div>


                        <!-- Already Registered -->
                        <div class="text-center w-100">
                            @if (Route::has('register'))
                                <p class="text-muted font-weight-bold">{{ __('Already registered?') }} <a href="{{ route('login') }}" class="text-primary ml-2">{{ __('Login') }}</a></p>
                            @endif
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>
