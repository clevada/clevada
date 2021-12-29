<section class="section">
    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12">
                    <h4 class="card-title">{{ __('Dashboard') }}</h4>
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($config->site_maintenance ?? null == 1)
                <div class="alert alert-danger font-weight-bold">
                    {{ __('Site is offline (maintenance mode)') }}. <a href="{{ route('admin.config.site_offline') }}">{{ __('Change') }}</a>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'demo') {{ __('Error. This action is disabled in demo mode') }} @endif
                </div>
            @endif

            <div class="row">

                <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box noradius noborder bg-primary">
                        <i class="bi bi-people float-end text-white"></i>
                        <h5 class="text-white text-uppercase mb-4">{{ __('Accounts & Contacts') }}</h5>
                        <div class="mb-3 text-white counter">{{ $count_accounts ?? 0 }} {{ __('total') }}</div>
                        <span class="text-white">{{ $count_accounts_today ?? 0 }} {{ __('today') }}, {{ $count_accounts_last_month ?? 0 }} {{ __('last month') }}</span>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="card-box noradius noborder bg-danger">
                        <i class="bi bi-envelope-open float-end text-white"></i>
                        <h5 class="text-white text-uppercase mb-4">{{ __('Inbox') }}</h5>
                        <div class="mb-3 text-white counter">{{ $count_inbox_unread ?? 0 }} {{ __('unread') }}</div>
                        <span class="text-white">{{ $count_inbox ?? 0 }} {{ __('total messages') }}</span>
                    </div>
                </div>


                @if (check_admin_module('forum'))
                    <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="card-box noradius noborder bg-success">
                            <i class="bi bi-chat-left-text float-end text-white"></i>
                            <h5 class="text-white text-uppercase mb-4">{{ __('Forum activity') }}</h5>
                            <div class="mb-3 text-white counter">{{ $count_forum_posts ?? 0 }} {{ __('posts') }}, {{ $count_forum_topics ?? 0 }} {{ __('topics') }}</div>
                            <span class="text-white"> {{ __('Today') }}: {{ $count_forum_posts_today ?? 0 }} {{ __('posts') }}, {{ $count_forum_topics_today ?? 0 }}
                                {{ __('topics') }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="card-box noradius noborder bg-warning">
                            <i class="bi bi-exclamation-square float-end text-white"></i>
                            <h5 class="text-white text-uppercase mb-4">{{ __('Forum reports') }}</h5>
                            <div class="mb-3 text-white counter">{{ $count_forum_unprocessed_reports ?? 0 }} {{ __('reports waiting') }}</div>
                            <span class="text-white">{{ $count_forum_unprocessed_reports_today ?? 0 }} {{ __('reports today') }}</span>
                        </div>
                    </div>
                @endif

            </div>
            <!-- end row -->



            <div class="row">

                <div class="col-12 col-md-6 mt-4">

                    <span class="float-end ms-2 mb-2"><a href="{{ route('admin.accounts') }}" class="btn btn-light">{{ __('View all accounts') }}</a></span>
                    
                    <h5 class="mt-2">{{ __('Latest accounts created') }}:</h5>

                    <div class="table-responsive-md">
                        <table class="table table-bordered table-hover">                            
                            <tbody>
                                @foreach ($latest_accounts as $account)
                                    <tr>
                                        <th scope="row">
                                            @if ($account->avatar)
                                                <span class="float-start me-2"><img alt="{{ $account->name }}" style="height: 50px; height: 50px;" src="{{ image($account->avatar) }}" /></span>
                                            @endif
                                            <div class="fw-bold">
                                                <a href="{{ route('admin.accounts.show', ['id' => $account->id]) }}">{{ $account->name }}</a>
                                                <span class="text-muted fw-normal">({{ $account->email }})<span>
                                            </div>
                                            <div class="text-muted small fw-normal">{{ date_locale($account->created_at, 'datetime') }}</div>                                            
                                        </th>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                   
                </div>



                <div class="col-12 col-md-6 mt-4">

                    <span class="float-end ms-2 mb-2"><a href="{{ route('admin.forms') }}" class="btn btn-light">{{ __('View all messages') }}</a></span>

                    <h5 class="mt-2">{{ __('Latest forms messages') }}:</h5>

                    <div class="table-responsive-md">
                        <table class="table table-hover table-bordered">                            
                            <tbody>
                                @foreach ($latest_forms as $msg)
                                    <tr>
                                        <th scope="row">
                                            <div class="listing fw-bold"><a href="{{ route('admin.forms.show', ['id' => $msg->id]) }}">@if ($msg->subject) {{ $msg->subject }} @else <span class="text-danger">{{ __('no subject') }}</span>@endif</a></div>

                                            <div class="text-muted fw-normal">{{ $msg->name }} ({{ $msg->email }})</div>

                                            <div class="text-muted small">{{ date_locale($msg->created_at, 'datetime') }}</div>
                                        </th>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
