<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel{{ $lang->id }}" aria-hidden="true" id="update-permalinks-{{ $lang->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.config.langs.permalinks', ['id' => $lang->id]) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel{{ $lang->id }}">{{ __('Update permalinks') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="slug" class="form-label">{{ __('Posts permalink') }}</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="slugAddon1">{{ config('app.url') }}/</span>
                            <input type="text" class="form-control" id="slug" aria-describedby="slugAddon1" name="posts" value="{{ $permalinks['posts'] ?? 'blog' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">{{ __('eCommerce permalink') }}</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="slugAddon2">{{ config('app.url') }}/</span>
                            <input type="text" class="form-control" id="slug" aria-describedby="slugAddon2" name="cart" value="{{ $permalinks['cart'] ?? 'shop' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">{{ __('Forum permalink') }}</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="slugAddon3">{{ config('app.url') }}/</span>
                            <input type="text" class="form-control" id="slug" aria-describedby="slugAddon3" name="forum" value="{{ $permalinks['forum'] ?? 'forum' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">{{ __('Knowledge Base permalink') }}</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="slugAddon4">{{ config('app.url') }}/</span>
                            <input type="text" class="form-control" id="slug" aria-describedby="slugAddon4" name="docs" value="{{ $permalinks['docs'] ?? 'docs' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">{{ __('Contact page permalink') }}</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="slugAddon5">{{ config('app.url') }}/</span>
                            <input type="text" class="form-control" id="slug" aria-describedby="slugAddon5" name="contact" value="{{ $permalinks['contact'] ?? 'contact' }}">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slug" class="form-label">{{ __('Tag permalink') }}</label>
                                <input type="text" class="form-control" name="tag" value="{{ $permalinks['tag'] ?? 'tag' }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slug" class="form-label">{{ __('Search permalink') }}</label>
                                <input type="text" class="form-control" name="search" value="{{ $permalinks['search'] ?? 'search' }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slug" class="form-label">{{ __('Profile permalink') }}</label>
                                <input type="text" class="form-control" name="profile" value="{{ $permalinks['profile'] ?? 'profile' }}">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>
