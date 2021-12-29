<?php
debug_backtrace() || die ("Direct access not permitted"); 
?>

<script>
    function showDiv(divId, element)
{
    document.getElementById(divId).style.display = element.value == '' ? 'block' : 'none';
}
</script>

<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="Label" aria-hidden="true" id="create-categ">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="Label">{{ __('Create category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Category title') }}</label>
                                <input class="form-control" name="title" type="text" required />
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Select parent category') }}</label>
                                <select class="form-select" name="parent_id" onchange="showDiv('hidden_div', this)">
                                    <option value="">{{ __('Root (no parent)') }}</option>
                                    @foreach ($categories as $categ)
                                    @include('admin.forum.loops.categories-add-select-loop', $categ)
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div id="hidden_div">
                                <div class="form-group">
                                    <label>{{ __('Select topics type') }}</label>
                                    <select name="type" class="form-select">
                                        <option value="discussion">{{ __('Regular discussion') }}</option>
                                        <option value="question">{{ __('Questions and answers') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>{{ __('Users can create topics directly in this category') }}</label>
                                <select name="allow_topics" class="form-select" aria-describedby="categHelp">
                                    <option value="1">{{ __('Yes') }}</option>
                                    <option value="0">{{ __('No') }}</option>
                                </select>
                                <small id="categHelp" class="form-text text-muted">{{ __('If No, users can create topics only in subcategories of this category') }}</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Custom URL structure') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="slug" type="text" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Icon code') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="icon" type="text" />
                            </div>
                        </div>                      

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Position') }}</label>
                                <input class="form-control" name="position" type="text" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Active</label>
                                <select name="active" class="form-select">
                                    <option value="1">{{ __('Yes') }}</option>
                                    <option value="0">{{ __('No') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta title') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="meta_title" type="text" />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Meta description') }} ({{ __('optional') }})</label>
                                <textarea class="form-control" name="meta_description" rows="2"></textarea>
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