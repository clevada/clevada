<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages') }}">{{ __('Pages') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('New page') }}</li>
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
                    <h4 class="card-title">{{ __('New page') }}</h4>
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

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    @if ($message == 'duplicate'){{ __('Error. Page with this slug already exists') }} @endif
                    @if ($message == 'length2'){{ __('Error. Page slug must be minimum 3 characters') }} @endif
                </div>
            @endif

            <form method="post" action="{{ route('admin.pages') }}" enctype="multipart/form-data">
                @csrf

                <div class="card p-3 bg-light mb-4">
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" required>
                                <div class="form-text">
                                    {{ __('Tip: input a representative short description about this page. You can use this label to search a specific page in template editor. Label is not visible on public website.') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Parent page') }}</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">- {{ __('No parent') }} -</option>
                                    @foreach ($root_pages as $root_page)
                                        <option value="{{ $root_page->id }}">{{ $root_page->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Top section') }} [<a target="_blank" href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>]</label>
                                <select name="top_section_id" class="form-select">
                                    <option value="">- {{ __('No content') }} -</option>
                                    @foreach ($global_sections as $top_section)
                                        <option value="{{ $top_section->id }}">{{ $top_section->label }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted small mb-2">{{ __('This section is below the navigation menu and above main content') }}</div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Bottom section') }} [<a target="_blank" href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>]</label>
                                <select name="bottom_section_id" class="form-select">
                                    <option value="">- {{ __('No content') }} -</option>
                                    @foreach ($global_sections as $bottom_section)
                                        <option value="{{ $bottom_section->id }}">{{ $bottom_section->label }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted small mb-2">{{ __('This section id below the main content and above footer') }}</div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Page layout') }}</label>
                                <select name="sidebar_position" class="form-select" id="sidebar_position" onchange="change_layout()">
                                    <option value="">{{ __('Full width') }}</option>
                                    <option value="left">{{ __('Sidebar at the left') }}</option>
                                    <option value="right">{{ __('Sidebar at the right') }}</option>
                                </select>
                            </div>
                        </div>

                        <script>
                            function change_layout() {
                                var select = document.getElementById('sidebar_position');
                                var value = select.options[select.selectedIndex].value;
                                if (value == '') {
                                    document.getElementById('hidden_div_sidebar').style.display = 'none';
                                } else {
                                    document.getElementById('hidden_div_sidebar').style.display = 'block';
                                }

                            }
                        </script>

                        <div class="col-md-3 col-12">
                            <div id="hidden_div_sidebar" style="display: none">
                                <div class="form-group">
                                    <label>{{ __('Select sidebar') }} [<a target="_blank" href="{{ route('admin.template.sidebars') }}"><b>{{ __('Manage sidebars') }}</b></a>]</label>
                                    <select name="sidebar_id" class="form-select">
                                        <option value="">- {{ __('select') }} -</option>
                                        @foreach ($sidebars as $sidebar)
                                            <option value="{{ $sidebar->id }}">{{ $sidebar->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch" name="active" checked>
                                    <label class="form-check-label" for="customSwitch">{{ __('Active') }}</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                @foreach (sys_langs() as $lang)

                    @if (count(sys_langs()) > 1)
                        <h5>{!! flag($lang->code) !!} {{ $lang->name }}</h5> 
                    @endif

                    <div class="form-group">
                        <label>{{ __('Page title') }}</label>
                        <input class="form-control" name="title_{{ $lang->id }}" type="text" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Custom Meta title') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="meta_title_{{ $lang->id }}">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Custom Meta description') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="meta_description_{{ $lang->id }}">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Custom permalink') }} ({{ __('leave empty to auto generate') }})</label>
                                <input type="text" class="form-control" name="slug_{{ $lang->id }}">
                            </div>
                        </div>

                    </div>

                    @if (count(sys_langs()) > 1 && ! $loop->last)<hr>@endif
                    <div class="clearfix mb-3"></div>

                @endforeach

                <button type="submit" class="btn btn-primary">{{ __('Create page') }}</button>

                <p class="form-text mt-3">{{ __('You can add content blocks for this page at the next step') }}</p>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
