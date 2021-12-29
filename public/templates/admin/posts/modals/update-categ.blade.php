<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel_{{ $categ->id }}" aria-hidden="true" id="update-categ-{{ $categ->id }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form action="{{ route('admin.posts.categ.show', ['id' => $categ->id]) }}" method="post">
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

                        @if (count(sys_langs()) > 1)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Language') }}</label><br />
                                    <select name="lang_id" class="form-select" required>
                                        <option @if (!$categ->lang_id) selected @endif value="">- {{ __('Select') }} -</option>
                                        @foreach (sys_langs() as $lang)
                                            <option @if ($categ->lang_id == $lang->id) selected @endif value="{{ $lang->id }}">{{ $lang->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

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
                                <input class="form-control" name="position" type="text" value="{{ $categ->position }}" />
                                <div class="form-text text-muted small">{{ __('Order if there are multiple categories (same level)') }}</div>
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
                                <label>{{ __('Icon code') }} {{ __('optional') }}</label>
                                <input class="form-control" name="icon" type="text" value="{{ $categ->icon }}" />
                                <div class="form-text text-muted small"><a target="_blank" href="{{ route('admin.config.icons') }}">{{ __('Manage icons') }}</a></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Badges') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="badges" type="text" value="{{ $categ->badges }}" />
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

                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Top section') }} [<a href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>]</label>
                                <select name="top_section_id" class="form-select">
                                    <option value="">- {{ __('No content') }} -</option>
                                    @foreach ($global_sections as $top_section)
                                        <option @if ($categ->top_section_id == $top_section->id) selected @endif value="{{ $top_section->id }}">
                                            {{ $top_section->label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted small mb-2">{{ __('This section is below the navigation menu and above main content') }}</div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Bottom section') }} [<a href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>]</label>
                                <select name="bottom_section_id" class="form-select">
                                    <option value="">- {{ __('No content') }} -</option>
                                    @foreach ($global_sections as $bottom_section)
                                        <option @if ($categ->bottom_section_id == $bottom_section->id) selected @endif value="{{ $bottom_section->id }}">
                                            {{ $bottom_section->label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted small mb-2">{{ __('This section id below the main content and above footer') }}</div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Sidebar') }}</label>
                                <select name="sidebar_position" class="form-select" id="sidebar_position" onchange="change_sidebar()">
                                    <option value="">{{ __('No sidebar') }}</option>
                                    <option @if ($categ->sidebar_position == 'left') selected @endif value="left">{{ __('Sidebar at the left') }}</option>
                                    <option @if ($categ->sidebar_position == 'right') selected @endif value="right">{{ __('Sidebar at the right') }}</option>
                                </select>
                            </div>
                        </div>

                        <script>
                            function change_sidebar() {
                                var select = document.getElementById('sidebar_position');
                                var value = select.options[select.selectedIndex].value;
                                if (value == '') {
                                    document.getElementById('hidden_div_sidebar').style.display = 'none';
                                } else {
                                    document.getElementById('hidden_div_sidebar').style.display = 'block';
                                }
                            }
                        </script>

                        <div class="col-md-3 col-12">
                            <div id="hidden_div_sidebar" style="display: @if ($categ->sidebar_position) block @else none @endif">

                                <div class="form-group">
                                    <label>{{ __('Select sidebar') }}</label>
                                    <select name="sidebar_id" class="form-select">
                                        <option value="">- {{ __('select') }} -</option>
                                        @foreach ($sidebars as $sidebar)
                                            <option @if ($categ->sidebar_id == $sidebar->id) selected @endif value="{{ $sidebar->id }}">{{ $sidebar->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
