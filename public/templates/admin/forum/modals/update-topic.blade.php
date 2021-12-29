<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="update-topic-{{ $topic->id }}" aria-hidden="true" id="update-topic-{{ $topic->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.forum.topics.update', ['id' => $topic->id]) }}" method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Topic title') }}</label>
                                <input name="title" class="form-control" value="{{ $topic->title }}">
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Topic content') }}</label>
                                <textarea name="content" class="form-control editor">{{ $topic->content }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Topic style') }}</label>
                                <select name="type" class="form-control">
                                    <option @if($topic->type=='question') selected @endif value="question">{{ __('Question') }}</option>
                                    <option @if($topic->type=='discussion') selected @endif value="discussion">{{ __('Discussion') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Topic status') }}</label>
                                <select name="status" class="form-control">
                                    <option @if($topic->status=='active') selected @endif value="active">{{ __('Active') }}</option>
                                    <option @if($topic->status=='closed') selected @endif value="closed">{{ __('Closed') }}</option>
                                </select>
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