<?php
debug_backtrace() || die('Direct access not permitted');
?>

<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="Label" aria-hidden="true" id="create-tag">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="Label">{{ __('Create tag') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Tag') }}</label>
                                <input class="form-control" name="tag" type="text" required />
                            </div>
                        </div>

                        @if (count($langs) > 1)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Language') }}</label><br />
                                    <select name="lang_id" class="form-select" required>
                                        <option value="">- {{ __('Select') }} -</option>
                                        @foreach ($langs as $lang)
                                            <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif                     

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create tag') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
