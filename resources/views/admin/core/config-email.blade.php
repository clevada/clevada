@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config', ['module' => 'email']) }}">{{ __('Configuration') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                @include('admin.core.layouts.menu-config')
            </div>

        </div>

    </div>


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
                @if ($message == 'test_email_ok')
                    {{ __('Test email sent. Please check your email address or log file') }}
                @endif
            </div>
        @endif
       
        <form method="post">
            @csrf

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>{{ __('Mail sending driver') }}</label>
                        <select name="mail_driver" class="form-select">
                            <option @if (($config->mail_driver ?? null) == 'smtp') selected @endif value="smtp">{{ __('SMTP mailer') }}</option>
                            <option @if (($config->mail_driver ?? null) == 'mailgun') selected @endif value="mailgun">{{ __('Mailgun') }}</option>
                            <option @if (($config->mail_driver ?? null) == 'sendmail') selected @endif value="sendmail">{{ __('Sendmail') }}</option>
                            <option @if (($config->mail_driver ?? null) == 'log') selected @endif value="log">{{ __('LOG') }}</option>
                        </select>
                        <div class="form-text">{{ __('If you select LOG, emails are not sending, only save in log file') }}</div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>{{ __('Site email address') }} (From: email)</label>
                        <input class="form-control" name="mail_from_address" type="text" required value="{{ $config->mail_from_address ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>{{ __('Email name') }} (From: name)</label>
                        <input type="text" class="form-control" name="mail_from_name" required value="{{ $config->mail_from_name ?? null }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>SMTP server (host)</label>
                        <input type="text" class="form-control" name="smtp_host" value="{{ $config->smtp_host ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>SMTP user</label>
                        <input type="text" class="form-control" name="smtp_username" value="{{ $config->smtp_username ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>SMTP password</label>
                        <input type="password" class="form-control" name="smtp_password" value="{{ $config->smtp_password ?? null }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label>SMTP port</label>
                        <input type="text" class="form-control" name="smtp_port" value="{{ $config->smtp_port ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label>SMTP encryption</label>
                        <select name="smtp_encryption" class="form-select">
                            <option @if (($config->smtp_encryption ?? null) == 'tls') selected @endif value="tls">TLS</option>
                            <option @if (($config->smtp_encryption ?? null) == 'ssl') selected @endif value="ssl">SSL</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr />

            <h5>Mailgun integration (optional)</h5>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Mailgun domain</label>
                        <input type="text" class="form-control" name="mailgun_domain" value="{{ $config->mailgun_domain ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Mailgun secret key</label>
                        <input type="text" class="form-control" name="mailgun_secret" value="{{ $config->mailgun_secret ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>Mailgun endpoint</label>
                        <select class="form-select" name="mailgun_endpoint">
                            <option @if (($config->mailgun_endpoint ?? null) == 'api.mailgun.net') selected @endif value="api.mailgun.net">US Endpoint</option>
                            <option @if (($config->mailgun_endpoint ?? null) == 'api.eu.mailgun.net') selected @endif value="api.eu.mailgun.net">EU Endpoint</option>
                        </select>
                    </div>
                </div>
            </div>

            <h5>AWS integration (optional)</h5>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>AWS key</label>
                        <input type="text" class="form-control" name="aws_key" value="{{ $config->aws_key ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>AWS secret</label>
                        <input type="text" class="form-control" name="aws_secret" value="{{ $config->aws_secret ?? null }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>AWS region</label>
                        <input type="text" class="form-control" name="aws_region" value="{{ $config->aws_region ?? null }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label>{{ __('Email signature') }}</label>
                <textarea class="form-control trumbowyg" name="mail_signature">{!! $config->mail_signature ?? null !!}</textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>

</section>


<section class="section">

    <div class="card">

        <div class="card-header">
            <h4><i class="bi bi-envelope"></i> {{ __('Test email settings') }}</h4>
            {{ __('Send a test email using your settings') }}
        </div>
        <!-- end card-header -->

        <div class="card-body">

            <form action="{{ route('admin') }}" method="post" class="row row-cols-lg-auto g-3 align-items-center">
                @csrf

                <div class="col-12">
                    <input type="email" class="form-control" name="test_email" placeholder="{{ __('Input email') }}" required>
                </div>

                <div class="mr-3"></div>
                <button type="submit" class="btn btn-primary">{{ __('Send test email') }}</button>
        </div>
        </form>

    </div>

    </div>
