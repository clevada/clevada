@php
$block_data = block($block->id);
$block_content = unserialize($block_data->content ?? null);
$block_content = (object) $block_content;
@endphp


@if ($block_data->content ?? null)
    <div class="container-xxl">
        <div class="block-download">

            @if ($session_msg = Session::get('error'))
                <div class="alert alert-danger my-3">
                    @if ($session_msg == 'login_required'){{ __('Error. You must be logged to download this file.') }} @endif
                    @if ($session_msg == 'verify_email_required'){{ __('Error. You must verify your email before download this file.') }} @endif
                    @if ($session_msg == 'no_file'){{ __('Error. This file is not valid.') }} @endif
                </div>
            @endif

            @if ($session_msg = Session::get('success'))
                <div class="alert alert-success my-3">
                    @if ($session_msg == 'downloaded'){{ __('File downloaded.') }} @endif
                </div>
            @endif

            <div class="block-download-content">{!! $block_content->content !!}</div>

            <div class="block-download-info">
                @if ($block_extra['version']) <b>{{ 'Version' }}</b> {{ $block_extra['version'] }}@endif

                @if ($block_extra['release_date']) <div class="mb-2"></div><b>{{ 'Release date' }}</b> {{ date_locale($block_extra['release_date']) }}@endif
            </div>

            @if (($block_extra['file'] ?? null) && ($block_extra['hash'] ?? null))
                <a href="{{ route('block.download', ['id' => $block->id, 'hash' => $block_extra['hash']]) }}" class="btn {{ $block_extra['download_btn_style'] ?? 'btn-primary' }}"><i
                        class="bi bi-download"></i> {{ __('Download file') }}</a>
            @endif

        </div>
    </div>
@endif
