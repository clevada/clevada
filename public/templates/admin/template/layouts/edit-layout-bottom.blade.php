<div class="row">

    <div class="col-12">
        <div class="builder-col">

            <h5>{{ __('Manage bottom section') }} ({{ __('optional') }})</h5>
            <div class="text-muted small mb-1">{{ __('This section is below the main content and above footer') }}</div>

            <a target="_blank" href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>

            <div class="mb-1"></div>

            <form action="{{ route('admin.template.global_sections.assign', ['module' => $module]) }}" method="POST">
                @csrf

                <div class="col-md-6 col-lg-4">
                <label>{{ __('Select section') }}:</label>
                <select class="form-select form-select-lg" name="section_id">
                    <option value="">- {{ __('No content') }} -</option>
                    @foreach ($global_sections as $global_section)
                        <option @if (($bottom_section_id ?? null) == $global_section->id) selected @endif value="{{ $global_section->id }}">{{ $global_section->label }}</option>
                    @endforeach
                </select>
                </div>

                <input type="hidden" name="position" value="bottom">
                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button class="btn btn-danger mt-3" type="submit">{{ __('Update bottom content') }}</button>
            </form>

        </div>
    </div>
</div>
