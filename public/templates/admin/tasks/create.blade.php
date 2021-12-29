<div class="card-header">
    <span class="pull-right">
        <a class="btn btn-primary btn-dark" href="{{ route('admin.tasks') }}"><i class="fas fa-file-alt"></i> {{ __('All task') }}</a>
    </span>
    <h3><i class="far fa-plus-square"></i> {{ __('New task') }}</h3>
</div>
<!-- end card-header -->

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
        @if ($message=='error_title') Error! Input title @endif
    </div>
    @endif

    <form method="post" enctype="multipart/form-data" action="{{ route('admin.tasks') }}">
        @csrf

        <div class="row">

            <div class="col-lg-8 col-12">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" name="title" type="text" required>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="form-group">
                    <label>{{ __('Priority') }}</label>
                    <select name="priority" class="form-control" required>
                        <option selected="selected" value="0">{{ __('Normal') }}</option>
                        <option value="1">{{ __('Important') }}</option>
                        <option value="2">{{ __('Urgent') }}</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="form-group">
                    <label>Asign this task to employee operator:</label>
                    <select name="operator_user_id" class="form-control select2" aria-describedby="employeeHelpBlock">
                        <option value="">- no employee -</option>
                        @if($internals)
                        @foreach ($internals as $user)                        
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                        @endif
                    </select>
                    <small id="employeeHelpBlock" class="form-text text-muted">
                        {{ __('Leave empty for no employee. Note that admins and managers have full access to tasks.') }}
                    </small>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="form-group">
                    <label>Select client:</label>
                    <select name="client_user_id" class="form-control select_client" aria-describedby="clientHelpBlock">
                    <option value="">- no client -</option>
                    </select>
                    <small id="clientHelpBlock" class="form-text text-muted">
                        {{ __('Leave empty for no client.') }}
                    </small>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="form-group">
                    <label>Due date</label>
                    <input class="form-control" name="due_date" type="text" id="datepicker_duedate" aria-describedby="duedateHelpBlock"  autocomplete="off" />
                    <small id="duedateHelpBlock" class="form-text text-muted">
                        {{ __('Leave empty if task have not any due date') }}
                    </small>
                </div>

                <script>
                    $('#datepicker_duedate').datepicker({
                                uiLibrary: 'bootstrap4',
                                iconsLibrary: 'fontawesome',
                                format: 'yyyy-mm-dd' 
                            });
                </script>
            </div>


            <div class="col-12">
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" rows="10" name="description"></textarea>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label>Upload file (optional)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="validatedCustomFile" name="file">
                        <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                    </div>
                </div>

            </div>

            <div class="col-12">
                <div class="form-group">
                    <button type="submit" name="status" value="new" class="btn btn-dark"> Create task</button>
                </div>
            </div>

        </div><!-- end row -->

    </form>

</div>
<!-- end card-body -->