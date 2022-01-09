<?php
debug_backtrace() || die('Direct access not permitted');
?>
<div class="modal fade custom-modal" tabindex="-1" role="dialog" 
    aria-hidden="true" aria-labelledby="addBlockLabel{{ $pos ?? null }}" id="addBlock{{ $pos ?? null }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            @php
            if(($module ?? null) == 'pages') $action = route('admin.pages.content.new', ['id' => $page->id]);
            else if(($module ?? null) == 'posts') $action = route('admin.posts.content.new', ['id' => $post->id]);
            else if(($module ?? null) == 'sidebar') $action = route('admin.template.sidebars.content.new', ['id' => $sidebar->id]);
            else if(($module ?? null) == 'global') $action = route('admin.template.global_sections.content.new', ['id' => $section->id]);
            else if(($module ?? null) == 'footer') $action = route('admin.template.footer.content.new', ['template_id' => $template->id, 'footer' => $footer, 'pos' => $pos]);
            @endphp

            <form method="post" action="{{ $action ?? null }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" aria-labelledby="addBlockLabel{{ $pos ?? null }}">{{ __('Add block') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if(! ($module ?? null))
                    <div class="form-group col-lg-4 col-md-6">
                        <label class="form-label" for="blockLabel">{{ __('Block label') }}</label>
                        <input class="form-control" type="text" id="blockLabel_{{ $pos ?? null }}" name="label" required>                
                    </div>
                    @endif

                    <p><b>{{ __('Click to add a block') }}</b>. {{ __('You can manage block content and settings at the next step') }}</p>

                    <div class="row">
                        @foreach (get_block_types() as $type)
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6 col-12 mb-4">
                                <input type="radio" name="type_id" class="radio input-hidden" id="block_{{ $type->id }}_{{ $pos ?? null }}" value="{{ $type->id }}" required />
                                <label for="block_{{ $type->id }}_{{ $pos ?? null }}">
                                    <div class='text-center'>
                                        <div class="fs-1">{!! $type->icon !!}</div>
                                        <div class="mb-2">
                                            {{ $type->label }}
                                        </div>                                       
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>
                
                <div class="modal-footer">
                    <input type="hidden" name="module" value="{{ $module ?? null }}">
                    <input type="hidden" name="content_id" value="{{ $content_id ?? null }}">                    
                    <button type="submit" class="btn btn-primary">{{ __('Add block') }}</button>
                </div>

            </form>

        </div>

    </div>

</div>
