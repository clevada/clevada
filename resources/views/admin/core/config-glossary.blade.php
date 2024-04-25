<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.config', ['module' => 'glossary']) }}">{{ __('Glossary settings') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12">
                    @include('admin.core.includes.menu-config')
                </div>

            </div>

        </div>


        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    @if ($message == 'updated')
                        {{ __('Updated') }}
                    @endif
                </div>
            @endif



            <form method="post">
                @csrf

                <div class="alert alert-light mt-3">
                    @if (!($config->module_glossary_enabled ?? null) == 'on')
                        <div class="text-danger fw-bold mb-3"><i class="bi bi-exclamation-circle"></i> {{ __('Note: Glossary section is not enabled on website.') }}</div>
                    @endif
                    <div class="form-check form-switch mt-0 mb-0">
                        <input type='hidden' value='' name='module_glossary_enabled'>
                        <input class="form-check-input" type="checkbox" id="switchGlossary" name="module_glossary_enabled" @if (($config->module_glossary_enabled ?? null) == 'on') checked @endif>
                        <label class="form-check-label text-reset" for="switchGlossary">{{ __('Enable Glossary section on website') }}</label>
                    </div>
                </div>

                <div class="mb-2 mt-2 fw-bold fs-5">{{ __('Glossary page settings') }}</div>

                @foreach (sys_langs() as $lang)
                    @php
                        $label_key = "glossary_label_$lang->id";
                        $meta_title_key = "glossary_meta_title_$lang->id";
                        $meta_description_key = "glossary_meta_description_$lang->id";
                    @endphp

                    @if (count(sys_langs()) > 1)
                        <div class="fw-bold fs-6 mb-2">{!! flag($lang->code) !!} {{ $lang->name }}</div>
                    @endif

                    <div class="form-group">
                        <label>{{ __('Glossary label') }}</label>
                        <input type="text" class="form-control" name="glossary_label_{{ $lang->id }}" value="{{ $config->$label_key ?? null}}">
                        <div class="text-muted small">{{ __('Label is used in navigation menu ') }}</div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Glossary page meta title') }}</label>
                        <input type="text" class="form-control" name="glossary_meta_title_{{ $lang->id }}" value="{{ $config->$meta_title_key ?? null }}">
                    </div>

                    <div class="form-group">
                        <label>{{ __('Glossary page meta description') }}</label>
                        <textarea rows="2" class="form-control" name="glossary_meta_description_{{ $lang->id }}">{{ $config->$meta_description_key ?? null }}</textarea>
                    </div>

                    @if (count(sys_langs()) > 1 && !$loop->last)
                        <div class="mb-4"></div>
                    @endif
                @endforeach

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
