<?php
debug_backtrace() || die('Direct access not permitted');
?>

<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="Label{{ $tag->id }}" aria-hidden="true" id="update-tag-{{ $tag->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.posts.tags.show', ['id' => $tag->id]) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="Label{{ $tag->id }}">{{ __('Update tag') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Tag') }}</label>
                                <input class="form-control" name="tag" type="text" required value="{{ $tag->tag }}" />
                            </div>
                        </div>

                        @if (count(sys_langs()) > 1)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Language') }}</label><br />
                                    <select name="lang_id" class="form-select" required>
                                        <option @if (!$tag->lang_id) selected @endif value="">- {{ __('Select') }} -</option>
                                        @foreach (sys_langs() as $lang)
                                            <option @if ($tag->lang_id == $lang->id) selected @endif value="{{ $lang->id }}">{{ $lang->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="active" id="checkbox_active_{{ $tag->id }}" @if ($tag->active == 1) checked @endif>
                                    <label for="checkbox_active_{{ $tag->id }}" class="custom-control-label"> {{ __('Active') }}</label>
                                </div>
                                <div class="form-text text-muted small">
                                    {{ __('Only active tags are displayed on website') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group" id="updateTag_{{ $tag->id }}">
                                <input id="color_{{ $tag->id }}" name="color" value="{{ $tag->color ?? '#f7f7f7' }}">
                                <label>{{ __('Tag color') }} ({{ __('optional') }})</label>
                                <script>
                                    $('#color_{{ $tag->id }}').spectrum({
                                        type: "color",
                                        showInput: true,
                                        showInitial: true,
                                        showAlpha: false,
                                        showButtons: false,
                                        allowEmpty: false,
                                        appendTo: '#updateTag_{{ $tag->id }}'
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update tag') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
