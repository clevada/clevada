<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.forum.topics') }}">{{ __('Community') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.forum.topics') }}">{{ __('Topics') }}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<section class="section">

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                    <h4 class="card-title">{{ __('All topics') }} ({{ $topics->total() }})</h4>
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
                    @if ($message == 'updated') {{ __('Updated') }} @endif
                    @if ($message == 'deleted') {{ __('Deleted') }} @endif
                </div>
            @endif


            <section>
                <form action="{{ route('admin.forum.topics') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                    <div class="col-12">
                        <input type="text" name="search_terms" placeholder="{{ __('Search author') }}" class="form-control mr-2 @if ($search_terms) is-valid @endif" value="<?= $search_terms ?>" />
                    </div>

                    <div class="col-12">
                        <select name="search_status" class="form-select me-2 mb-2 @if ($search_status) is-valid @endif">
                            <option value="">- {{ __('Any status') }} -</option>
                            <option @if ($search_status == 'active') selected @endif value="active">{{ __('Active topics') }}</option>
                            <option @if ($search_status == 'closed') selected @endif value="closed">{{ __('Closed topics') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="search_type" class="form-select me-2 mb-2 @if ($search_type) is-valid @endif">
                            <option value="">- {{ __('All types') }} -</option>
                            <option @if ($search_type == 'question') selected="selected" @endif value="question">{{ __('Question style') }}</option>
                            <option @if ($search_type == 'discussion') selected="selected" @endif value="discussion">{{ __('Discussion style') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <select name="search_sticky" class="form-select me-2 mb-2 @if ($search_sticky) is-valid @endif">
                            <option value="">- {{ __('All topics') }} -</option>
                            <option @if ($search_sticky == 1) selected="selected" @endif value="1">{{ __('Sticky topics') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-dark me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                        <a class="btn btn-light mb-2" href="{{ route('admin.forum.topics') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </section>
            <div class="mb-3"></div>

            <div class="table-responsive-md">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Details') }}</th>
                            <th width="100">{{ __('Posts') }}</th>
                            <th width="300">{{ __('Author') }}</th>
                            <th width="180">{{ __('Type') }}</th>
                            <th width="100">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($topics as $topic)
                            <tr @if ($topic->status != 'active') class="table-warning" @endif>
                                <td>
                                    @if ($topic->status == 'closed')
                                        <span class="float-right"><button class="btn btn-sm btn-warning"> {{ __('Closed') }}</button></span>
                                    @endif

                                    <h4><a target="_blank" href="{{ route('forum.topic', ['id' => $topic->id, 'slug' => $topic->slug]) }}">{{ $topic->title }}</a></h4>
                                    <div class="text-muted small">{{ __('Created at') }} {{ date_locale($topic->created_at, 'datetime') }}</div>
                                </td>

                                <td>
                                    <h4>{{ $topic->count_posts }}</h4>
                                </td>

                                <td>
                                    @if ($topic->author_avatar) <img class="logged_user_avatar rounded-circle" style="max-height:20px" src="{{ thumb($topic->author_avatar) }}">@endif
                                    {{ $topic->author_name }}
                                </td>

                                <td>
                                    @if ($topic->type == 'discussion') <button class="btn btn-light btn-sm btn-block"><i class="bi bi-chat-left-text"></i> {{ __('Discussion') }}</button> @endif
                                    @if ($topic->type == 'question') <button class="btn btn-light btn-sm btn-block"><i class="bi bi-question-square"></i> {{ __('Question') }}</button> @endif
                                </td>

                                <td>
                                    <div class="d-flex">

                                        <button data-toggle="modal" data-target="#update-topic-{{ $topic->id }}" class="btn btn-dark btn-sm mr-3"><i class="bi bi-pencil-square"></i></button>
                                        @include('admin.forum.modals.update-topic')

                                        <form method="POST" action="{{ route('admin.forum.topics.delete', ['id' => $topic->id]) }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="float-right btn btn-danger btn-sm delete-item-{{ $topic->id }}"><i class="bi bi-x-square"></i></button>
                                        </form>
                                    </div>

                                    <script>
                                        $('.delete-item-{{ $topic->id }}').click(function(e) {
                                            e.preventDefault() // Don't post the form, unless confirmed
                                            if (confirm('Are you sure to delete this topic? All Replies will be deleted.')) {
                                                $(e.target).closest('form').submit() // Post the surrounding form
                                            }
                                        });
                                    </script>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $topics->appends(['search_terms' => $search_terms, 'search_status' => $search_status, 'search_sticky' => $search_sticky, 'search_type' => $search_type])->links() }}

        </div>
        <!-- end card-body -->

    </div>

</section>
