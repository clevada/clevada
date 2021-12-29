<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages') }}">{{ __('Pages') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Update') }}</li>
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
                    <h4 class="card-title">{{ __('Update') }}</h4>
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

            <form method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card p-3 bg-light mb-4">
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Label') }}</label>
                                <input class="form-control" name="label" type="text" required value="{{ $page->label }}">
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
                                        <option @if ($page->parent_id == $root_page->id) selected @endif value="{{ $root_page->id }}">
                                            {{ $root_page->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Top section') }} [<a href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>]</label>
                                <select name="top_section_id" class="form-select">
                                    <option value="">- {{ __('No content') }} -</option>
                                    @foreach ($global_sections as $top_section)
                                        <option @if ($page->top_section_id == $top_section->id) selected @endif value="{{ $top_section->id }}">
                                            {{ $top_section->label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted small mb-2">{{ __('This section is below the navigation menu and above main content') }}</div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Bottom section') }} [<a href="{{ route('admin.template.global_sections') }}"><b>{{ __('Manage sections') }}</b></a>]</label>
                                <select name="bottom_section_id" class="form-select">
                                    <option value="">- {{ __('No content') }} -</option>
                                    @foreach ($global_sections as $bottom_section)
                                        <option @if ($page->bottom_section_id == $bottom_section->id) selected @endif value="{{ $bottom_section->id }}">
                                            {{ $bottom_section->label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted small mb-2">{{ __('This section id below the main content and above footer') }}</div>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>{{ __('Sidebar') }}</label>
                                <select name="sidebar_position" class="form-select" id="sidebar_position" onchange="change_sidebar()">
                                    <option value="">{{ __('No sidebar') }}</option>
                                    <option @if ($page->sidebar_position == 'left') selected @endif value="left">{{ __('Sidebar at the left') }}</option>
                                    <option @if ($page->sidebar_position == 'right') selected @endif value="right">{{ __('Sidebar at the right') }}</option>
                                </select>
                            </div>
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

                        <div class="col-md-3 col-12">
                            <div id="hidden_div_sidebar" style="display: @if ($page->sidebar_position) block @else none @endif">

                                <div class="form-group">
                                    <label>{{ __('Select sidebar') }}</label>
                                    <select name="sidebar_id" class="form-select">
                                        <option value="">- {{ __('select') }} -</option>
                                        @foreach ($sidebars as $sidebar)
                                            <option @if ($page->sidebar_id == $sidebar->id) selected @endif value="{{ $sidebar->id }}">{{ $sidebar->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="customSwitch" name="active" @if ($page->active == 1) checked @endif>
                                    <label class="form-check-label" for="customSwitch">{{ __('Active') }}</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                @foreach ($langs as $lang)

                    @if (count(sys_langs()) > 1)
                        <h5>{!! flag($lang->code) !!} {{ $lang->name }}</h5> 
                    @endif

                    <div class="form-group">
                        <label>{{ __('Page title') }}</label>
                        <input class="form-control" name="title_{{ $lang->id }}" type="text" required value="{{ $lang->title }}">
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Custom Meta title') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="meta_title_{{ $lang->id }}" value="{{ $lang->meta_title }}">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Custom Meta description') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="meta_description_{{ $lang->id }}" value="{{ $lang->meta_description }}">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Custom permalink') }} ({{ __('optional') }})</label>
                                <input type="text" class="form-control" name="slug_{{ $lang->id }}" value="{{ $lang->slug }}">
                            </div>
                        </div>

                    </div>

                    @if (count(sys_langs()) > 1 && ! $loop->last)<hr>@endif
                    <div class="clearfix mb-3"></div>

                @endforeach


                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>

            </form>

        </div>
        <!-- end card-body -->

    </div>

</section>
