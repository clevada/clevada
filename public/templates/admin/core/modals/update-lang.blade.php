<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel{{ $lang->id }}" aria-hidden="true" id="update-lang-{{ $lang->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.config.langs.show', ['id' => $lang->id]) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel{{ $lang->id }}">{{ __('Update language') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Website short title') }}</label>
                                <input class="form-control" name="site_short_title" type="text" required value="{{ $lang->site_short_title ?? null }}" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Homepage meta title') }}</label>
                                <input class="form-control" name="homepage_meta_title" type="text" required value="{{ $lang->homepage_meta_title ?? null }}" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Homepage meta description') }}</label>
                                <input class="form-control" name="homepage_meta_description" type="text" value="{{ $lang->homepage_meta_description ?? null }}" />
                            </div>
                        </div>                      

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Language name') }}</label>
                                <input class="form-control" name="name" type="text" required value="{{ $lang->name }}" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Language code') }}</label>
                                <select name="code" class="form-select" required>
                                    <option selected value="">- {{ __('Select') }} -</option>
                                    @foreach($lang_codes_array as $key => $lang_code)
                                    @if($key == 'divider')<option disabled>-----------------</option>
                                    @else
                                    <option @if($lang->code == $lang_code) selected @endif value="{{ $lang_code }}">{{ $key }} ({{ $lang_code }})</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Locale') }}</label>
                                <select name="locale" class="form-select" required>
                                    <option @if (! $lang->locale) selected @endif value="">- {{ __('Select') }} -</option>
                                    @foreach($locales_array as $key => $locale)
                                        <option @if ($lang->locale == $key) selected @endif value="{{ $key }}">{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Is default language') }}</label>
                                <select name="is_default" class="form-select">
                                    <option @if ($lang->is_default == 1) selected @endif value="1">{{ __('Yes') }}</option>
                                    <option @if ($lang->is_default == 0) selected @endif value="0">{{ __('No') }}</option>
                                </select>
                            </div>
                        </div>
                     
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-select" aria-describedby="activeFrontendHelp">
                                    <option @if ($lang->status == 'active') selected @endif value="active">{{ __('Active') }}</option>
                                    <option @if ($lang->status == 'inactive') selected @endif value="inactive">{{ __('Inactive') }}</option>
                                    <option @if ($lang->status == 'disabled') selected @endif value="disabled">{{ __('Disabled') }}</option>
                                </select>
                                <small id="activeFrontendHelp" class="form-text text-muted">{{ __('If there are more tnah one active language, a language selector will be active in website') }}</small>
                            </div>
                        </div>                           

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Timezone') }}</label>
                                <input class="form-control" name="timezone" type="text" aria-describedby="timeHelp" value="{{ $lang->timezone ?? null}}" />
                                <small id="timeHelp" class="form-text text-muted"><a target="_blank" href="http://php.net/manual/en/timezones.php">{{ __('View timezones list') }}</a></small>
                            </div>
                        </div>
                       
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update language') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>