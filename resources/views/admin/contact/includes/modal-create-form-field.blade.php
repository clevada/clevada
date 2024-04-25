<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" id="create-form-field">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="post" action="{{ route('admin.contact.add_field') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">{{ __('Create form field') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="bg-light px-3 pt-3 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Field type') }}</label>
                                    <select class="form-select" name="type" id="type" onchange="change_type()">
                                        <option value="text">{{ __('Text (one row)') }}</option>
                                        <option value="textarea">{{ __('Textarea (multiple rows)') }}</option>
                                        <option value="select">{{ __('Select from dropdown values (one selection)') }}</option>
                                        <option value="checkbox">{{ __('Select multiple values') }}</option>
                                        <option value="file">{{ __('Upload file') }}</option>
                                        <option value="email">{{ __('Email') }}</option>
                                        <option value="number">{{ __('Number (integer)') }}</option>
                                        <option value="month">{{ __('Month') }}</option>
                                        <option value="date">{{ __('Date (day / month / year)') }}</option>
                                        <option value="time">{{ __('Time (hour / minute)') }}</option>
                                        <option value="datetime-local">{{ __('Date and time') }}</option>
                                        <option value="color">{{ __('Color') }}</option>
                                    </select>
                                </div>
                            </div>

                            <script>
                                function change_type() {
                                    var select = document.getElementById('type');
                                    var value = select.options[select.selectedIndex].value;
                                    if (value == 'select' || value == 'checkbox') {
                                        document.getElementById('hidden_div_dropdowns').style.display = 'block';
                                    } else {
                                        document.getElementById('hidden_div_dropdowns').style.display = 'none';
                                    }
                                }
                            </script>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Field display') }}</label>
                                    <select class="form-select" name="col_md">
                                        <option value="12">{{ __('Full width') }}</option>
                                        <option value="6">{{ __('50% width (half)') }}</option>
                                        <option value="4">{{ __('33% width (a third)') }}</option>
                                        <option value="3">{{ __('25% width (quarter)') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="required" name="required">
                                        <label class="form-check-label" for="required">{{ __('Required field') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="customSwitch" name="active" checked>
                                        <label class="form-check-label" for="customSwitch">{{ __('Active') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>{{ __('Label') }}</label>
                        <input class="form-control" name="label" required>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Info') }} ({{ __('optional') }})</label>
                        <textarea class="form-control" rows="2" name="info"></textarea>
                        <div class="text-muted small">{{ __('If set, info text will be visible below the form field.') }}</div>
                    </div>

                    <div id="hidden_div_dropdowns" style="display: none">
                        <div class="form-group">
                            <label>{{ __('Values') }} ({{ __('each option in a new line') }})</label>
                            <textarea class="form-control" rows="3" name="dropdowns"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create form field') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
