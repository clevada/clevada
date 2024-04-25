<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.template') }}">{{ __('Template builder') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                @include('admin.template.includes.menu-template')
            </div>

        </div>

    </div>


    <div class="card-body">

        <div class="mb-3">
            @include('admin.template.includes.menu-template-edit')
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success py-2">
                @if ($message == 'updated')
                    <div class="fw-bold">{{ __('Updated') }}</div>
                    <i class="bi bi-exclamation-circle"></i>
                    {{ __("Note: if you don't see any changes on website, you can try to reload the website using CTRL+F5 or clear browser cache.") }}
                @endif
            </div>
        @endif

        <form method="post">
            @csrf
            @method('PUT')



            <div class="card bg-light p-3 mb-3">

                <div class="fw-bold fs-5">{{ __('Website home page') }}</div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Home page content') }}</label>
                            <select name="tpl_home_content_source" class="form-select">
                                <option @if (($config->tpl_home_content_source ?? null) == 'auto') selected @endif value="auto">{{ __('List of last posts') }}</option>
                                <option @if (($config->tpl_home_content_source ?? null) == 'manual') selected @endif value="manual">{{ __('Manually build home page with blocks') }}</option>
                            </select>
                            <div class="fw-bold text-info mt-1"><i class="bi bi-info-circle"></i>
                                {{ __('To build a custom homepage, selet "Manually build home page with blockse" option above, then go to Pages > Homepage > Page content.') }}</div>
                        </div>
                    </div>
                </div>


                @if (($config->tpl_home_content_source ?? null) != 'manual')
                    <div class="form-group mb-2">
                        <div class="form-check form-switch">
                            <input type='hidden' value='' name='tpl_homepage_hero_first_post'>
                            <input class="form-check-input" type="checkbox" id="tpl_homepage_hero_first_post" name="tpl_homepage_hero_first_post" @if ($config->tpl_homepage_hero_first_post ?? null) checked @endif>
                            <label class="form-check-label" for="tpl_homepage_hero_first_post">{{ __('Highlight first post') }}</label>
                        </div>
                        <div class="form-text">{{ __('If checked, first post will be on a full width row.') }} <a class="fw-bold" href="#" data-bs-toggle="modal"
                                data-bs-target="#home-example">{{ __('Show example') }}</a></div>
                    </div>


                    <div class="form-group mb-3 mt-2">
                        <div class="form-check form-switch">
                            <input type='hidden' value='' name='tpl_homepage_cta_button'>
                            <input class="form-check-input" type="checkbox" id="tpl_homepage_cta_button" name="tpl_homepage_cta_button" @if ($config->tpl_homepage_cta_button ?? null) checked @endif>
                            <label class="form-check-label" for="tpl_homepage_cta_button">{{ __('Add a call to action button') }}</label>
                        </div>
                        <div class="form-text">{{ __('Note: default button style will be used.') }} <a class="fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#home-example">{{ __('Show example') }}</a>
                        </div>
                    </div>

                    <script>
                        $('#tpl_homepage_cta_button').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div_cta_buton').style.display = 'block';
                            else
                                document.getElementById('hidden_div_cta_buton').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div_cta_buton" style="display: @if ($config->tpl_homepage_cta_button ?? null) block @else none @endif">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Call to action button label') }}</label>
                                    <input class="form-control" name="tpl_homepage_cta_button_label" value="{{ $config->tpl_homepage_cta_button_label ?? null }}">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{ __('Call to action button URL') }}</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">https://</span>
                                        <input class="form-control" name="tpl_homepage_cta_button_url" value="{{ $config->tpl_homepage_cta_button_url ?? null }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group mb-3 mt-2">
                        <div class="form-check form-switch">
                            <input type='hidden' value='' name='tpl_homepage_highlight_text'>
                            <input class="form-check-input" type="checkbox" id="tpl_homepage_highlight_text" name="tpl_homepage_highlight_text" @if ($config->tpl_homepage_highlight_text ?? null) checked @endif>
                            <label class="form-check-label" for="tpl_homepage_highlight_text">{{ __('Add a highlight text') }}</label>
                        </div>
                        <div class="form-text">{{ __('Note: title will be displayed below the menu navigation.') }} <a class="fw-bold" href="#" data-bs-toggle="modal"
                                data-bs-target="#home-example">{{ __('Show example') }}</a></div>
                    </div>

                    <script>
                        $('#tpl_homepage_highlight_text').change(function() {
                            select = $(this).prop('checked');
                            if (select)
                                document.getElementById('hidden_div_highlight_text').style.display = 'block';
                            else
                                document.getElementById('hidden_div_highlight_text').style.display = 'none';
                        })
                    </script>

                    <div id="hidden_div_highlight_text" style="display: @if ($config->tpl_homepage_highlight_text ?? null) block @else none @endif">
                        <div class="form-group">
                            <label>{{ __('Highlight title') }}</label>
                            <input class="form-control" name="tpl_homepage_highlight_title" value="{{ $config->tpl_homepage_highlight_title ?? null }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Highlight content') }}</label>
                            <textarea class="form-control" name="tpl_homepage_highlight_content" rows="4">{{ $config->tpl_homepage_highlight_content ?? null }}</textarea>
                        </div>
                    </div>

                    @include('admin.accounts.modals.help-roles')
                @endif

            </div>

            <button type="submit" class="btn btn-primary mt-3">{{ __('Update template') }}</button>
        </form>

    </div>
    <!-- end card-body -->

</div>
