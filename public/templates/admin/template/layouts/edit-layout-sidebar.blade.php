<div class="row mt-5">

    <div class="col-12">
        <div class="builder-col">

            <h5>{{ __('Manage sidebar') }} ({{ __('optional') }})</h5>
            <div class="text-muted small mb-1">{{ __("If you don't add a sidebar, all content blocks will have full width") }}</div>

            <a target="_blank" href="{{ route('admin.template.sidebars') }}"><b>{{ __('Manage sidebars') }}</b></a>

            <div class="mb-1"></div>

            <form action="{{ route('admin.template.sidebars.assign', ['module' => $module]) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <label>{{ __('Select sidebar') }}</label>
                        <select class="form-select form-select-lg" name="sidebar_position" id="sidebar_position" onchange="change_sidebar()">
                            <option value="">- {{ __('No sidebar') }} -</option>
                            <option @if((template("sidebar_position_$module") ?? null) == 'left') selected @endif value="left">{{ __('Sidebar at the left') }}</option>
                            <option @if((template("sidebar_position_$module") ?? null) == 'right') selected @endif value="right">{{ __('Sidebar at the right') }}</option>                            
                        </select>
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

                    <div class="col-md-6 col-lg-4">
                        <div id="hidden_div_sidebar" style="display: @if(template("sidebar_position_$module") ?? null) block @else none @endif">
                        <label>{{ __('Select sidebar') }}</label>
                        <select class="form-select form-select-lg" name="sidebar_id">
                            <option value="">- {{ __('Select sidebar') }} -</option>
                            @foreach ($sidebars as $sidebar)
                                <option @if((template("sidebar_id_$module") ?? null) == $sidebar->id) selected @endif value="{{ $sidebar->id }}">{{ $sidebar->label }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button class="btn btn-danger mt-3" type="submit">{{ __('Update sidebar') }}</button>
            </form>

        </div>
    </div>
</div>
