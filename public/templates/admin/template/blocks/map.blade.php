<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.templates') }}">{{ __('Templates') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage footer block') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section">

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <h4 class="card-title">{{ __('Edit block') }} ({{ $block->type_label }})</h4>
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

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate') {{ __('Error. This block exists') }} @endif
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-info">
                    @if ($message == 'image_deleted') {{ __('Image deleted') }} @endif
                </div>
            @endif

            <form id="updateBlock" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php
                    $block_extra = unserialize($block->extra);
                @endphp

                <div class="card p-3 bg-light mb-4">
                    <div class="form-group col-xl-2 col-lg-3">
                        <labeL>{{ __('Map height (in pixels)') }}</labeL>
                        <div class="input-group">                            
                            <input type="text" class="form-control" aria-describedby="width-addon"  name="height" value="{{ $block_extra['height'] ?? null }}">
                            <span class="input-group-text" id="width-addon">{{ __('pixels') }}</span>
                        </div>
                        <div class="form-text">
                            {{ __('Example: 400') }}
                        </div>
                    </div>

                    <div class="form-group col-xl-2 col-lg-3">
                        <labeL>{{ __('Zoom') }}</labeL>
                        <div class="input-group">                            
                            <input type="text" class="form-control" name="zoom" value="{{ $block_extra['zoom'] ?? '16' }}">                            
                        </div>
                        <div class="form-text">
                            {{ __('Numeric value from 10 (minimum zoom) to 20 (maximum zoom). Default: 16') }}
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <labeL>{{ __('Address') }}</labeL>
                        <input class="form-control" type="text" name="address" value="{{ $block_extra['address'] ?? null }}">
                    </div>
                    <div class="form-text">
                        {{ __('Map will be centered automatic based on this address. Use complete address (country, region, city, street, code). Example: "Spain, Valencia, Av. de les Balears, 59".') }}
                    </div>
                </div>

                <div class="form-group">
                    <input type="hidden" name="type_id" value="{{ $block->type_id }}">
                    <input type="hidden" name="referer" value="{{ $referer }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update block') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->
    </div>
</section>
