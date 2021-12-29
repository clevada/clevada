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
                    <div class="form-group">
                        <labeL>{{ __('Quote content') }}</labeL>
                        <textarea rows="3" class="form-control" name="content">{{ $block_extra['content'] ?? null }}</textarea>
                        <div class="form-text">{{ __('HTML tags allowed') }}</div>
                    </div>

                    <div class="form-group">
                        <labeL>{{ __('Source author') }} ({{ __('optional') }})</labeL>
                        <input type="text" class="form-control" name="source" value="{{ $block_extra['source'] ?? null }}">
                        <div class="form-text">{{ __('HTML tags allowed') }}</div>
                    </div>

                    <div class="form-group mb-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="use_avatar" name="use_avatar" @if ($block_extra['use_avatar'] ?? null) checked @endif>
                            <label class="form-check-label" for="use_avatar">{{ __('Add avatar image') }}</label>
                        </div>
                    </div>

                    <script>
                        $('#use_avatar').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div_avatar').style.display = 'block';
                            else
                                document.getElementById('hidden_div_avatar').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div_avatar" style="display: @if (($block_extra['use_avatar'] ?? null)) block @else none @endif">
                        <div class="form-group">
                            <label for="formFile" class="form-label">{{ __('Source avatar') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="file" id="formFile" name="avatar">
                        </div>
                        @if ($block_extra['avatar'] ?? null)
                            <a target="_blank" href="{{ image($block_extra['avatar']) }}"><img style="max-width: 300px; max-height: 100px;" src="{{ image($block_extra['avatar']) }}"
                                    class="img-fluid"></a>
                        @endif
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
