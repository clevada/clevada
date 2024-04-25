@include('admin.includes.color-picker')

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">{{ __('Posts ') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Tags') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-12 mb-3">
                @include('admin.posts.includes.menu')
            </div>

            <div class="col-12 col-sm-12 col-md-6 order-md-1 order-first">
                <h4 class="card-title">{{ __('Posts tags') }} ({{ $tags->total() }})</h4>
            </div>

            <div class="col-12 col-sm-12 col-md-6 order-md-2 order-last">
                <div class="float-end">
                    <span class="float-end"><button data-bs-toggle="modal" data-bs-target="#create-tag" class="btn btn-gear"><i class="bi bi-plus-circle"></i>
                            {{ __('Create tag') }}</button></span>
                    @include('admin.posts.modals.create-tag')

                </div>
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                @if ($message == 'duplicate')
                    {{ __('Error. This tag exist') }}
                @endif
            </div>
        @endif

        <section>
            <form action="{{ route('admin.posts.tags') }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">

                <div class="col-12">
                    <input type="text" name="search_terms" placeholder="{{ __('Search tag') }}" class="form-control me-2 mb-2 @if ($search_terms) is-valid @endif" value="<?= $search_terms ?>" />
                </div>

                @if (count(sys_langs()) > 1)
                    <div class="col-12">
                        <select name="search_lang_id" class="form-select @if ($search_lang_id) is-valid @endif me-2 mb-2">
                            <option selected="selected" value="">- {{ __('Any language') }} -</option>
                            @foreach (sys_langs() as $sys_lang)
                                <option @if ($search_lang_id == $sys_lang->id) selected @endif value="{{ $sys_lang->id }}"> {{ $sys_lang->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-12">
                    <button class="btn btn-gear me-2 mb-2" type="submit"><i class="bi bi-check2"></i> {{ __('Apply') }}</button>
                    <a class="btn btn-light mb-2" href="{{ route('admin.posts.tags') }}"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </section>

        <div class="mb-2"></div>

        <div class="table-responsive-md">

            <table class="table table-bordered table-hover sortable">
                <thead>
                    <tr>
                        <th>{{ __('Details') }}</th>
                        @if (count($langs) > 1)
                            <th width="180">{{ __('Language') }}</th>
                        @endif
                        <th width="150">{{ __('Posts') }}</th>
                        <th width="160">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="sortable">

                    @foreach ($tags as $tag)
                        @php
                            if ($tag->language->status == 'disabled') {
                                continue;
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold">
                                    {{ $tag->tag }}
                                </div>
                            </td>

                            @if (count($langs) > 1)
                                <td>
                                    {!! flag($tag->language->code) !!} {{ $tag->language->name ?? __('No language') }}
                                    @if ($tag->language->status != 'active')
                                        <span class="small text-danger">({{ __('inactive') }})</span>
                                    @endif
                                </td>
                            @endif

                            <td>
                                <a class="fw-bold fs-6" href="{{ route('admin.posts.index', ['search_tag_id' => $tag->id]) }}">{{ $tag->counter }} {{ __('posts') }}</a>
                            </td>

                            <td>
                                <div class="d-grid gap-2">
                                    <a href="#" data-bs-toggle="modal" data-bs-target=".confirm-{{ $tag->id }}" class="btn btn-danger btn-sm">{{ __('Delete tag') }}</a>
                                    <div class="modal fade confirm-{{ $tag->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Confirm delete') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Are you sure you want to delete this tag? Note: Topics assigned to this tag are not deleted.') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" action="{{ route('admin.posts.tags.show', ['id' => $tag->id]) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                        <button type="submit" class="btn btn-danger">{{ __('Yes. Delete') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{ $tags->appends(['search_terms' => $search_terms, 'search_lang_id' => $search_lang_id])->links() }}

    </div>
    <!-- end card-body -->

</div>
