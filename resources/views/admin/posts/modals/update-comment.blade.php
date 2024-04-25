<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel{{ $comment->id }}" aria-hidden="true" id="update-comment-{{ $comment->id }}">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="updateLabel{{ $comment->id }}">{{ __('Update') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <form action="{{ route('admin.posts.comments.update', ['id' => $comment->id]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <textarea name="comment" class="form-control" rows="8">{!! $comment->comment !!}</textarea>
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-12">
                        <label>{{ __('Status') }}</label>
                        <select class='form-select' name='status'>
                            <option @if($comment->status == 'active') selected @endif value="active">{{ __('Active') }}</option>
                            <option @if($comment->status == 'pending') selected @endif value="pending">{{ __('Pending') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="search_post_id" value="{{ $search_post_id ?? null }}">
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                   
                </form>

            </div>

        </div>
    </div>
</div>
