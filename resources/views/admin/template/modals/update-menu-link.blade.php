<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />

<style>
    .hide {
        display: none !important;
    }
</style>

<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="updateLabel-{{ $link->id }}" aria-hidden="true" id="update-menu-link-{{ $link->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.template.menu.show', ['id' => $link->id]) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="updateLabel-{{ $link->id }}">{{ __('Update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Link destination') }}</label>
                                <select name="type" class="form-select" id="update_menu_link_{{ $link->id }}" onchange="showDiv_{{ $link->id }}()" required>
                                    <option selected value="">-- {{ __('Choose an option') }} --</option>
                                    <option @if ($link->type == 'home') selected @endif value="home">{{ __('Homepage') }}</option>
                                    <option @if ($link->type == 'contact') selected @endif value="contact">{{ __('Contact page') }}</option>
                                    @if (($is_dropdown ?? null) != 1)
                                        <option @if ($link->type == 'dropdown') selected @endif value="dropdown">{{ __('Dropdown menu') }}</option>
                                    @endif
                                    <option @if ($link->type == 'page') selected @endif value="page">{{ __('Page') }}</option>
                                    <option @if ($link->type == 'custom') selected @endif value="custom">{{ __('Custom link') }}</option>
                                </select>
                            </div>
                        </div>

                        <script>
                            function showDiv_{{ $link->id }}() {
                                var select = document.getElementById('update_menu_link_{{ $link->id }}');
                                var value = select.options[select.selectedIndex].value;

                                if (value == 'custom') {
                                    document.getElementById('hidden_div_custom_{{ $link->id }}').style.display = 'block';
                                    document.getElementById('hidden_div_page_{{ $link->id }}').style.display = 'none';
                                } else if (value == 'page') {
                                    document.getElementById('hidden_div_page_{{ $link->id }}').style.display = 'block';
                                    document.getElementById('hidden_div_custom_{{ $link->id }}').style.display = 'none';
                                } else {
                                    document.getElementById('hidden_div_page_{{ $link->id }}').style.display = 'none';
                                    document.getElementById('hidden_div_custom_{{ $link->id }}').style.display = 'none';
                                }
                            }
                        </script>

                        <div id="hidden_div_custom_{{ $link->id }}" style="display: @if ($link->type == 'custom') block @else none @endif">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('URL') }}</label>
                                    <input class="form-control" name="custom_url" type="text" value="{{ $link->value ?? null }}" />
                                </div>
                            </div>
                        </div>

                        <div id="hidden_div_page_{{ $link->id }}" style="display: @if ($link->type == 'page') block @else none @endif" class="mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('Change page') }} ({{ __('optional') }})</label>
                                    <select name="page_id" class="form-control select2_{{ $link->id }}">
                                        <option value="{{ $link->value ?? null }}">
                                            @if ($link->value)
                                                {{ page((int) $link->value)->title ?? null }}
                                            @else
                                                -
                                            @endif
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" required value="{{ $link->label }}" />
                            </div>
                        </div>


                        <div class="col-12">
                            <hr>
                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="new_tab_{{ $link->id }}" name="new_tab" @if ($link->new_tab == 1) checked @endif>
                                    <label class="form-check-label" for="new_tab_{{ $link->id }}">{{ __('Open link in new tab') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-check-label">{{ __('Link style') }}</label>
                                <select name="btn_id" class="form-select">
                                    <option value="">{{ __('Default style') }}</option>
                                    @foreach ($buttons as $button)
                                        <option @if ($link->btn_id == $button->id) selected @endif value="{{ $button->id }}">{{ __('Button') }} ({{ $button->label }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-check-label">{{ __('Icon code') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="icon" value="{{ $link->icon }}">
                                <div class="small text-muted"><a target="_blank" href="{{ route('admin.config', ['module' => 'icons']) }}">{{ __('Manage icons') }}</a></div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="parent_id" value="{{ $link->id }}">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    $(document).ready(function() {
        $(".select2-search__field").focus();
        $('.select2_{{ $link->id }}').select2({
            dropdownParent: $('#update-menu-link-{{ $link->id }}'),
            minimumInputLength: 2,
            theme: "bootstrap-5",
            allowClear: true,
            placeholder: 'Search in pages label',
            ajax: {
                url: "{{ route('admin.ajax', ['source' => 'pages']) }}",
                dataType: 'json',
                delay: 20,
                cache: true
            }
        });
    });
</script>
