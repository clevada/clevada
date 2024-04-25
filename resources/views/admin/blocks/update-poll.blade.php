<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage block content') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        @if ($message == 'updated')
            {{ __('Updated') }}
        @endif
        @if ($message == 'created')
            {{ __('Block added. You can add content below.') }}
        @endif
    </div>
@endif

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <h4 class="card-title">@include('admin.includes.block_type_label', ['type' => $block->type]) - {{ __('Manage block content') }}</h4>
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

        <form id="updateBlock" method="post">
            @csrf
            @method('PUT')

            @php
                $block_extra = unserialize($block->extra);
            @endphp

            <div class="card p-3 bg-light mb-4">

                <div class="form-group col-lg-4 col-md-6">
                    <label class="form-label" for="blockLabel">{{ __('Label') }} ({{ __('optional') }})</label>
                    <input class="form-control" type="text" id="blockLabel" name="label" value="{{ $block->label }}">
                    <div class="form-text">{{ __('You can enter a label for this block to easily identify it from multiple blocks of the same type on a page') }}</div>
                </div>

                <div class="form-group">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="hide" name="hide" @if ($block->hide ?? null) checked @endif>
                        <label class="form-check-label" for="hide">{{ __('Hide block') }}</label>
                    </div>
                    <div class="form-text">{{ __('Hidden blocks are not displayed on website') }}</div>
                </div>               

                <hr>

                <div class="form-group">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="use_custom_poll" name="use_custom_poll" @if ($block_extra['poll_id'] ?? null) checked @endif>
                        <label class="form-check-label" for="use_custom_poll">{{ __('Use custom poll') }}</label>
                    </div>
                    <div class="small text-muted mt-1">{{ __('By default, last active poll is used. If checked, you can select a custom poll do show in this block..') }}</div>
                </div>

                <script>
                    $('#use_custom_poll').change(function() {
                        select = $(this).prop('checked');
                        if (select)
                            document.getElementById('hidden_div_poll').style.display = 'block';
                        else
                            document.getElementById('hidden_div_poll').style.display = 'none';
                    })
                </script>

                <div id="hidden_div_poll" style="display: @if (isset($block_extra['poll_id'])) block @else none @endif" class="mt-2">
                    <div class="form-group col-xl-3 col-lg-4 col-md-6 mb-0">
                        <label>{{ __('Input poll ID') }} [<a class="fw-bold" target="_blank" href="{{ route('admin.polls.index') }}">{{ __('View all polls') }}</a>]</label>
                        <input class="form-control" name="poll_id" type="number" step="1" min="1" value="@if ($block_extra['poll_id'] ?? null){{ $block_extra['poll_id'] }}@endif">
                    </div>
                </div>

            </div>

            <div class="form-group">
                <input type="hidden" name="type" value="{{ $block->type }}">
                <input type="hidden" name="referer" value="{{ $referer }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                <button type="submit" name="submit_return_to_block" value="block" class="btn btn-light ms-3">{{ __('Update and return here') }}</button>
            </div>

        </form>

    </div>
    <!-- end card-body -->

</div>
