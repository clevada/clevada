<style>
    .input-hidden {
        position: absolute;
        left: -9999px;
    }

    input[type=radio]:checked+label>img {
        border: 4px solid #FFD33E;
        border-radius: 2%;
    }

</style>

@if ($message = Session::get('error'))
    <div class="alert alert-danger">
        @if ($message == 'select_layout')
            {{ __('Please select layout') }}
        @endif
    </div>
@endif


<h4>{{ __('Select page layout style') }}</h4>

<form method="POST" action="{{ route('admin.templates.update-layout', ['template_id' => $template->id, 'module' => $module]) }}">
    @csrf

    <div class="row">

        <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-4">
            <input type="radio" name="layout" id="12" class="input-hidden" value="full" />
            <label for="12">
                <img class="img-fluid" src="{{ asset('assets/img/layouts/12.png') }}" alt="layout">
            </label>
            {{ __('Full width blocks') }}. {{ __('Add content blocks on multiple rows') }}
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-4">
            <input type="radio" name="layout" id="4-8" class="input-hidden" value="sidebar_start" />
            <label for="4-8">
                <img class="img-fluid" src="{{ asset('assets/img/layouts/4-8.png') }}" alt="layout">
            </label>
            {{ __('Sidebar on the left side') }}. {{ __('Optionally, you can add full width blocks above') }}
        </div>    

        <div class="col-xl-2 col-md-4 col-sm-6 col-12 mb-4">
            <input type="radio" name="layout" id="8-4" class="input-hidden" value="sidebar_end" />
            <label for="8-4">
                <img class="img-fluid" src="{{ asset('assets/img/layouts/8-4.png') }}" alt="layout">
            </label>
            {{ __('Sidebar on the right side') }}. {{ __('Optionally, you can add full width blocks above') }}
        </div>
        
        <div class="col-12 mb-4">
            <button type="submit" class="btn btn-lg btn-danger">{{ __('Select this layout') }}</button>
        </div>
    </div>

</form>
