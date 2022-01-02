<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" id="create-categ">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="post">
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

                        @if (count(sys_langs()) > 1)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Language') }}</label><br />
                                    <select name="lang_id" class="form-select" required>
                                        <option value="">- {{ __('Select') }} -</option>
                                        @foreach (sys_langs() as $lang)
                                            <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Select parent category') }}</label>
                                <select class="form-select" name="parent_id">
                                    <option value="">{{ __('Root (no parent)') }}</option>

                                    @foreach ($categories as $categ)
                                        {{ print_r($categ) }}
                                        @include('admin.posts.loops.categories-add-select-loop', $categ)
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Position') }}</label>
                                <input class="form-control" name="position" type="text" />
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
                                <label>{{ __('Icon code') }} ({{ __('optional') }}) <a target="_blank" href="{{ route('admin.config.icons') }}"><i class="bi bi-question-circle"></i></a></label>
                                <input class="form-control" name="icon" type="text" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Badges') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="badges" type="text" />
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

                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Top section') }} [<a href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>]</label>
                                <select name="top_section_id" class="form-select">
                                    <option value="">- {{ __('No content') }} -</option>
                                    @foreach ($global_sections as $top_section)
                                        <option value="{{ $top_section->id }}">{{ $top_section->label }}</option>
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
                                        <option value="{{ $bottom_section->id }}">{{ $bottom_section->label }}</option>
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
                                    <option value="left">{{ __('Sidebar at the left') }}</option>
                                    <option value="right">{{ __('Sidebar at the right') }}</option>
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
                            <div id="hidden_div_sidebar" style="display: none">

                                <div class="form-group">
                                    <label>{{ __('Select sidebar') }}</label>
                                    <select name="sidebar_id" class="form-select">
                                        <option value="">- {{ __('select') }} -</option>
                                        @foreach ($sidebars as $sidebar)
                                            <option value="{{ $sidebar->id }}">{{ $sidebar->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
