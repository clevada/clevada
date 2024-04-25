<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" id="create-categ">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">{{ __('Create category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Category title') }}</label>
                                <input class="form-control" name="title" type="text" required />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Select parent category') }}</label>
                                <select class="form-select" name="parent_id">
                                    <option value="">{{ __('Root (no parent)') }}</option>

                                    @foreach ($categories as $categ)
                                        @include('admin.posts.loops.categories-add-select-loop', $categ)
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Position') }}</label>
                                <input class="form-control" name="position" type="number" min="0" step="1" />
                                <div class="text-muted small">{{ __('Position in the parent category. Leave empty to use the last position.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Custom permalink') }}</label>
                                <input class="form-control" name="slug" type="text" />
                                <div class="form-text text-muted small">{{ __('Leave empty to auto generate') }}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Icon code') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="icon" type="text" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formFile" class="form-label">{{ __('Category image') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="file" id="formFile" name="image">
                                <div class="form-text text-muted small">{{ __('Maximum 5 MB') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta title') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="meta_title" type="text" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="meta_description" rows="1"></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitch" name="active" checked>
                                <label class="form-check-label" for="customSwitch">{{ __('Active') }}</label>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Create category') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
