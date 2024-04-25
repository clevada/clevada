<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true" id="create-account-field">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="addLabel">{{ __('Create field') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Select role') }}</label>
                                <select name="role" class="form-select" required>
                                    <option selected="selected" value="">- {{ __('Select role') }} -</option>
                                    <option value="user"> {{ __('Registered user') }}</option>
                                    <option value="contributor"> {{ __('Contributor') }}</option>
                                    <option value="author"> {{ __('Author') }}</option>
                                    <option value="manager"> {{ __('Manager') }}</option>
                                    <option value="admin"> {{ __('Administrator') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Field type') }}</label>
                                <select name="type" class="form-select" required>
                                    <option value="text">{{ __('Text input (one line)') }}</option>
                                    <option value="textarea">{{ __('Textarea (multiple lines)') }}</option>
                                    <option value="date">{{ __('Date') }}</option>
                                    <option value="numeric">{{ __('Numeric') }}</option>
                                    <option value="url">{{ __('Website link') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Field name') }}</label>
                            <input class="form-control" name="name" type="text" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="fieldRequired" name="required">
                                <label class="form-check-label" for="fieldRequired">{{ __('Required field') }}</label>
                            </div>
                            <div class="text-muted small">{{ __('If checked, user must input a value to update the profile page') }}</div>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="fieldShowWebsite" name="show_on_website" checked>
                                <label class="form-check-label" for="fieldShowWebsite">{{ __('Show on the website') }}</label>
                            </div>
                            <div class="text-muted small">{{ __('If checked, the field value (if value is set) is displayed in user profile page on the website') }}</div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Help text') }}</label>
                            <input class="form-control" name="helptext" type="text" />
                            <div class="text-muted small">{{ __('It is displayed in profile form but not displayed on website') }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('Position') }}</label>
                            <input class="form-control" name="position" type="number" min="1" step="1" value="1" />
                            <div class="text-muted small">{{ __('Field position, if there are multiple fields. Input position number.') }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="fieldActive" name="active" checked>
                            <label class="form-check-label" for="fieldActive">{{ __('Active') }}</label>
                        </div>
                        <div class="text-muted small">{{ __('Only active fields are displayed on the website and users profile form') }}</div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create field') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
