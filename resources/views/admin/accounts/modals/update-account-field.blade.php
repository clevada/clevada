<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel-{{ $field->id }}" aria-hidden="true" id="update-field-{{ $field->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.accounts-fields.show', ['id' => $field->id]) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel-{{ $field->id }}">{{ __('Update field') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Select role') }}</label>
                                <select name="role" class="form-select" required>
                                    <option selected="selected" value="">- {{ __('Select role') }} -</option>
                                    <option @if ($field->role == 'user') selected @endif value="user"> {{ __('Registered user') }}</option>
                                    <option @if ($field->role == 'contributor') selected @endif value="contributor"> {{ __('Contributor') }}</option>
                                    <option @if ($field->role == 'author') selected @endif value="author"> {{ __('Author') }}</option>
                                    <option @if ($field->role == 'manager') selected @endif value="manager"> {{ __('Manager') }}</option>
                                    <option @if ($field->role == 'admin') selected @endif value="admin"> {{ __('Administrator') }}</option>
                                </select>
                            </div>
                        </div>



                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Field type') }}</label>
                                <select name="type" class="form-select" required>
                                    <option @if ($field->type == 'text') selected @endif value="text">{{ __('Text input (one line)') }}</option>
                                    <option @if ($field->type == 'textarea') selected @endif value="textarea">{{ __('Textarea (multiple lines)') }}</option>
                                    <option @if ($field->type == 'date') selected @endif value="date">{{ __('Date') }}</option>
                                    <option @if ($field->type == 'number') selected @endif value="numeric">{{ __('Numeric') }}</option>
                                    <option @if ($field->type == 'url') selected @endif value="url">{{ __('Website link') }}</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Field name') }}</label>
                            <input class="form-control" name="name" type="text" required value="{{ $field->name }}" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="fieldRequired-{{ $field->id }}" name="required" @if ($field->required == 1) checked @endif>
                                <label class="form-check-label" for="fieldRequired-{{ $field->id }}">{{ __('Required field') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="fieldShowWebsite-{{ $field->id }}" name="show_on_website" @if ($field->show_on_website == 1) checked @endif>
                                <label class="form-check-label" for="fieldShowWebsite-{{ $field->id }}">{{ __('Show on the website') }}</label>
                            </div>
                            <div class="text-muted small">{{ __('If checked, the field value (if value is set) is displayed in user profile page on the website') }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Help text') }}</label>
                        <input class="form-control" name="helptext" type="text" value="{{ $field->helptext }}" />
                        <div class="text-muted small">{{ __('It is displayed in profile form but not displayed on website') }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('Position') }}</label>
                            <input class="form-control" name="position" type="number" min="1" step="1" value="{{ $field->position ?? 1 }}" />
                            <div class="text-muted small">{{ __('Field position, if there are multiple fields. Input position number.') }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="fieldActive-{{ $field->id }}" name="active" @if ($field->active == 1) checked @endif>
                            <label class="form-check-label" for="fieldActive-{{ $field->id }}">{{ __('Active') }}</label>
                        </div>
                        <div class="text-muted small">{{ __('Only active fields are displayed on the website and users profile form') }}</div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update field') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
