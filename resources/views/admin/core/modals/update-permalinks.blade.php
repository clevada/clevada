<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden="true" id="update-permalinks">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel">{{ __('Update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="text-muted small mb-3">
                        <i class="bi bi-info-circle"></i> {{ __('Uppercases are converted to lowercases. Spaces are converted to "-". Special characters are removed.') }}
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug" class="form-label">{{ __('Contact page permalink') }}</label>
                                    <input type="text" class="form-control" name="contact" value="{{ $permalinks['contact'] ?? 'contact' }}">
                                    <div class="small text-muted">{{ __('Default: "contact"') }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug" class="form-label">{{ __('Posts tag permalink') }}</label>
                                    <input type="text" class="form-control" name="tag" value="{{ $permalinks['tag'] ?? 'tag' }}">
                                    <div class="small text-muted">{{ __('Default: "tag"') }}</div>
                                </div>
                            </div>
    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug" class="form-label">{{ __('Search permalink') }}</label>
                                    <input type="text" class="form-control" name="search" value="{{ $permalinks['search'] ?? 'search' }}">
                                    <div class="small text-muted">{{ __('Default: "search"') }}</div>
                                </div>
                            </div>
    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug" class="form-label">{{ __('Profile permalink') }}</label>
                                    <input type="text" class="form-control" name="profile" value="{{ $permalinks['profile'] ?? 'profile' }}">
                                    <div class="small text-muted">{{ __('Default: "profile"') }}</div>
                                </div>
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
