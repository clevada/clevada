<!-- Color picker -->
<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">

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
                        <labeL>{{ __('Spacer height') }}</labeL>
                        <select class="form-select" name="height">
                            <option @if(($block_extra['height'] ?? null) == 10) selected @endif value="10">10 {{ __('pixels') }}</option>
                            <option @if(($block_extra['height'] ?? null) == 20) selected @endif value="20">20 {{ __('pixels') }}</option>
                            <option @if(($block_extra['height'] ?? null) == 30) selected @endif value="30">30 {{ __('pixels') }}</option>
                            <option @if(($block_extra['height'] ?? null) == 50) selected @endif value="50">50 {{ __('pixels') }}</option>
                            <option @if(($block_extra['height'] ?? null) == 100) selected @endif value="100">100 {{ __('pixels') }}</option>
                            <option @if(($block_extra['height'] ?? null) == 200) selected @endif value="200">200 {{ __('pixels') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_hr" name="use_hr" @if ($block_extra['use_hr'] ?? null) checked @endif>
                            <label class="form-check-label" for="use_hr">{{ __('Add horizontal line') }}</label>
                        </div>
                    </div>

                    <script>
                        $('#use_hr').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div_color').style.display = 'block';
                            else
                                document.getElementById('hidden_div_color').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div_color" style="display: @if (($block_extra['use_hr'] ?? null)) block @else none @endif">                        
                        <div class="form-group">
                            <input class="form-control form-control-color" id="hr_color" name="hr_color" value="@if (isset($block_extra['hr_color'])) {{ $block_extra['hr_color'] }} @else #eeeeee @endif">
                            <label>{{ __('Horizontal line color') }}</label>
                            <script>
                                $('#hr_color').spectrum({
                                    type: "color",
                                    showInput: true,
                                    showInitial: true,
                                    showAlpha: false,
                                    showButtons: false,
                                    allowEmpty: false,
                                });
                            </script>
                        </div>
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
