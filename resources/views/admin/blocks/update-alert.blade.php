@include('admin.includes.trumbowyg-assets')

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


                <div class="form-group col-xl-2 col-lg-3 col-md-4">
                    <label>{{ __('Alert type') }}</label>
                    <select class="form-select" name="alert_type">
                        <option @if (($block_extra['type'] ?? null) == 'primary') selected @endif value="primary">{{ __('Note (info)') }}</option>
                        <option @if (($block_extra['type'] ?? null) == 'success') selected @endif value="success">{{ __('Success') }}</option>
                        <option @if (($block_extra['type'] ?? null) == 'danger') selected @endif value="danger">{{ __('Danger') }}</option>
                        <option @if (($block_extra['type'] ?? null) == 'warning') selected @endif value="warning">{{ __('Warning') }}</option>
                        <option @if (($block_extra['type'] ?? null) == 'light') selected @endif value="light">{{ __('Light') }}</option>
                    </select>
                </div>               
            </div>


            <h5 class="mb-3">{{ __('Block content') }}:</h5>


            @php
                $content_array = unserialize($block->content);
            @endphp

            <div class="form-group">
                <label>{{ __('Title') }} ({{ __('optional') }})</label>
                <input class="form-control" type="text" name="title" value="{{ $content_array['title'] ?? null }}">
            </div>

            <div class="form-group">
                <label>{{ __('Content') }}</label>
                <textarea class="form-control trumbowyg" name="content">{{ $content_array['content'] ?? null }}</textarea>
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
