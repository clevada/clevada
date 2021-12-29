<style>
    .modal-backdrop {
        z-index: 1040;
    }

</style>

<hr>

<div class="form-group mb-0">

    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSettings" aria-controls="offcanvasSettings"><i class="bi bi-code-square"></i>
        {{ __('Custom block style') }}</button>

    @if ($block->module == 'homepage' || $block->module == 'posts' || $block->module == 'pages' || $block->module == 'docs')
        <a href="#" data-bs-toggle="modal" data-bs-target=".tips-{{ $block->id }}" class="btn btn-secondary ms-2"><i class="bi bi-link"></i></a>
        <div class="modal fade tips-{{ $block->id }}" tabindex="-1" role="dialog" aria-labelledby="ConfirmDeleteLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ConfirmDeleteLabel">{{ __('Direct link') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <b>{{ __('Block ID') }}: block-{{ $block->id }}</b>
                        <div class="mb-2"></div>
                        {{ __('Frontend template block') }}: <code>&lt;div id="block-{{ $block->id }}"&gt;...&lt;/div&gt;</code>
                        <div class="mb-2"></div>
                        <i class="bi bi-info-circle"></i> {{ __(' You can create a link to point this page section, using block ID anchor at the end of url') }}:
                        <br>

                        @php
                            if ($block->module == 'homepage') {
                                $block_url = route('homepage');
                            } elseif ($block->module == 'posts') {
                                $block_url = post($block->content_id)->url;
                            } elseif ($block->module == 'pages') {
                                $block_url = page($block->content_id)->url;
                            } elseif ($block->module == 'docs') {
                                $block_url = doc($block->content_id)->url;
                            }
                        @endphp

                        <input type="text" class="form-control mt-2 mb-2" value="{{ $block_url ?? null }}/#block-{{ $block->id }}" id="myInput">
                        <button onclick="myFunction()" type="button" class="btn btn-secondary"><i class="bi bi-link"></i> {{ __('Copy link') }}</button>

                        <script>
                            function myFunction() {
                                var copyText = document.getElementById("myInput");
                                copyText.select();
                                copyText.setSelectionRange(0, 99999); /* For mobile devices */
                                navigator.clipboard.writeText(copyText.value);
                                //alert("Copied the text: " + copyText.value);
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="small text-muted mt-1">{{ __('Customize CSS style for this block, using custom css code') }}</div>

</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSettings" aria-labelledby="offcanvasSettingsLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ __('Custom CSS code for this block') }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">

        {{ __('Customize CSS style for this block, using custom css code') }}. {{ __('Examples') }}:<br>
        <code>#block-{{ $block->id }} .block-{{ $block->type }} { background-color: #ccc !important }</code><br>
        <code>#block-{{ $block->id }} .block-{{ $block->type }} a { color: red !important}</code>
        <div class="mb-2"></div>

        <label>{{ __('Add custom CSS code') }}</label>

        <div class="mb-2">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#templateModal">
                {{ __('View default CSS style for this block') }}
            </button>
        </div>

        <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-body">
                        <code>
                            {!! nl2br(get_block_css_style($block->type)) !!}
                        </code>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-text mb-2 mt-2">{!! __('You can overwrite existing properties or add new selectors and properties. You must add <code>!important</code> property for all definitions, to overwrite default  values.') !!}</div>

        <textarea name="custom_css" class='form-control' style="height:70%">{!! $block->custom_css !!}</textarea>

    </div>
</div>
