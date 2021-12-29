@include('admin.includes.trumbowyg-assets')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.templates') }}">{{ __('Templates') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$template->label }}</li>
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
                    @include('admin.template.layouts.menu-template')
                </div>

            </div>

        </div>


        <div class="card-body">

            <div class="float-end"><a class="btn btn-secondary" target="_blank" href="{{ route('contact') }}"><i class="bi bi-box-arrow-up-right"></i> {{ __('Preview contact page') }}</a></div>

            <h4 class="mt-2 mb-3">{{ __('Edit contact page template') }}</h4>

            <div class="mb-3">
                @include('admin.template.layouts.menu-template-edit')
            </div>

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
                    @if ($message == 'updated'){{ __('Updated') }}@endif
                </div>
            @endif

            @if (!isset($config->contact_form_enabled) || $config->contact_form_enabled != 1)
                <div class="alert alert-danger">
                    {{ __('Warning. Contact form is disabled') }}</a>
                </div>
            @endif

            <form method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label>{{ __('Enable / disable contact form') }}</label>
                            <select name="contact_form_enabled" class="form-select" id="contact_form" aria-describedby="chelp">
                                <option @if (($config->contact_form_enabled ?? null) == 1) selected @endif value="1">{{ __('Contact form enabled') }}</option>
                                <option @if (($config->contact_form_enabled ?? null) == 0) selected @endif value="0">{{ __('Contact form disabled') }}</option>
                            </select>
                            <div id="chelp" class="form-text text-muted">{{ __('If you disable contact form, visitors can not send messages using contact page form)') }}.</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label>{{ __('Enable / disable Google Map') }}</label>
                            <select name="contact_map_enabled" class="form-select" aria-describedby="gmaphelp">
                                <option @if (($config->contact_map_enabled ?? null) == 0) selected @endif value="0">{{ __('Google Map disabled') }}</option>
                                <option @if (($config->contact_map_enabled ?? null) == 1) selected @endif value="1">{{ __('Google Map enabled') }}</option>
                            </select>
                            <div id="gmaphelp" class="form-text text-muted">{{ __('If enabled, a Google Map will be displayed in the contact page.') }}</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>{{ __('Google Map Address') }}</label>
                            <input class="form-control" name="contact_map_address" value="{{ $config->contact_map_address ?? null }}" aria-describedby="maphelp" />
                            <div id="maphelp" class="form-text text-muted">
                                {{ __('Map will be centered automatic based on this address. Use complete address (country, region, city, street, code)') }}.
                                {{ __('Example') }}: "Spain, Valencia, Av. de les Balears, 59"</div>
                        </div>
                    </div>

                    @foreach (sys_langs() as $lang)
                        @php 
                        $textarea_key = 'contact_text_lang_'.$lang->id;
                        @endphp 

                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Contact page text') }} @if (count(sys_langs()) > 1)- {{ $lang->name }} @if ($lang->is_default) ({{ __('default language') }})@endif  @endif</label>
                                <textarea class="form-control trumbowyg" name="contact_text_lang_{{ $lang->id }}">{!! $config->$textarea_key ?? null !!}</textarea>
                            </div>
                        </div>
                    @endforeach

                </div>


                <div class="row">
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Address icon code') }}</label>
                            <input class="form-control" name="contact_address_icon" value="{{ $config->contact_address_icon ?? '<i class="bi bi-map"></i>' }}" />
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Address label') }}</label>
                            <input class="form-control" name="contact_address_label" value="{{ $config->contact_address_label ?? __('Address') }}" />
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="form-group">
                            <label>{{ __('Address') }}</label>
                            <input class="form-control" name="contact_address" value="{{ $config->contact_address ?? null }}" aria-describedby="contact_address" />
                            <div id="contact_address" class="form-text text-muted">{{ __('Leave blank to not display. HTML tags allowed.') }}.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Phone icon') }}</label>
                            <input class="form-control" name="contact_phone_icon" value="{{ $config->contact_phone_icon ?? '<i class="bi bi-phone"></i>' }}" />
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Phone label') }}</label>
                            <input class="form-control" name="contact_phone_label" value="{{ $config->contact_phone_label ?? __('Phone') }}" />
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="form-group">
                            <label>{{ __('Phone') }}</label>
                            <input class="form-control" name="contact_phone" value="{{ $config->contact_phone ?? null }}" aria-describedby="contact_phone" />
                            <div id="contact_phone" class="form-text text-muted">{{ __('Leave blank to not display. HTML tags allowed.') }}.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="form-group">
                            <label>{{ __('Email icon') }}</label>
                            <input class="form-control" name="contact_email_icon" value="{{ $config->contact_email_icon ?? '<i class="bi bi-envelope"></i>' }}" />
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Email label') }}</label>
                            <input class="form-control" name="contact_email_label" value="{{ $config->contact_email_label ?? __('Email') }}" />
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="form-group">
                            <label>{{ __('Address') }}</label>
                            <input class="form-control" name="contact_email" value="{{ $config->contact_email ?? null }}" aria-describedby="contact_email" />
                            <div id="contact_email" class="form-text text-muted">{{ __('Leave blank to not display. HTML tags allowed.') }}.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Icon') }}</label>
                            <input class="form-control" name="contact_extra1_icon" value="{{ $config->contact_extra1_icon ?? null }}" />
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Label') }}</label>
                            <input class="form-control" name="contact_extra1_label" value="{{ $config->contact_extra1_label ?? null }}" />
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-8 col-12">
                        <div class="form-group">
                            <label>{{ __('Value') }}</label>
                            <input class="form-control" name="contact_extra1" value="{{ $config->contact_extra1 ?? null }}" aria-describedby="contact_extra1" />
                            <div id="contact_extra1" class="form-text text-muted">{{ __('Leave blank to not display. HTML tags allowed.') }}.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Icon') }}</label>
                            <input class="form-control" name="contact_extra2_icon" value="{{ $config->contact_extra2_icon ?? null }}" />
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="form-group">
                            <label>{{ __('Label') }}</label>
                            <input class="form-control" name="contact_extra2_label" value="{{ $config->contact_extra2_label ?? null }}" />
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-8 col-12">
                        <div class="form-group">
                            <label>{{ __('Value') }}</label>
                            <input class="form-control" name="contact_extra2" value="{{ $config->contact_extra2 ?? null }}" aria-describedby="contact_extra2" />
                            <div id="contact_extra2" class="form-text text-muted">{{ __('Leave blank to not display. HTML tags allowed.') }}.
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <button type="submit" class="btn btn-primary mt-3">{{ __('Update template') }}</button>

            </form>

            <div class="mt-4">{{ __('See also') }}: <a href="{{ route('admin.config.website', ['section' => 'antispam']) }}"><b>{{ __('Enable antispam check in contact page') }}</b></a></div>

        </div>
        <!-- end card-body -->

    </div>

</section>
