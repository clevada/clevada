<?php
debug_backtrace() || die('Direct access not permitted'); ?>

<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true" id="create">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Upload new image') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>{{ __('Label') }}</label>
                        <input class="form-control" type="text" name="label" required>
                        <div class="form-text">{{ __('Input a short description to identify this ad. Label is not visible on website') }}</div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Type') }}</label>
                        <select class="form-select" name="type" id="type" required onchange="adsType()">
                            <option value="">- {{ __('Select') }} -</option>
                            <option value="image">{{ __('Banner') }}</option>
                            <option value="code">{{ __('Ads code (JavaScript code, Google AdSense code...') }}</option>
                        </select>
                    </div>

                    <script>
                        function adsType() {
                            var select = document.getElementById('type');
                            var value = select.options[select.selectedIndex].value;

                            if (value == 'image') {
                                document.getElementById('hidden_div_image').style.display = 'block';
                                document.getElementById('hidden_div_code').style.display = 'none';
                            } 
                            if (value == 'code') {
                                document.getElementById('hidden_div_image').style.display = 'none';
                                document.getElementById('hidden_div_code').style.display = 'block';
                            }
                        }
                    </script>

                    <div id="hidden_div_image" style="display: none">
                        <div class="form-group">
                            <label for="formFile">{{ __('Upload banner image') }}</label>
                            <input class="form-control" type="file" id="formFile" name="image">
                        </div>

                        <div class="form-group">
                            <label>{{ __('URL') }}</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text" id="basic-addon1">https://</span>
                                <input type="text" class="form-control" name="url">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Alt tag') }} ({{ __('optional') }})</label>
                            <input class="form-control" type="text" name="alt">
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitchTarget" name="new_tab">
                                <label class="form-check-label" for="customSwitchTarget">{{ __('Open link in new tab') }}</label>
                            </div>
                        </div>
                    </div>

                    <div id="hidden_div_code" style="display: none">                       
                        <div class="form-group">
                            <label>{{ __('Ads code') }}</label>
                            <textarea class="form-control" rows="4" name="code"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="customSwitch" name="active" checked>
                            <label class="form-check-label" for="customSwitch">{{ __('Active') }}</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create new ad') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
