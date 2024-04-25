<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.log.email') }}">{{ __('Email log') }}</a></li>					
                    <li class="breadcrumb-item">{{ __('Log details') }}</li>	
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

				<div class="col-12 mb-3">
                    @include('admin.core.layouts.menu-tools')
                </div>

                <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('Log details') }}</h4>
                </div>                           

            </div>

        </div>


		<div class="card-body">	
	
			{!! $email->message !!}

		</div>
		<!-- end card-body -->

	</div>

</section>