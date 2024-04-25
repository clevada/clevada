<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.files') }}">{{ __('Drive files') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12">
                <div class="alert alert-light">
                    <b>{{ __('Here you can see all files from all modules (workspace files, channels uploads, files used on website posts / pages, files uploaded in forms ...)') }}.</b>
                    <div class="mt-1 fst-italic"><i class="bi bi-exclamation-circle"></i>
                        {{ __('Note: This files can be deleted in this page. To delete a file, go to specific module where image is located. Example: to delete a file uploaded in a workspace, go to workspace files and delete it.') }}.
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-5 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Files') }} ({{ $files->total() }})</h4>
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
                @if ($message == 'created')
                    {{ __('Created') }}
                @endif
                @if ($message == 'updated')
                    {{ __('Updated') }}
                @endif
                @if ($message == 'deleted')
                    {{ __('Deleted') }}
                @endif
            </div>
        @endif

        <div class="table-responsive-md">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('File details') }}</th>
                        <th width="350">{{ __('Source') }}</th>
                        <th width="140">{{ __('Size') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($files as $file)
                        <tr>

                            <td>
                                {{ __('ID') }}: {{ $file->id }}
                                <div class="mb-1"></div>
                                {{ __('Big image URL') }}: <a target="_blank" href="{{ image($file->file) }}">{{ image($file->file) }}</a>
                                <br>
                                {{ __('Thumb URL') }}: <a target="_blank" href="{{ thumb($file->file) }}">{{ thumb($file->file) }}</a>
                            </td>

                            <td>
                                @switch($file->module)
                                    @case('channels')
                                        {{ __('Channels uploads') }}
                                    @break

                                    @case('docs')
                                        {{ __('Documentation') }}
                                    @break

                                    @case('forms')
                                        {{ __('Website form') }}
                                    @break

                                    @case('forum')
                                        {{ __('Community forum') }}
                                    @break

                                    @case('pages')
                                        {{ __('Website page') }}
                                    @break

                                    @case('posts')
                                        {{ __('Website post') }}
                                    @break

                                    @case('workspaces')
                                        {{ __('Workspace upload') }}
                                    @break

                                    @default
                                        {{ $file->module }}
                                @endswitch
                            </td>

                            <td>
                                {{ $file->size_mb }} MB
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{ $files->links() }}

    </div>
    <!-- end card-body -->

</div>
