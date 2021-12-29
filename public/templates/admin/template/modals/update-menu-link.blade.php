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
                                    <optgroup label="{{ __('Common') }}">
                                        <option @if ($link->type == 'homepage') selected @endif value="homepage">{{ __('Homepage') }}</option>
                                        @if (($is_dropdown ?? null) != 1)<option value="dropdown">{{ __('Dropdown menu') }}</option>@endif
                                        <option @if ($link->type == 'custom') selected @endif value="custom">{{ __('Custom link') }}</option>
                                        <option @if ($link->type == 'page') selected @endif value="page">{{ __('Static page') }}</option>
                                    </optgroup>

                                    <optgroup label="{{ __('Module') }}">
                                        @foreach ($modules as $module)
                                            <option @if ($link->type == 'module' && $link->value == $module->module) selected @endif value="module_{{ $module->module }}">{{ $module->label }}</option>
                                        @endforeach
                                    </optgroup>
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

                        <div id="hidden_div_custom_{{ $link->id }}" style="display: @if($link->type == 'custom') block @else none @endif">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('URL') }}</label>
                                    <input class="form-control" name="custom_url" type="text" value="{{ $link->value ?? null }}" />
                                </div>
                            </div>
                        </div>

                        
                        @if($link->type == 'page')
                        <div id="hidden_div_page_{{ $link->id }}" style="display: @if($link->type == 'page') block @else none @endif" class="mb-4">                           
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('Change page') }} ({{ __('optional') }})</label>
                                    <select name="page_id" class="form-control select2_{{ $link->id }}">
                                    <option value="{{ $link->value ?? null }}">@if($link->value) {{ page((int) $link->value)->label }} @else - @endif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif

                        @foreach ($langs as $lang)
                            <div class="col-12">
                                <div class="form-group">
                                    <label>@if (count(sys_langs()) > 1){!! flag($lang->code) !!} @endif {{ __('Label') }}</label>
                                    <input class="form-control" name="label_{{ $lang->id }}" type="text" required value="{{ get_menu_link_label($link->id, $lang_id = $lang->id) }}" />
                                </div>
                            </div>
                        @endforeach

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