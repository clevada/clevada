@include("{$template_view}.layouts.bottom")

<div id="footer">
    <!-- ======= Primary Footer ======= -->
    <div class="footer">
        <div class="container-xxl">
            @php
                $footer_columns = template('footer_columns') ?? 1;                
            @endphp

            @switch($footer_columns)
                @case('1')
                    @include("{$template_view}.layouts.footer-1-col", ['footer' => 'primary'])
                @break

                @case('2')
                    @include("{$template_view}.layouts.footer-2-cols", ['footer' => 'primary'])
                @break

                @case('3')
                    @include("{$template_view}.layouts.footer-3-cols", ['footer' => 'primary'])
                @break

                @case('4')
                    @include("{$template_view}.layouts.footer-4-cols", ['footer' => 'primary'])
                @break               
            @endswitch
        </div>
    </div>

    @if (template('footer2_show'))
        <!-- ======= Secondary Footer ======= -->
        <div class="footer2">
            <div class="container-xxl">
                @php
                    $footer_columns = template('footer2_columns') ?? 1;                
                @endphp
    
                @switch($footer_columns)
                    @case('1')
                        @include("{$template_view}.layouts.footer-1-col", ['footer' => 'secondary'])
                    @break
    
                    @case('2')
                        @include("{$template_view}.layouts.footer-2-cols", ['footer' => 'secondary'])
                    @break
    
                    @case('3')
                        @include("{$template_view}.layouts.footer-3-cols", ['footer' => 'secondary'])
                    @break
    
                    @case('4')
                        @include("{$template_view}.layouts.footer-4-cols", ['footer' => 'secondary'])
                    @break               
                @endswitch
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- Fancybox -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>

<!-- Prism -->
<script src="{{ asset("$template_path/assets/vendor/prism/prism.js") }}"></script>

@if(($config->addthis_code ?? null) && ($config->addthis_code_enabled ?? null))
<!-- Addthis tools -->
{!! $config->addthis_code ?? null !!}
@endif
{!! $config->template_global_footer_code ?? null !!}