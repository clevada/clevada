<?php
debug_backtrace() || die('Direct access not permitted'); ?>

<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="update-{{ $item->id }}" aria-hidden="true" id="update-{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data" action="{{ route('admin.ads.show', ['id' => $item->id]) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>{{ __('Label') }}</label>
                        <input class="form-control" type="text" name="label" required value="{{ $item->label }}">
                        <div class="form-text">{{ __('Input a short description to identify this ad. Label is not visible on website') }}</div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Type') }}</label>
                        <select class="form-select" name="type" id="type_{{ $item->id }}" required onchange="adsType_{{ $item->id }}()">
                            <option @if ($item->type == 'image') selected @endif value="image">{{ __('Image') }}</option>
                            <option @if ($item->type == 'code') selected @endif value="code">{{ __('Code') }}</option>
                        </select>
                    </div>

                    <script>
                        function adsType_{{ $item->id }}() {
                            var select = document.getElementById('type_{{ $item->id }}');
                            var value = select.options[select.selectedIndex].value;

                            if (value == 'image') {
                                document.getElementById('hidden_div_image_{{ $item->id }}').style.display = 'block';
                                document.getElementById('hidden_div_code_{{ $item->id }}').style.display = 'none';
                            }
                            if (value == 'code') {
                                document.getElementById('hidden_div_image_{{ $item->id }}').style.display = 'none';
                                document.getElementById('hidden_div_code_{{ $item->id }}').style.display = 'block';
                            }
                        }
                    </script>

                    <div id="hidden_div_image_{{ $item->id }}" style="display: @if ($item->type == 'image') block @else none @endif">
                        <div class="form-group">
                            <label for="formFile">{{ __('Change image') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="file" id="formFile" name="image">
                        </div>

                        <div class="form-group">
                            <label>{{ __('URL') }}</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="basic-addon1">https://</span>
                                <input type="text" class="form-control" name="url" value="{{ $item->url }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Alt tag') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="text" name="alt" value="{{ $item->alt }}">
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitchTarget_{{ $item->id }}" name="new_tab" @if ($item->new_tab == 1) checked @endif>
                                <label class="form-check-label" for="customSwitchTarget_{{ $item->id }}">{{ __('Open link in new tab') }}</label>
                            </div>
                        </div>
                    </div>

                    <div id="hidden_div_code_{{ $item->id }}" style="display: @if ($item->type == 'code') block @else none @endif">
                        <div class="form-group">
                            <label>{{ __('Code') }}</label>
                            <textarea class="form-control" rows="5" name="code">{{ $item->content }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="customSwitch_{{ $item->id }}" name="active" @if ($item->active == 1) checked @endif>
                            <label class="form-check-label" for="customSwitch_{{ $item->id }}">{{ __('Active') }}</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    @if ($item->type == 'image')
                        <input type="hidden" name="old_image" value="{{ $item->content }}">
                    @endif
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
