<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel_{{ $field->id }}" aria-hidden="true" id="update-form-field-{{ $field->id }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.forms.config.update_field', ['id' => $form->id, 'field_id' => $field->id]) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel_{{ $field->id }}">{{ __('Update form field') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="bg-light px-3 pt-3 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Field type') }}</label>
                                    <select class="form-select" name="type" id="type_{{ $field->id }}" onchange="change_type_{{ $field->id }}()">
                                        <option @if($field->type == 'text') selected @endif value="text">{{ __('Text (one row)') }}</option>
                                        <option @if($field->type == 'textarea') selected @endif value="textarea">{{ __('Textarea (multiple rows)') }}</option>
                                        <option @if($field->type == 'select') selected @endif value="select">{{ __('Select from dropdown values (one selection)') }}</option>
                                        <option @if($field->type == 'checkbox') selected @endif value="checkbox">{{ __('Select multiple values') }}</option>
                                        <option @if($field->type == 'file') selected @endif value="file">{{ __('Upload file') }}</option>
                                        <option @if($field->type == 'email') selected @endif value="email">{{ __('Email') }}</option>                                        
                                        <option @if($field->type == 'number') selected @endif value="number">{{ __('Number (integer)') }}</option>
                                        <option @if($field->type == 'month') selected @endif value="month">{{ __('Month') }}</option>
                                        <option @if($field->type == 'date') selected @endif value="date">{{ __('Date (day / month / year)') }}</option>
                                        <option @if($field->type == 'time') selected @endif value="time">{{ __('Time (hour / minute)') }}</option>
                                        <option @if($field->type == 'datetime-local') selected @endif value="datetime-local">{{ __('Date and time') }}</option>
                                        <option @if($field->type == 'color') selected @endif value="color">{{ __('Color') }}</option>
                                    </select>
                                </div>
                            </div>

                            <script>
                                function change_type_{{ $field->id }}() {
                                    var select = document.getElementById('type_{{ $field->id }}');
                                    var value = select.options[select.selectedIndex].value;
                                    if (value == 'select') {
                                        @foreach ($langs as $lang)
                                            document.getElementById('hidden_div_dropdowns_{{ $field->id }}_{{ $lang->id }}').style.display = 'block';
                                        @endforeach
                                    } else {
                                        @foreach ($langs as $lang)
                                            document.getElementById('hidden_div_dropdowns_{{ $field->id }}_{{ $lang->id }}').style.display = 'none';
                                        @endforeach
                                    }
                                }
                            </script>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Field display') }}</label>
                                    <select class="form-select" name="col_md">
                                        <option @if($field->col_md == '12') selected @endif value="12">{{ __('Full width') }}</option>
                                        <option @if($field->col_md == '6') selected @endif value="6">{{ __('50% width (2 fields per row)') }}</option>
                                        <option @if($field->col_md == '4') selected @endif value="4">{{ __('33% width (3 fields per row)') }}</option>
                                        <option @if($field->col_md == '3') selected @endif value="3">{{ __('25% width (4 fields per row)') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="required_{{ $field->id }}" name="required" @if($field->required) checked @endif>
                                        <label class="form-check-label" for="required_{{ $field->id }}">{{ __('Required field') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="customSwitch_{{ $field->id }}" name="active" @if($field->active) checked @endif>
                                        <label class="form-check-label" for="customSwitch_{{ $field->id }}">{{ __('Active') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach (sys_langs() as $lang)
                        <h5 class="mb-3">@if (count(sys_langs()) > 1) {!! flag($lang->code) !!} {{ $lang->name }} @endif</h5>

                        <div class="form-group">
                            <label>{{ __('Label') }}</label>
                            <input class="form-control" name="label_{{ $lang->id }}" value="{{ form_field_lang($field->id, $lang->id)->label ?? null }}" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Info') }} ({{ __('optional') }})</label>
                            <textarea class="form-control" rows="2" name="info_{{ $lang->id }}">{{ form_field_lang($field->id, $lang->id)->info ?? null }}</textarea>
                        </div>

                        <div id="hidden_div_dropdowns_{{ $field->id }}_{{ $lang->id }}" style="display: @if($field->type == 'select') block @else none @endif">
                            <div class="form-group">
                                <label>{{ __('Dropdown values') }} ({{ __('one option in a new line') }})</label>
                                <textarea class="form-control" rows="2" name="dropdowns_{{ $lang->id }}">{{ form_field_lang($field->id, $lang->id)->dropdowns ?? null }}</textarea>
                            </div>
                        </div>

                        <div class="mb-4"></div>

                        @if (count(sys_langs()) > 1 && !$loop->last)<hr>@endif
                    @endforeach

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update form field') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
