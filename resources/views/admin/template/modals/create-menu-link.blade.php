<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />

<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" id="create-menu-link">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">{{ __('Add new link') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Link destination') }}</label>
                                <select name="type" class="form-select" id="createmenu" onchange="showDiv()" required>
                                    <option selected value="">-- {{ __('Choose an option') }} --</option>

                                    <option value="home">{{ __('Homepage') }}</option>
                                    <option value="contact">{{ __('Contact page') }}</option>
                                    @if (($is_dropdown ?? null) != 1)
                                        <option value="dropdown">{{ __('Dropdown menu') }}</option>
                                    @endif
                                    <option value="page">{{ __('Page') }}</option>
                                    <option value="custom">{{ __('Custom link') }}</option>

                                </select>
                            </div>
                        </div>

                        <script>
                            function showDiv() {
                                var select = document.getElementById('createmenu');
                                var value = select.options[select.selectedIndex].value;

                                if (value == 'custom') {
                                    document.getElementById('hidden_div_custom').style.display = 'block';
                                    document.getElementById('hidden_div_page').style.display = 'none';
                                } else if (value == 'page') {
                                    document.getElementById('hidden_div_page').style.display = 'block';
                                    document.getElementById('hidden_div_custom').style.display = 'none';
                                } else {
                                    document.getElementById('hidden_div_page').style.display = 'none';
                                    document.getElementById('hidden_div_custom').style.display = 'none';
                                }
                            }
                        </script>

                        <div id="hidden_div_custom" style="display: none">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('URL') }}</label>
                                    <input class="form-control" name="custom_url" type="text" />
                                </div>
                            </div>
                        </div>

                        <div id="hidden_div_page" style="display: none" class="mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('Select page') }}</label>
                                    <select name="page_id" class="form-control select2">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" required value="{{ $link->label ?? null }}" />
                            </div>
                        </div>

                        <div class="col-12">
                            <hr>
                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="new_tab" name="new_tab">
                                    <label class="form-check-label" for="new_tab">{{ __('Open link in new tab') }}</label>
                                </div>
                            </div>
                        </div>

                        @if (($is_dropdown ?? null) != 1)
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-check-label">{{ __('Link style') }}</label>
                                    <select name="btn_id" class="form-select">
                                        <option value="">{{ __('Link') }}</option>
                                        @foreach ($buttons as $button)
                                            <option value="{{ $button->id }}">{{ __('Button') }} ({{ $button->label }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-check-label">{{ __('Icon code') }} ({{ __('optional') }})</label>
                                <input class="form-control" name="icon">
                                <div class="small text-muted"><a target="_blank" href="{{ route('admin.config', ['module' => 'icons']) }}">{{ __('Manage icons') }}</a></div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Add menu link') }}</button>
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
        $('.select2').select2({
            dropdownParent: $('#create-menu-link'),
            minimumInputLength: 2,
            theme: "bootstrap-5",
            allowClear: true,
            placeholder: 'Search in active pages',
            ajax: {
                url: "{{ route('admin.ajax', ['source' => 'pages']) }}",
                dataType: 'json',
                delay: 20,
                cache: true
            }
        });
    });
</script>
