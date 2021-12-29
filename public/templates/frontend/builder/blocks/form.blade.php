@php
$block_data = block($block->id);

$block_header = unserialize($block_data->header ?? null);
$block_content = unserialize($block_data->content ?? null);
@endphp

<div class="container-xxl">
    <div class="block-form">

        @if ($block_header['add_header'] ?? null)
            <div class="block-form-header">
                @if ($block_header['title'] ?? null)
                    <div class="block-form-header-title">
                        {{ $block_header['title'] ?? null }}
                    </div>
                @endif

                @if ($block_header['content'] ?? null)
                    <div class="block-form-header-content">
                        {!! $block_header['content'] ?? null !!}
                    </div>
                @endif
            </div>
        @endif

        @if ($session_msg = Session::get('success'))
            <div class="alert alert-success my-3">
                @if ($session_msg == 'form_submited'){{ __('Form submitted successfully') }} @endif
            </div>
        @endif

        @if ($block_extra['form_id'])
            @php
                $form_fields = form($block_extra['form_id']);
            @endphp

            <form method="POST" action="{{ route('form.submit', ['id' => $block_extra['form_id']]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <div class="row">
                        @foreach ($form_fields as $field)
                            <div class="col-md-{{ $field['col_md'] ?? 12 }}">
                                <div class="form-group">
                                    <label class="block-form-label">{{ $field['label'] }}</label>

                                    @php
                                        $arrayTypes = explode(',', 'text,email,file,number,month,date,time,datetime-local,color');
                                    @endphp

                                    @if (in_array($field['type'], $arrayTypes))
                                        <input type="{{ $field['type'] }}" class="form-control block-form-control" name="{{ $field['id'] }}" @if ($field['required']) required @endif>
                                    @elseif ($field['type'] == 'textarea')
                                        <textarea class="form-control block-form-control" rows="4" name="{{ $field['id'] }}" @if ($field['required']) required @endif></textarea>
                                    @elseif ($field['type'] == 'select')
                                        @php
                                            $field_dropdowns_array = explode(PHP_EOL, $field['dropdowns']);
                                        @endphp
                                        <select class="form-select block-form-control" name="{{ $field['id'] }}" @if ($field['required']) required @endif>
                                            <option value="">- {{ __('Select') }}- {{ $field_dropdowns ?? null }}</option>
                                            @foreach ($field_dropdowns_array as $field_dropdown_name)
                                                <option value="{{ $field_dropdown_name }}">{{ $field_dropdown_name }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    @if ($field['info'])<div class="form-text text-muted small">{!! $field['info'] !!}</div>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="source_lang_id" value="{{ active_lang()->id ?? null }}">
                <input type="hidden" name="block_id" value="{{ $block->id ?? null }}">
                @if (check_form_have_recaptcha($block_extra['form_id']))<input type="hidden" name="recaptcha_response" id="recaptchaResponse">@endif
                <button type="submit" class="btn {{ $block_extra['submit_btn_style'] ?? 'btn-primary' }}">{{ $block_content['submit_btn_text'] ?? __('Submit') }}</button>

            </form>
        @endif

    </div>
</div>
