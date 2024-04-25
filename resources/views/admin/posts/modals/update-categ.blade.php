<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel_{{ $categ->id }}" aria-hidden="true" id="update-categ-{{ $categ->id }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form action="{{ route('admin.posts.categ.show', ['id' => $categ->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel_{{ $categ->id }}">{{ __('Update category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Category title') }}</label>
                                <input class="form-control" name="title" type="text" required value="{{ $categ->title }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Select parent category') }}</label>
                                <select class="form-select" name="parent_id">
                                    <option @if ($categ->parent_id == null) selected @endif value="">{{ __('Root (no parent)') }}</option>
                                    @foreach ($categories as $cat)
                                        {{ $level = 1 }}
                                        @include('admin.posts.loops.categories-edit-select-loop', $cat)
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Position') }}</label>
                                <input class="form-control" name="position" type="number" min="0" step="1" value="{{ $categ->position }}" />
                                <div class="text-muted small">{{ __('Position in the parent category. Leave empty to use the last position.') }}</div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Description') }} ({{ __('optional') }})</label>
                            <textarea class="form-control" name="description" rows="1">{{ $categ->description }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Custom permalink') }}</label>
                                <input class="form-control" name="slug" type="text" value="{{ $categ->slug }}" />
                                <div class="form-text text-muted small">{{ __('Leave empty to auto generate') }}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Icon code') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="icon" type="text" value="{{ $categ->icon }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formFile" class="form-label">{{ __('Change image') }} ({{ __('optional') }})</label>
                                <input class="form-control" type="file" id="formFile" name="image">
                                <div class="form-text text-muted small">{{ __('Maximum 5 MB') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta title') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="meta_title" type="text" value="{{ $categ->meta_title }}" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="meta_description" rows="1">{{ $categ->meta_description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customSwitch" name="active" @if ($categ->active == 1) checked @endif>
                                <label class="form-check-label" for="customSwitch">{{ __('Active') }}</label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update category') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
