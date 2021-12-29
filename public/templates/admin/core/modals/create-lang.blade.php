<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" id="create-lang">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">{{ __('Add language') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Website short title') }}</label>
                                <input class="form-control" name="site_short_title" type="text" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Homepage meta title') }}</label>
                                <input class="form-control" name="homepage_meta_title" type="text" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Homepage meta description') }}</label>
                                <input class="form-control" name="homepage_meta_description" type="text" />
                            </div>
                        </div> 

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Language name') }}</label>
                                <input class="form-control" name="name" type="text" required />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Language code (2 characters)') }}</label>
                                <select name="code" class="form-select" required>
                                    <option selected value="">- {{ __('Select') }} -</option>
                                    @foreach($lang_codes_array as $key => $lang_code)
                                    @if($key == 'divider')<option disabled>-----------------</option>
                                    @else
                                    <option value="{{ $lang_code }}">{{ $key }} ({{ $lang_code }})</option>
                                    @endif
                                    @endforeach
                                </select>                                
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Locale') }}</label>
                                <select name="locale" class="form-select" required>
                                    <option selected value="">- {{ __('Select') }} -</option>
                                    @foreach($locales_array as $key => $locale)
                                        <option value="{{ $key }}">{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Is default language') }}</label>
                                <select name="is_default" class="form-select">
                                    <option value="1">{{ __('Yes') }}</option>
                                    <option selected="selected" value="0">{{ __('No') }}</option>
                                </select>
                            </div>
                        </div>                                  

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-select" aria-describedby="activeFrontendHelp">
                                    <option value="active">{{ __('Active') }}</option>
                                    <option value="inactive">{{ __('Inactive') }}</option>
                                    <option value="disabled">{{ __('Disabled') }}</option>
                                </select>
                                <small id="activeFrontendHelp" class="form-text text-muted">{{ __('If there are more tnah one active language, a language selector will be active in website') }}</small>
                            </div>
                        </div>
                                                        

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Timezone') }}</label>
                                <input class="form-control" name="timezone" type="text" aria-describedby="timeHelp" />
                                <small id="timeHelp" class="form-text text-muted"><a target="_blank" href="http://php.net/manual/en/timezones.php">{{ __('View timezones list') }}</a></small>
                            </div>
                        </div>                                    

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Add language') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>