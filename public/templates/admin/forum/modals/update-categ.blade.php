<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>

<script>
    function showDiv_{{ $categ->id }}(divId, element)
{
    document.getElementById(divId).style.display = element.value == '' ? 'block' : 'none';
}
</script>

<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="Label{{ $categ->id }}" aria-hidden="true" id="update-categ-{{ $categ->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.forum.categ.show', ['id' => $categ->id]) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="Label{{ $categ->id }}">{{ __('Update category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Category title') }}</label>
                                <input class="form-control" name="title" type="text" required value="{{ $categ->title }}" />
                            </div>
                        </div>

                        @if($categ->slug!='uncategorized')
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Select parent category') }}</label>
                                <select class="form-select" name="parent_id" onchange="showDiv('hidden_div_{{ $categ->id }}', this)">
                                    <option @if($categ->parent_id==null) selected @endif value="">{{ __('Root (no parent)') }}</option>
                                    @foreach ($categories as $cat)
                                    {{ $level = 1 }}
                                    @include('admin.forum.loops.categories-edit-select-loop', $cat)
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <div id="hidden_div_{{ $categ->id }}" @if($categ->parent_id) style="display: none;" @endif>
                                <div class="form-group">
                                    <label>{{ __('Select topics type') }}</label>
                                    <select class="form-select" name="type" required>
                                        <option value="">-- {{ __('Select topics type') }} --</option>
                                        <option @if($categ->type == 'discussion') selected @endif value="discussion">{{ __('Regular discussion') }}</option>
                                        <option @if($categ->type == 'question') selected @endif value="question">{{ __('Questions and answers') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Users can create topics directly in this category</label>
                                <select name="allow_topics" class="form-select" aria-describedby="categHelp">
                                    <option @if($categ->allow_topics=='1') selected @endif value="1">{{ __('Yes') }}</option>
                                    <option @if($categ->allow_topics=='0') selected @endif value="0">{{ __('No') }}</option>
                                </select>
                                <small id="categHelp" class="form-text text-muted">If No, users can create topics only in subcategories of this category</small>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="description" rows="2">{{ $categ->description }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Custom URL structure') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="slug" type="text" value="{{ $categ->slug }}" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Icon code') }} {{ __('optional') }}</label>
                                <input class="form-control" name="icon" type="text" value="{{ $categ->icon }}" />
                            </div>
                        </div>                      

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Position') }}</label>
                                <input class="form-control" name="position" type="text" value="{{ $categ->position }}" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Active') }}</label>
                                <select name="active" class="form-select">
                                    <option @if ($categ->active==1) selected @endif value="1">{{ __('Yes') }}</option>
                                    <option @if ($categ->active==0) selected @endif value="0">{{ __('No') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta title') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="meta_title" type="text" value="{{ $categ->meta_title }}" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="meta_description" rows="2">{{ $categ->meta_description }}</textarea>
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