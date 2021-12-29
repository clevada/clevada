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
                                    <optgroup label="{{ __('Common') }}">
                                        <option value="homepage">{{ __('Homepage') }}</option>
                                        @if (($is_dropdown ?? null) != 1)<option value="dropdown">{{ __('Dropdown menu') }}</option>@endif
                                        <option value="custom">{{ __('Custom link') }}</option>
                                        <option value="page">{{ __('Static page') }}</option>
                                    </optgroup>

                                    <optgroup label="{{ __('Module') }}">
                                        @foreach ($modules as $module)
                                            <option value="module_{{ $module->module }}">{{ $module->label }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <script>
                            function showDiv() {
                                var select = document.getElementById('createmenu');                                
                                var value = select.options[select.selectedIndex].value;
                                
                                if(value == 'custom') {
                                    document.getElementById('hidden_div_custom').style.display = 'block';
                                    document.getElementById('hidden_div_page').style.display = 'none';
                                }
                                else if(value == 'page') {
                                    document.getElementById('hidden_div_page').style.display = 'block';
                                    document.getElementById('hidden_div_custom').style.display = 'none';
                                }
                                else {
                                    document.getElementById('hidden_div_page').style.display = 'none';
                                    document.getElementById('hidden_div_custom').style.display = 'none';
                                }
                            }                            
                        </script>

                        <div id="hidden_div_page" style="display: none; margin-bottom: 50px">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('Choose page') }}</label>
                                    <select name="page_id" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>

                        <div id="hidden_div_custom" style="display: none">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('URL') }}</label>
                                    <input class="form-control" name="custom_url" type="text" />
                                </div>
                            </div>
                        </div>      

                        @foreach ($langs as $lang)
                        <div class="col-12">
                            <div class="form-group">
                                <label>@if (count(sys_langs()) > 1){!! flag($lang->code) !!} @endif {{ __('Label') }}</label>
                                <input class="form-control" name="label_{{ $lang->id }}" type="text" required value="{{ $link->label ?? null }}" />
                            </div>
                        </div>
                        @endforeach                                                                        

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