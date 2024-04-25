<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contact') }}">{{ __('Contact messages') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contact.fields') }}">{{ __('Manage custom fields') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Update') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>



<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <h4 class="card-title">{{ __('Update') }}</h4>
            </div>
        </div>

    </div>


    <div class="card-body">

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
            </div>
        @endif


        <form method="post" action="{{ route('admin.contact.update_field', ['field_id' => $field->id]) }}">
            @csrf
            @method('PUT')

            <div class="bg-light px-3 pt-3 mb-4">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <label>{{ __('Field type') }}</label>
                            <select class="form-select" name="type" id="type_{{ $field->id }}" onchange="change_type_{{ $field->id }}()">
                                <option @if ($field->type == 'text') selected @endif value="text">{{ __('Text (one row)') }}</option>
                                <option @if ($field->type == 'textarea') selected @endif value="textarea">{{ __('Textarea (multiple rows)') }}</option>
                                <option @if ($field->type == 'select') selected @endif value="select">{{ __('Select from dropdown values (one selection)') }}</option>
                                <option @if ($field->type == 'checkbox') selected @endif value="checkbox">{{ __('Select multiple values') }}</option>
                                <option @if ($field->type == 'file') selected @endif value="file">{{ __('Upload file') }}</option>
                                <option @if ($field->type == 'email') selected @endif value="email">{{ __('Email') }}</option>
                                <option @if ($field->type == 'number') selected @endif value="number">{{ __('Number (integer)') }}</option>
                                <option @if ($field->type == 'month') selected @endif value="month">{{ __('Month') }}</option>
                                <option @if ($field->type == 'date') selected @endif value="date">{{ __('Date (day / month / year)') }}</option>
                                <option @if ($field->type == 'time') selected @endif value="time">{{ __('Time (hour / minute)') }}</option>
                                <option @if ($field->type == 'datetime-local') selected @endif value="datetime-local">{{ __('Date and time') }}</option>
                                <option @if ($field->type == 'color') selected @endif value="color">{{ __('Color') }}</option>
                            </select>
                        </div>
                    </div>

                    <script>
                        function change_type_{{ $field->id }}() {
                            var select = document.getElementById('type_{{ $field->id }}');
                            var value = select.options[select.selectedIndex].value;
                            if (value == 'select' || value == 'checkbox') {
                                document.getElementById('hidden_div_dropdowns_{{ $field->id }}').style.display = 'block';
                            } else {
                                document.getElementById('hidden_div_dropdowns_{{ $field->id }}').style.display = 'none';
                            }
                        }
                    </script>

                    <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <label>{{ __('Field display') }}</label>
                            <select class="form-select" name="col_md">
                                <option @if ($field->col_md == '12') selected @endif value="12">{{ __('Full width') }}</option>
                                <option @if ($field->col_md == '6') selected @endif value="6">{{ __('50% width (half)') }}</option>
                                <option @if ($field->col_md == '4') selected @endif value="4">{{ __('33% width (a third)') }}</option>
                                <option @if ($field->col_md == '3') selected @endif value="3">{{ __('25% width (quarter)') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="required_{{ $field->id }}" name="required" @if ($field->required) checked @endif>
                                <label class="form-check-label" for="required_{{ $field->id }}">{{ __('Required field') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitch_{{ $field->id }}" name="active" @if ($field->active) checked @endif>
                                <label class="form-check-label" for="customSwitch_{{ $field->id }}">{{ __('Active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Label') }}</label>
                <input class="form-control" name="label" value="{{ $field->label ?? null }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('Info') }} ({{ __('optional') }})</label>
                <textarea class="form-control" rows="2" name="info">{{ $field->info ?? null }}</textarea>
                <div class="text-muted small">{{ __('If set, info text will be visible below the form field.') }}</div>
            </div>

            <div id="hidden_div_dropdowns_{{ $field->id }}" style="display: @if ($field->type == 'select' || $field->type == 'checkbox') block @else none @endif">
                <div class="form-group">
                    <label>{{ __('Values') }} ({{ __('each option in a new line') }})</label>
                    <textarea class="form-control" rows="4" name="dropdowns">{{ $field->dropdowns ?? null }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>


    </div>
    <!-- end card-body -->

</div>
